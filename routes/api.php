<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\RestaurantController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\DriverController;
use App\Http\Controllers\Api\AdminController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Public Endpoints
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected Endpoints
Route::middleware('auth:sanctum')->group(function () {
    
    // User / Auth
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Categories
    Route::apiResource('categories', CategoryController::class);

    // Restaurants
    Route::get('/restaurants', [RestaurantController::class, 'index']);
    Route::get('/restaurants/{id}', [RestaurantController::class, 'show']);
    Route::post('/restaurants', [RestaurantController::class, 'store']);
    Route::put('/restaurants/{restaurant}', [RestaurantController::class, 'update']);
    Route::delete('/restaurants/{restaurant}', [RestaurantController::class, 'destroy']);

    // Products
    Route::get('/restaurants/{restaurant}/products', [ProductController::class, 'index']);
    Route::post('/restaurants/{restaurant}/products', [ProductController::class, 'store']);
    Route::put('/products/{product}', [ProductController::class, 'update']);
    Route::delete('/products/{product}', [ProductController::class, 'destroy']);

    // Orders
    Route::get('/orders', [OrderController::class, 'index']);
    Route::post('/orders', [OrderController::class, 'store']);
    Route::get('/orders/{order}', [OrderController::class, 'show']);
    Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus']);
    Route::patch('/orders/{order}/cancel', [OrderController::class, 'cancel']);

    // Driver
    Route::post('/driver/profile', [DriverController::class, 'updateProfile']);
    Route::put('/driver/profile', [DriverController::class, 'updateProfile']);
    Route::patch('/driver/toggle-online', [DriverController::class, 'toggleOnline']);
    Route::patch('/driver/location', [DriverController::class, 'updateLocation']);
    Route::get('/driver/available-orders', [DriverController::class, 'availableOrders']);
    Route::patch('/driver/orders/{order}/accept', [DriverController::class, 'acceptOrder']);

    // Admin
    Route::get('/admin/restaurants', [AdminController::class, 'restaurants']);
    Route::get('/admin/users', [AdminController::class, 'users']);
    Route::get('/admin/orders', [AdminController::class, 'orders']);
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard']);
});
