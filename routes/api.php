<?php

use App\Http\Controllers\Api\JwtAuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API & JWT Authentication Routes
|--------------------------------------------------------------------------
|
| These routes handle full JWT lifecycle: Registration, Login, Token Refresh,
| Protected Profile Lookup, and Secure Token Revocation/Logout.
|
*/

Route::prefix('auth')->group(function () {
    // Public Endpoints
    Route::post('/register', [JwtAuthController::class, 'register'])->name('api.auth.register');
    Route::post('/login', [JwtAuthController::class, 'login'])->name('api.auth.login');
    Route::post('/refresh', [JwtAuthController::class, 'refresh'])->name('api.auth.refresh');

    // Protected Endpoints (Guarded by 'jwt.auth' middleware)
    Route::middleware('jwt.auth')->group(function () {
        Route::get('/me', [JwtAuthController::class, 'me'])->name('api.auth.me');
        Route::post('/logout', [JwtAuthController::class, 'logout'])->name('api.auth.logout');
    });
});

