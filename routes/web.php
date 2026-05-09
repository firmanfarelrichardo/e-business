<?php

use Illuminate\Support\Facades\Route;

// ================= API =================
use App\Http\Controllers\Api\ProductCategoryController;
use App\Http\Controllers\Api\BrandController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\ProductBrandController;
use App\Http\Controllers\Api\BatchController;
use App\Http\Controllers\Api\OrderController as ApiOrderController;
use App\Http\Controllers\Api\StockReportController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ExpenseController;
use App\Http\Controllers\Api\DashboardController;

// ================= FRONTEND =================
use App\Http\Controllers\OrderController;

// ── Landing Page ─────────────────────────────────────────────────
Route::get('/', function () {
    return view('welcome');
});

// ── Auth Pages (Blade) ────────────────────────────────────────────
Route::get('/login', fn() => view('auth.auth', ['initialMode' => 'login']))->name('login');
Route::get('/register', fn() => view('auth.auth', ['initialMode' => 'register']))->name('register');

// ================= API RESOURCE =================
Route::apiResource('categories', ProductCategoryController::class);
Route::apiResource('brands', BrandController::class);
Route::apiResource('products', ProductController::class);
Route::apiResource('services', ServiceController::class);

Route::apiResource('product-brands', ProductBrandController::class);
Route::apiResource('batches', BatchController::class);

Route::apiResource('orders', ApiOrderController::class)->except(['update', 'destroy']);
Route::put('orders/{id}/status', [ApiOrderController::class, 'updateStatus']);

Route::get('reports/low-stock', [StockReportController::class, 'lowStockAlert']);
Route::get('reports/stock-recap', [StockReportController::class, 'stockRecap']);

// ================= USER =================
Route::prefix('users')->group(function () {
    Route::get('/', [UserController::class, 'index']);
    Route::post('/', [UserController::class, 'store']);
    Route::get('/{id}', [UserController::class, 'show']);
    Route::put('/{id}', [UserController::class, 'update']);
    Route::delete('/{id}', [UserController::class, 'destroy']);
    Route::patch('/{id}/toggle-active', [UserController::class, 'toggleActive']);
});

// ================= EXPENSE =================
Route::apiResource('expenses', ExpenseController::class);

// ================= DASHBOARD =================
Route::prefix('dashboard')->group(function () {
    Route::get('/', [DashboardController::class, 'index']);
    Route::get('/chart', [DashboardController::class, 'chart']);
    Route::get('/reports/orders', [DashboardController::class, 'reportOrders']);
    Route::get('/reports/top-products', [DashboardController::class, 'reportTopProducts']);
    Route::get('/reports/top-services', [DashboardController::class, 'reportTopServices']);
});

// ================= FRONTEND UI =================
Route::get('/kasir', [OrderController::class, 'create']);
Route::post('/orders', [OrderController::class, 'store']);

Route::get('/history', [OrderController::class, 'history']);
Route::get('/invoice/{id}', [OrderController::class, 'invoice']);
