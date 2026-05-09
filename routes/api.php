<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

// ── Public Auth Routes ────────────────────────────────────────────
Route::prefix('auth')->name('auth.')->group(function () {
    Route::post('register', [AuthController::class, 'register'])->name('register');
    Route::post('login', [AuthController::class, 'login'])->name('login');
});

// ── Protected Routes (require Sanctum token) ─────────────────────
Route::middleware('auth:sanctum')->group(function () {
    // Auth
    Route::prefix('auth')->name('auth.')->group(function () {
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('me', [AuthController::class, 'me'])->name('me');
        Route::put('profile', [AuthController::class, 'updateProfile'])->name('profile');
    });

    // Users CRUD (owner/employee only in controllers)
    Route::apiResource('users', UserController::class);
});

