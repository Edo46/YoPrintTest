<?php

namespace App\Jobs;

use App\Events\FileUploadStatusChanged;
use App\Models\FileUpload;
use App\Models\Product;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProcessCsvUpload implements ShouldQueue
{
    use Queueable;

    public $timeout = 600; // 10 minutes
    public $tries = 3;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public FileUpload $fileUpload
    ) {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            // Update status to processing
            $this->fileUpload->update([
                'status' => 'processing',
            ]);
            event(new FileUploadStatusChanged($this->fileUpload));

            // Read and process the CSV file
            // Use Storage::path() to get the correct full path (handles Laravel's default 'local' disk)
            $filePath = Storage::path($this->fileUpload->file_path);
            
            if (!file_exists($filePath)) {
                throw new \Exception('File not found: ' . $filePath);
            }

            $handle = fopen($filePath, 'r');
            if ($handle === false) {
                throw new \Exception('Unable to open file');
            }

            // Read header
            $header = fgetcsv($handle);
            if ($header === false) {
                throw new \Exception('Unable to read CSV header');
            }

            // Clean header (remove BOM and non-UTF-8 characters)
            $header = array_map(function ($value) {
                return $this->cleanUtf8(trim($value));
            }, $header);

            // Map header to lowercase for case-insensitive matching
            $headerMap = array_flip(array_map('strtolower', $header));

            $totalRows = 0;
            $processedRows = 0;
            $batchData = [];

            // Process rows
            while (($row = fgetcsv($handle)) !== false) {
                $totalRows++;

                // Clean each field
                $row = array_map(function ($value) {
                    return $this->cleanUtf8(trim($value));
                }, $row);

                // Combine header with row
                $data = array_combine($header, $row);

                if ($data === false) {
                    Log::warning("Failed to combine header with row at line " . ($totalRows + 1));
                    continue;
                }

                // Extract and validate required fields
                $uniqueKey = $this->getFieldValue($data, 'unique_key');
                
                if (empty($uniqueKey)) {
                    Log::warning("Missing UNIQUE_KEY at row " . ($totalRows + 1));
                    continue;
                }

                $productData = [
                    'unique_key' => $uniqueKey,
                    'product_title' => $this->getFieldValue($data, 'product_title'),
                    'product_description' => $this->getFieldValue($data, 'product_description'),
                    'style' => $this->getFieldValue($data, 'style#'),
                    'available_sizes' => $this->getFieldValue($data, 'available_sizes'),
                    'brand_logo_image' => $this->getFieldValue($data, 'brand_logo_image'),
                    'thumbnail_image' => $this->getFieldValue($data, 'thumbnail_image'),
                    'color_swatch_image' => $this->getFieldValue($data, 'color_swatch_image'),
                    'product_image' => $this->getFieldValue($data, 'product_image'),
                    'spec_sheet' => $this->getFieldValue($data, 'spec_sheet'),
                    'price_text' => $this->getFieldValue($data, 'price_text'),
                    'suggested_price' => $this->parseDecimal($this->getFieldValue($data, 'suggested_price')),
                    'category_name' => $this->getFieldValue($data, 'category_name'),
                    'subcategory_name' => $this->getFieldValue($data, 'subcategory_name'),
                    'color_name' => $this->getFieldValue($data, 'color_name'),
                    'color_square_image' => $this->getFieldValue($data, 'color_square_image'),
                    'color_product_image' => $this->getFieldValue($data, 'color_product_image'),
                    'color_product_image_thumbnail' => $this->getFieldValue($data, 'color_product_image_thumbnail'),
                    'size' => $this->getFieldValue($data, 'size'),
                    'qty' => $this->parseInt($this->getFieldValue($data, 'qty')),
                    'piece_weight' => $this->parseDecimal($this->getFieldValue($data, 'piece_weight')),
                    'piece_price' => $this->parseDecimal($this->getFieldValue($data, 'piece_price')),
                    'dozens_price' => $this->parseDecimal($this->getFieldValue($data, 'dozens_price')),
                    'case_price' => $this->parseDecimal($this->getFieldValue($data, 'case_price')),
                    'price_group' => $this->getFieldValue($data, 'price_group'),
                    'case_size' => $this->parseInt($this->getFieldValue($data, 'case_size')),
                    'inventory_key' => $this->getFieldValue($data, 'inventory_key'),
                    'size_index' => $this->parseInt($this->getFieldValue($data, 'size_index')),
                    'sanmar_mainframe_color' => $this->getFieldValue($data, 'sanmar_mainframe_color'),
                    'mill' => $this->getFieldValue($data, 'mill'),
                    'product_status' => $this->getFieldValue($data, 'product_status'),
                    'companion_styles' => $this->getFieldValue($data, 'companion_styles'),
                    'msrp' => $this->parseDecimal($this->getFieldValue($data, 'msrp')),
                    'map_pricing' => $this->parseDecimal($this->getFieldValue($data, 'map_pricing')),
                    'front_model_image_url' => $this->getFieldValue($data, 'front_model_image_url'),
                    'back_model_image' => $this->getFieldValue($data, 'back_model_image'),
                    'front_flat_image' => $this->getFieldValue($data, 'front_flat_image'),
                    'back_flat_image' => $this->getFieldValue($data, 'back_flat_image'),
                    'product_measurements' => $this->getFieldValue($data, 'product_measurements'),
                    'pms_color' => $this->getFieldValue($data, 'pms_color'),
                    'gtin' => $this->getFieldValue($data, 'gtin'),
                    'file_upload_id' => $this->fileUpload->id,
                ];

                // UPSERT - Update if exists, insert if not
                Product::updateOrCreate(
                    ['unique_key' => $uniqueKey],
                    $productData
                );

                $processedRows++;

                // Update progress periodically
                if ($processedRows % 100 === 0) {
                    $this->fileUpload->update([
                        'processed_rows' => $processedRows,
                        'total_rows' => $totalRows,
                    ]);
                    event(new FileUploadStatusChanged($this->fileUpload->fresh()));
                }
            }

            fclose($handle);

            // Update final status
            $this->fileUpload->update([
                'status' => 'completed',
                'total_rows' => $totalRows,
                'processed_rows' => $processedRows,
            ]);

            event(new FileUploadStatusChanged($this->fileUpload->fresh()));

        } catch (\Exception $e) {
            Log::error('CSV Processing failed: ' . $e->getMessage(), [
                'file_upload_id' => $this->fileUpload->id,
                'trace' => $e->getTraceAsString(),
            ]);

            $this->fileUpload->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);

            event(new FileUploadStatusChanged($this->fileUpload->fresh()));

            throw $e;
        }
    }

    /**
     * Clean non-UTF-8 characters from string and decode HTML entities
     */
    private function cleanUtf8(string $text): string
    {
        // Remove BOM
        $text = str_replace("\xEF\xBB\xBF", '', $text);
        
        // Decode HTML entities (&#174; -> ®, &reg; -> ®, etc.)
        // Use ENT_QUOTES | ENT_HTML401 to handle legacy entities like &#153;
        $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML401, 'UTF-8');
        
        // Handle remaining numeric entities that might not be decoded
        $text = preg_replace_callback('/&#(\d+);/', function ($matches) {
            return mb_chr((int)$matches[1], 'UTF-8');
        }, $text);
        
        // Convert to UTF-8 and remove invalid characters
        $text = mb_convert_encoding($text, 'UTF-8', 'UTF-8');
        
        // Remove any remaining non-UTF-8 characters
        $text = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', $text);
        
        return $text;
    }

    /**
     * Get field value from data array (case-insensitive)
     */
    private function getFieldValue(array $data, string $fieldName): ?string
    {
        // Try exact match first
        if (isset($data[$fieldName])) {
            return $data[$fieldName];
        }

        // Try case-insensitive match
        foreach ($data as $key => $value) {
            if (strtolower($key) === strtolower($fieldName)) {
                return $value;
            }
        }

        return null;
    }

    /**
     * Parse decimal value
     */
    private function parseDecimal(?string $value): ?float
    {
        if ($value === null || $value === '') {
            return null;
        }

        // Remove currency symbols and commas
        $cleaned = preg_replace('/[^0-9.-]/', '', $value);
        
        return $cleaned !== '' ? (float) $cleaned : null;
    }

    /**
     * Parse integer value
     */
    private function parseInt(?string $value): ?int
    {
        if ($value === null || $value === '') {
            return null;
        }

        // Remove non-numeric characters except minus sign
        $cleaned = preg_replace('/[^0-9-]/', '', $value);
        
        return $cleaned !== '' ? (int) $cleaned : null;
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        $this->fileUpload->update([
            'status' => 'failed',
            'error_message' => $exception->getMessage(),
        ]);

        event(new FileUploadStatusChanged($this->fileUpload->fresh()));
    }
}
