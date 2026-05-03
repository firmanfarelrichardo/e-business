<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductCategoryController;
use App\Http\Controllers\Api\BrandController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\ProductBrandController;
use App\Http\Controllers\Api\BatchController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\StockReportController;
use App\Http\Controllers\Front\CatalogController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/katalog', [CatalogController::class, 'index']);
Route::get('/jasa', [CatalogController::class, 'jasa']);
Route::get('/keranjang', [CatalogController::class, 'keranjang']);

Route::get('/not-configured', function () {
    return view('errors.not-configured');
});

Route::apiResource('categories', ProductCategoryController::class);
Route::apiResource('brands', BrandController::class);
Route::apiResource('products', ProductController::class);
Route::apiResource('services', ServiceController::class);

Route::apiResource('product-brands', ProductBrandController::class);
Route::apiResource('batches', BatchController::class);

Route::apiResource('orders', OrderController::class)->except(['update', 'destroy']);
Route::put('orders/{id}/status', [OrderController::class, 'updateStatus']);

Route::get('reports/low-stock', [StockReportController::class, 'lowStockAlert']);
Route::get('reports/stock-recap', [StockReportController::class, 'stockRecap']);
