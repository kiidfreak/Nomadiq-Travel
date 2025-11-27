<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

Route::get('/', function () {
    return view('welcome');
});

// Health check endpoint for Railway
Route::get('/up', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now()->toISOString()
    ]);
});

// Debug endpoint to check environment
Route::get('/debug', function () {
    return response()->json([
        'app_env' => env('APP_ENV'),
        'php_version' => PHP_VERSION,
        'laravel_version' => app()->version(),
        'db_connection' => config('database.default'),
        'timestamp' => now()->toISOString()
    ]);
});

// Serve storage files with CORS headers
Route::get('/storage/{path}', function ($path) {
    $filePath = storage_path('app/public/' . $path);
    
    if (!file_exists($filePath)) {
        abort(404);
    }
    
    $file = file_get_contents($filePath);
    $type = mime_content_type($filePath);
    
    return Response::make($file, 200, [
        'Content-Type' => $type,
        'Access-Control-Allow-Origin' => '*',
        'Access-Control-Allow-Methods' => 'GET',
        'Access-Control-Allow-Headers' => 'Content-Type',
    ]);
})->where('path', '.*');
