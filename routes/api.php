<?php

use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\KYCController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\CampayWebhookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/campay/webhook', [CampayWebhookController::class, 'handle'])->name('api.campay.webhook');
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{product}', [ProductController::class, 'show']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::post('/kyc/submit', [KYCController::class, 'submit']);
    Route::get('/profile', [ProfileController::class, 'show']);

    Route::apiResource('products', ProductController::class)->except(['index', 'show']);

    Route::get('/orders', [OrderController::class, 'index']);
    Route::post('/orders', [OrderController::class, 'store']);
    Route::post('/orders/{order}/pay', [OrderController::class, 'pay']);
    Route::post('/orders/{order}/ship', [OrderController::class, 'ship']);
    Route::post('/orders/{order}/complete', [OrderController::class, 'complete']);

    Route::post('/reviews', [ReviewController::class, 'store']);
    Route::post('/reports', [ReportController::class, 'store']);

    // Admin Routes
    Route::prefix('admin')->group(function () {
        Route::get('/kyc/pending', [AdminController::class, 'indexPendingKYC']);
        Route::post('/kyc/verify/{user}', [AdminController::class, 'verifyKYC']);
        Route::get('/reports', [ReportController::class, 'index']);
    });
});
