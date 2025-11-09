<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PackageController;
use App\Http\Controllers\Api\DestinationController;
use App\Http\Controllers\Api\TestimonialController;
use App\Http\Controllers\Api\InquiryController;
use App\Http\Controllers\Api\FloatingMemoryController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\BlogPostController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\PackageItineraryController;
use App\Http\Controllers\Api\ProposalController;
use App\Http\Controllers\Api\MicroExperienceController;
use App\Http\Controllers\Api\SettingController;

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
    Route::get('/by-slots', [FloatingMemoryController::class, 'bySlots']);
    Route::get('/{id}', [FloatingMemoryController::class, 'show']);
});

Route::prefix('inquiries')->group(function () {
    Route::post('/', [InquiryController::class, 'store']);
});

Route::prefix('blog-posts')->group(function () {
    Route::post('/', [BlogPostController::class, 'store']);
    // Route::put('/{id}', [BlogPostController::class, 'update']);
    // Route::delete('/{id}', [BlogPostController::class, 'destroy']);
    Route::get('/', [BlogPostController::class, 'index']);
    Route::get('/{id}', [BlogPostController::class, 'show']);
    Route::get('/slug/{slug}', [BlogPostController::class, 'showBySlug']);
});

Route::prefix('bookings')->group(function () {
    Route::post('/', [BookingController::class, 'store']);
    Route::get('/{id}', [BookingController::class, 'show']);
    Route::patch('/{id}/confirm', [BookingController::class, 'confirm']);
    Route::get('/{id}/payments', [PaymentController::class, 'getByBooking']);
});

Route::prefix('payments')->group(function () {
    Route::post('/', [PaymentController::class, 'store']);
    Route::get('/{id}', [PaymentController::class, 'show']);
    Route::patch('/{id}/verify', [PaymentController::class, 'verify']);
});

Route::prefix('mpesa')->group(function () {
    Route::post('/callback', [\App\Http\Controllers\Api\MpesaWebhookController::class, 'stkCallback']);
});

// Custom Itinerary submission for customers
Route::post('/custom-itinerary/submit', [PackageItineraryController::class, 'submitCustomItinerary']);

Route::post('/proposals', [ProposalController::class, 'store']);

Route::prefix('micro-experiences')->group(function () {
    Route::get('/', [MicroExperienceController::class, 'index']);
    Route::get('/category', [MicroExperienceController::class, 'byCategory']);
    Route::get('/{id}', [MicroExperienceController::class, 'show']);
});

Route::prefix('settings')->group(function () {
    Route::get('/', [SettingController::class, 'index']);
    Route::get('/{key}', [SettingController::class, 'show']);
});
