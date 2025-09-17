<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PackageController;
use App\Http\Controllers\Api\DestinationController;
use App\Http\Controllers\Api\TestimonialController;
use App\Http\Controllers\Api\InquiryController;
use App\Http\Controllers\Api\FloatingMemoryController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

// Public API routes
Route::prefix('packages')->group(function () {
    Route::get('/', [PackageController::class, 'index']);
    Route::get('/featured', [PackageController::class, 'featured']);
    Route::get('/{id}', [PackageController::class, 'show']);
});

Route::prefix('destinations')->group(function () {
    Route::get('/', [DestinationController::class, 'index']);
    Route::get('/featured', [DestinationController::class, 'featured']);
    Route::get('/{id}', [DestinationController::class, 'show']);
});

Route::prefix('testimonials')->group(function () {
    Route::get('/', [TestimonialController::class, 'index']);
    Route::get('/featured', [TestimonialController::class, 'featured']);
    Route::post('/', [TestimonialController::class, 'store']);
});

Route::prefix('memories')->group(function () {
    Route::get('/', [FloatingMemoryController::class, 'index']);
    Route::get('/latest', [FloatingMemoryController::class, 'latest']);
});

Route::prefix('inquiries')->group(function () {
    Route::post('/', [InquiryController::class, 'store']);
});
