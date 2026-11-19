<?php

use App\Http\Controllers\Admin\AdminManagementController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\CustomerController as AdminCustomerController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\InventoryController as AdminInventoryController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Controllers\Admin\RolePermissionController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CustomerAccountController;
use App\Http\Controllers\CustomerAuthController;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderTrackingController;
use App\Http\Controllers\OtpController;
use App\Http\Controllers\ShopController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Customer & Storefront Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::get('/category/{slug}', [ShopController::class, 'category'])->name('shop.category');
Route::get('/product/{slug}', [ShopController::class, 'show'])->name('shop.show');

Route::view('/about', 'pages.about')->name('about');
Route::view('/contact', 'pages.contact')->name('contact');

// Cart Routes
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove/{variantId}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
Route::get('/cart/mini', [CartController::class, 'miniCart'])->name('cart.mini');

// Checkout Routes
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');
Route::get('/order-confirmed/{orderNumber}', [CheckoutController::class, 'confirmation'])->name('checkout.confirmation');
Route::get('/orders/{orderNumber}/invoice', [CheckoutController::class, 'invoice'])->name('order.invoice');

// Order Tracking
Route::get('/track-order', [OrderTrackingController::class, 'index'])->name('orders.track.index');
Route::post('/track-order', [OrderTrackingController::class, 'track'])->name('orders.track.post');

// Customer Auth Routes
Route::get('/login', [CustomerAuthController::class, 'showLogin'])->name('login');
Route::post('/login', [CustomerAuthController::class, 'login']);
Route::get('/register', [CustomerAuthController::class, 'showRegister'])->name('register');
Route::post('/register', [CustomerAuthController::class, 'register']);
Route::post('/logout', [CustomerAuthController::class, 'logout'])->name('logout');

// Forgot / Reset Password Routes
Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgotForm'])->name('password.request');
Route::post('/forgot-password/otp-reset', [ForgotPasswordController::class, 'resetWithOtp'])->name('password.otp.reset');
Route::post('/forgot-password/email', [ForgotPasswordController::class, 'sendResetEmail'])->name('password.email');
Route::get('/reset-password/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [ForgotPasswordController::class, 'resetWithEmail'])->name('password.update');

// Google OAuth & Firebase
Route::get('/auth/google', [GoogleAuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');
Route::post('/auth/google/firebase', [GoogleAuthController::class, 'handleFirebaseLogin'])->name('auth.google.firebase');
Route::get('/auth/google/mock', [GoogleAuthController::class, 'mockConsent'])->name('auth.google.mock');
Route::post('/auth/google/mock/process', [GoogleAuthController::class, 'processMock'])->name('auth.google.mock.process');

// OTP Routes (AJAX)
Route::post('/otp/send', [OtpController::class, 'send'])->name('otp.send');
Route::post('/otp/verify', [OtpController::class, 'verify'])->name('otp.verify');

// Customer Dashboard & Account Pages
Route::middleware('auth')->group(function () {
    Route::get('/account', [CustomerAccountController::class, 'dashboard'])->name('customer.dashboard');
    Route::get('/account/profile', [CustomerAccountController::class, 'profile'])->name('customer.profile');
    Route::put('/account/profile', [CustomerAccountController::class, 'updateProfile'])->name('customer.profile.update');
    Route::get('/account/orders', [CustomerAccountController::class, 'orders'])->name('customer.orders');
    Route::get('/account/orders/{orderNumber}', [CustomerAccountController::class, 'orderDetail'])->name('customer.orders.show');
    Route::get('/account/orders/{orderNumber}/invoice', [CustomerAccountController::class, 'invoice'])->name('customer.orders.invoice');
});

/*
|--------------------------------------------------------------------------
| Admin Portal Routes
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->group(function () {
    // Admin Guest Auth
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.submit');

    // Admin Protected Area
    Route::middleware('admin')->group(function () {
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

        // Categories Management (Products permission)
        Route::resource('categories', AdminCategoryController::class)->middleware('admin.permission:products');

        // Products Management (Products permission)
        Route::resource('products', AdminProductController::class)->middleware('admin.permission:products');

        // Fast Inventory Control (Inventory permission)
        Route::get('/inventory', [AdminInventoryController::class, 'index'])->name('inventory.index')->middleware('admin.permission:inventory');
        Route::put('/inventory/{variant}', [AdminInventoryController::class, 'update'])->name('inventory.update')->middleware('admin.permission:inventory');

        // Order Management & Fulfillment (Orders permission)
        Route::middleware('admin.permission:orders')->group(function () {
            Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
            Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
            Route::put('/orders/{order}', [AdminOrderController::class, 'update'])->name('orders.update');
            Route::get('/orders/{order}/invoice', [AdminOrderController::class, 'invoice'])->name('orders.invoice');
        });

        // Customers (Customers permission)
        Route::middleware('admin.permission:customers')->group(function () {
            Route::get('/customers', [AdminCustomerController::class, 'index'])->name('customers.index');
            Route::get('/customers/{customer}', [AdminCustomerController::class, 'show'])->name('customers.show');
            Route::post('/customers/{customer}/toggle', [AdminCustomerController::class, 'toggleStatus'])->name('customers.toggle');
        });

        // Reports & Analytics (Reports permission)
        Route::get('/reports', [AdminReportController::class, 'index'])->name('reports.index')->middleware('admin.permission:reports');

        // Super Admin Exclusive Controls
        Route::middleware('super_admin')->group(function () {
            // Admin Staff Management
            Route::resource('admins', AdminManagementController::class)->except(['show']);
            Route::post('/admins/{admin}/toggle', [AdminManagementController::class, 'toggleStatus'])->name('admins.toggle');

            // Roles & Permissions Matrix
            Route::get('/roles', [RolePermissionController::class, 'index'])->name('roles.index');
            Route::put('/roles', [RolePermissionController::class, 'update'])->name('roles.update');

            // Website Settings
            Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
            Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');
            Route::get('/settings/coupon/{code}/remove', [SettingController::class, 'removeCoupon'])->name('settings.coupon.remove');
        });
    });
});
