<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\User\OrderController as UserOrderController;
use App\Http\Controllers\Api\Driver\OrderController as DriverOrderController;
use App\Http\Controllers\Api\TrackingController;
use App\Http\Controllers\Api\ProfileController;

Route::prefix('v1')->group(function () {
    // Public Endpoints
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    
    // Authenticated Endpoints
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/profile', [ProfileController::class, 'show']);
        Route::put('/profile', [ProfileController::class, 'update']);
        
        // User Endpoints
        Route::middleware('user')->prefix('user')->group(function () {
            Route::get('/orders', [UserOrderController::class, 'index']);
            Route::post('/orders/calculate-price', [UserOrderController::class, 'calculatePrice']);
            Route::post('/orders', [UserOrderController::class, 'store']);
            Route::get('/orders/{order}', [UserOrderController::class, 'show']);
            Route::post('/orders/{order}/cancel', [UserOrderController::class, 'cancel']);
            Route::get('/orders/{order}/track', [TrackingController::class, 'track']);
        });
        
        // Driver Endpoints
        Route::middleware('driver')->prefix('driver')->group(function () {
            Route::get('/orders', [DriverOrderController::class, 'index']);
            Route::get('/orders/available', [DriverOrderController::class, 'availableOrders']);
            Route::post('/orders/{order}/accept', [DriverOrderController::class, 'acceptOrder']);
            Route::post('/orders/{order}/status', [DriverOrderController::class, 'updateStatus']);
            Route::post('/location', [TrackingController::class, 'updateLocation']);
            Route::post('/status', [ProfileController::class, 'updateStatus']);
            Route::get('/earnings', [DriverOrderController::class, 'earnings']);
        });
    });
});