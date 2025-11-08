<?php

use App\Http\Controllers\FileUploadController;
use Illuminate\Support\Facades\Route;

// Main page
Route::get('/', [FileUploadController::class, 'index']);

// File upload endpoints (monolith - no separate API)
Route::get('/file-uploads', [FileUploadController::class, 'list']);
Route::post('/file-uploads', [FileUploadController::class, 'upload']);
Route::get('/file-uploads/{fileUpload}', [FileUploadController::class, 'show']);
