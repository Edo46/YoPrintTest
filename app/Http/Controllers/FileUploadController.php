<?php

namespace App\Http\Controllers;

use App\Http\Resources\FileUploadResource;
use App\Jobs\ProcessCsvUpload;
use App\Models\FileUpload;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class FileUploadController extends Controller
{
    /**
     * Display the upload page
     */
    public function index(): View
    {
        return view('file-uploads');
    }

    /**
     * Get all file uploads (API endpoint for AJAX)
     */
    public function list(): JsonResponse
    {
        $uploads = FileUpload::orderBy('created_at', 'desc')->get();
        
        return response()->json([
            'data' => FileUploadResource::collection($uploads),
        ]);
    }

    /**
     * Handle file upload
     */
    public function upload(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:csv,txt', // Max 10MB
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $file = $request->file('file');
        $originalFilename = $file->getClientOriginalName();

        // Calculate file hash for idempotency
        $fileHash = md5_file($file->getRealPath());

        // Check if file was already uploaded
        $existingUpload = FileUpload::where('file_hash', $fileHash)->first();

        if ($existingUpload) {
            // Re-process existing file
            if ($existingUpload->status === 'failed' || $existingUpload->status === 'completed') {
                $existingUpload->update([
                    'status' => 'pending',
                    'error_message' => null,
                    'processed_rows' => null,
                ]);
                
                ProcessCsvUpload::dispatch($existingUpload);
            }

            return response()->json([
                'message' => 'File already uploaded. Re-processing...',
                'data' => new FileUploadResource($existingUpload),
            ], 200);
        }

        // Store file
        $storedFilename = time() . '_' . str_replace(' ', '_', $originalFilename);
        $filePath = $file->storeAs('uploads', $storedFilename);

        // Create file upload record
        $fileUpload = FileUpload::create([
            'original_filename' => $originalFilename,
            'stored_filename' => $storedFilename,
            'file_path' => $filePath,
            'file_hash' => $fileHash,
            'status' => 'pending',
        ]);

        // Dispatch job to process CSV
        ProcessCsvUpload::dispatch($fileUpload);

        return response()->json([
            'message' => 'File uploaded successfully and queued for processing',
            'data' => new FileUploadResource($fileUpload),
        ], 201);
    }

    /**
     * Get a specific upload
     */
    public function show(FileUpload $fileUpload): JsonResponse
    {
        return response()->json([
            'data' => new FileUploadResource($fileUpload),
        ]);
    }
}
