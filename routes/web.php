<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\RestaurantAdminController;
use App\Http\Controllers\Admin\ProductAdminController;
use App\Http\Controllers\Admin\CategoryAdminController;
use App\Http\Controllers\Admin\OrderAdminController;
use App\Http\Controllers\Admin\UserAdminController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

// ─── Admin Auth (guest) ────────────────────────────────────────
Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

// ─── Admin Panel (auth + role check) ──────────────────────────
Route::prefix('admin')->middleware(['auth', 'admin.role'])->group(function () {

    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index']);

    // Restaurants
    Route::get('/restaurants', [RestaurantAdminController::class, 'index'])->name('admin.restaurants.index');
    Route::get('/restaurants/create', [RestaurantAdminController::class, 'create'])->name('admin.restaurants.create');
    Route::post('/restaurants', [RestaurantAdminController::class, 'store'])->name('admin.restaurants.store');
    Route::get('/restaurants/{restaurant}/edit', [RestaurantAdminController::class, 'edit'])->name('admin.restaurants.edit');
    Route::put('/restaurants/{restaurant}', [RestaurantAdminController::class, 'update'])->name('admin.restaurants.update');
    Route::delete('/restaurants/{restaurant}', [RestaurantAdminController::class, 'destroy'])->name('admin.restaurants.destroy');

    // Products
    Route::get('/products', [ProductAdminController::class, 'index'])->name('admin.products.index');
    Route::get('/products/create', [ProductAdminController::class, 'create'])->name('admin.products.create');
    Route::post('/products', [ProductAdminController::class, 'store'])->name('admin.products.store');
    Route::get('/products/{product}/edit', [ProductAdminController::class, 'edit'])->name('admin.products.edit');
    Route::put('/products/{product}', [ProductAdminController::class, 'update'])->name('admin.products.update');
    Route::delete('/products/{product}', [ProductAdminController::class, 'destroy'])->name('admin.products.destroy');

    // Categories
    Route::get('/categories', [CategoryAdminController::class, 'index'])->name('admin.categories.index');
    Route::post('/categories', [CategoryAdminController::class, 'store'])->name('admin.categories.store');
    Route::put('/categories/{category}', [CategoryAdminController::class, 'update'])->name('admin.categories.update');
    Route::delete('/categories/{category}', [CategoryAdminController::class, 'destroy'])->name('admin.categories.destroy');

    // Orders
    Route::get('/orders', [OrderAdminController::class, 'index'])->name('admin.orders.index');
    Route::get('/orders/{order}', [OrderAdminController::class, 'show'])->name('admin.orders.show');
    Route::patch('/orders/{order}/status', [OrderAdminController::class, 'updateStatus'])->name('admin.orders.updateStatus');

    // Users (admin only — extra check inside controller)
    Route::get('/users', [UserAdminController::class, 'index'])->name('admin.users.index');
});
