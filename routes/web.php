<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StockMovementController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Welcome page
Route::get('/', function () {
    return view('welcome');
});

// Authentication routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Forgot password routes
Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('password.email');

// Reset password routes
Route::get('/reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

// Admin routes (protected by admin guard)
Route::middleware('auth:admin')->group(function () {
    // Dashboard - accessible by both admin and pegawai
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/notifications/{id}/mark-as-read', [DashboardController::class, 'markNotificationAsRead'])->name('notifications.mark-read');

    Route::get('/stock-movements', [StockMovementController::class, 'index'])->name('stock-movements.index');
    Route::get('/stock-movements/create', [StockMovementController::class, 'create'])->name('stock-movements.create');
    Route::post('/stock-movements', [StockMovementController::class, 'store'])->name('stock-movements.store');

    // Admin only routes - check role in controller
    Route::resource('categories', CategoryController::class);
    Route::post('/categories/bulk-destroy', [CategoryController::class, 'bulkDestroy'])->name('categories.bulk-destroy');

    Route::resource('products', ProductController::class);
    Route::post('/products/{product}/update-stock', [ProductController::class, 'updateStock'])->name('products.update-stock');

    Route::resource('users', UserController::class);

    Route::get('/reports', [StockMovementController::class, 'report'])->name('reports.index');

    // Add expiry alert route
    Route::get('/expiry-alert', [DashboardController::class, 'expiryAlert'])->name('expiry.alert');

    // Add route for individual product expiry notification
    Route::get('/expiry-notification/{product}', [DashboardController::class, 'showExpiryNotification'])->name('expiry.notification');

    // Add test route for Formspree
    Route::get('/test-formspree', [DashboardController::class, 'testFormspree'])->name('test.formspree');
});
