<?php

namespace Tests\Feature;

use App\Models\FileUpload;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FileUploadTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('local');
    }

    /** @test */
    public function it_can_upload_a_csv_file()
    {
        Queue::fake();

        $csvContent = "UNIQUE_KEY,PRODUCT_TITLE,PRODUCT_DESCRIPTION,STYLE#,SANMAR_MAINFRAME_COLOR,SIZE,COLOR_NAME,PIECE_PRICE\n";
        $csvContent .= "TEST001,Test Product,Test Description,STY001,Blue,M,Navy,29.99\n";

        $file = UploadedFile::fake()->createWithContent('test.csv', $csvContent);

        $response = $this->postJson('/api/v1/file-uploads', [
            'file' => $file,
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'data' => [
                    'id',
                    'original_filename',
                    'status',
                    'created_at',
                ],
            ]);

        $this->assertDatabaseHas('file_uploads', [
            'original_filename' => 'test.csv',
            'status' => 'pending',
        ]);
    }

    /** @test */
    public function it_rejects_non_csv_files()
    {
        $file = UploadedFile::fake()->create('test.txt', 100);

        $response = $this->postJson('/api/v1/file-uploads', [
            'file' => $file,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['file']);
    }

    /** @test */
    public function it_detects_duplicate_files_by_hash()
    {
        Queue::fake();

        $csvContent = "UNIQUE_KEY,PRODUCT_TITLE,PRODUCT_DESCRIPTION,STYLE#,SANMAR_MAINFRAME_COLOR,SIZE,COLOR_NAME,PIECE_PRICE\n";
        $csvContent .= "TEST001,Test Product,Test Description,STY001,Blue,M,Navy,29.99\n";

        $file1 = UploadedFile::fake()->createWithContent('test1.csv', $csvContent);
        $file2 = UploadedFile::fake()->createWithContent('test2.csv', $csvContent);

        // Upload first file
        $response1 = $this->postJson('/api/v1/file-uploads', [
            'file' => $file1,
        ]);
        $response1->assertStatus(201);

        // Upload identical file with different name
        $response2 = $this->postJson('/api/v1/file-uploads', [
            'file' => $file2,
        ]);

        $response2->assertStatus(200)
            ->assertJson([
                'message' => 'File already uploaded. Re-processing...',
            ]);

        // Should only have one file upload record
        $this->assertEquals(1, FileUpload::count());
    }

    /** @test */
    public function it_can_list_all_file_uploads()
    {
        FileUpload::factory()->count(3)->create();

        $response = $this->getJson('/api/v1/file-uploads');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    /** @test */
    public function it_can_show_a_specific_file_upload()
    {
        $upload = FileUpload::factory()->create([
            'original_filename' => 'test-file.csv',
        ]);

        $response = $this->getJson("/api/v1/file-uploads/{$upload->id}");

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $upload->id,
                    'original_filename' => 'test-file.csv',
                ],
            ]);
    }

    /** @test */
    public function it_validates_required_file()
    {
        $response = $this->postJson('/api/v1/file-uploads', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['file']);
    }

    /** @test */
    public function products_can_be_created_with_unique_key()
    {
        $fileUpload = FileUpload::factory()->create();

        $product = Product::create([
            'unique_key' => 'TEST001',
            'product_title' => 'Test Product',
            'product_description' => 'Test Description',
            'style' => 'STY001',
            'sanmar_mainframe_color' => 'Blue',
            'size' => 'M',
            'color_name' => 'Navy',
            'piece_price' => 29.99,
            'file_upload_id' => $fileUpload->id,
        ]);

        $this->assertDatabaseHas('products', [
            'unique_key' => 'TEST001',
            'product_title' => 'Test Product',
        ]);

        $this->assertEquals('TEST001', $product->unique_key);
    }

    /** @test */
    public function file_upload_has_products_relationship()
    {
        $fileUpload = FileUpload::factory()->create();
        
        Product::factory()->count(3)->create([
            'file_upload_id' => $fileUpload->id,
        ]);

        $this->assertEquals(3, $fileUpload->products()->count());
    }
}
