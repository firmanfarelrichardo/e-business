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
use App\Http\Controllers\Front\CatalogController;
use App\Http\Controllers\Front\CartController;
use App\Http\Controllers\DashboardController;

// ================= WEB CONTROLLERS =================
use App\Http\Controllers\Web\ProductController as WebProductController;
use App\Http\Controllers\Web\OrderController as WebOrderController;
use App\Http\Controllers\Web\BatchController as WebBatchController;
use App\Http\Controllers\Web\StockCardController;

// ================= FRONTEND =================
use App\Http\Controllers\OrderController;

// -- Landing Page ---------------------------------------------------------
Route::get('/', function () {
    return view('welcome');
});

// -- Auth Pages (Blade) ---------------------------------------------------
Route::get('/login', fn() => view('auth.auth', ['initialMode' => 'login']))->name('login');
Route::get('/register', fn() => view('auth.auth', ['initialMode' => 'register']))->name('register');

Route::post('/web-auth/login', function (Illuminate\Http\Request $request) {
    if (Illuminate\Support\Facades\Auth::attempt($request->only('username', 'password'))) {
        $request->session()->regenerate();
        return response()->json(['ok' => true, 'data' => ['message' => 'Login berhasil!']]);
    }
    return response()->json(['ok' => false, 'message' => 'Kredensial tidak valid'], 401);
});

Route::post('/web-auth/register', function (Illuminate\Http\Request $request) {
    $request->validate([
        'name' => 'required',
        'username' => 'required|unique:users',
        'email' => 'required|email|unique:users',
        'password' => 'required|min:8|confirmed',
    ]);
    $user = \App\Models\User::create([
        'name' => $request->name,
        'username' => $request->username,
        'email' => $request->email,
        'password' => \Illuminate\Support\Facades\Hash::make($request->password),
        'address' => $request->address,
        'role' => 'owner', // Defaulting to owner so they can test RBAC as per request
        'is_active' => true,
    ]);
    Illuminate\Support\Facades\Auth::login($user);
    $request->session()->regenerate();
    return response()->json(['ok' => true, 'data' => ['message' => 'Registrasi berhasil!']]);
});

Route::post('/web-auth/logout', function (Illuminate\Http\Request $request) {
    Illuminate\Support\Facades\Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/login');
})->name('logout');

// -- Catalog UI Pages -----------------------------------------------------
Route::get('/katalog', [CatalogController::class, 'index']);
Route::get('/jasa', [CatalogController::class, 'jasa']);
Route::middleware(['auth'])->group(function () {
    Route::get('/keranjang', [CatalogController::class, 'keranjang'])->name('keranjang');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
    Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
});

Route::get('/not-configured', function () {
    return view('errors.not-configured');
});

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

// ================= PROFILE & DASHBOARD =================
Route::get('/profile', [DashboardController::class, 'profile'])->name('profile');

Route::prefix('dashboard')->group(function () {
    Route::get('/', [DashboardController::class, 'index']);
    Route::get('/profile', [DashboardController::class, 'profile'])->name('profile');
    Route::post('/profile/upload-photo', [DashboardController::class, 'updatePhoto'])->name('profile.uploadPhoto');

    // User Management (Owner only)
    Route::get('/users', [DashboardController::class, 'users']);
    Route::post('/users', [DashboardController::class, 'storeUser'])->name('dashboard.users.store');
    Route::put('/users/{id}', [DashboardController::class, 'updateUser'])->name('dashboard.users.update');
    Route::delete('/users/{id}', [DashboardController::class, 'deleteUser'])->name('dashboard.users.destroy');

    // Product Management (delegated to Web\ProductController)
    Route::get('/products', [WebProductController::class, 'index']);
    Route::post('/products', [WebProductController::class, 'store'])->name('dashboard.products.store');
    Route::put('/products/{id}', [WebProductController::class, 'update'])->name('dashboard.products.update');
    Route::delete('/products/{id}', [WebProductController::class, 'destroy'])->name('dashboard.products.destroy');

    // Service Management
    Route::get('/services', [DashboardController::class, 'services']);
    Route::post('/services', [DashboardController::class, 'storeService'])->name('dashboard.services.store');
    Route::put('/services/{id}', [DashboardController::class, 'updateService'])->name('dashboard.services.update');
    Route::delete('/services/{id}', [DashboardController::class, 'deleteService'])->name('dashboard.services.destroy');

    // Expense Management (Owner records, Employee reads)
    Route::get('/expenses', [DashboardController::class, 'expenses'])->name('dashboard.expenses');
    Route::post('/expenses', [DashboardController::class, 'storeExpense'])->name('dashboard.expenses.store');

    // Selling Price Control (Owner + Employee)
    Route::put('/product-brands/{id}/price', [DashboardController::class, 'updateProductBrandPrice'])->name('dashboard.productbrand.price');

    // Order Queue Management (delegated to Web\OrderController)
    Route::get('/queues', [WebOrderController::class, 'index'])->name('dashboard.queues');
    Route::post('/queues/{id}/status', [WebOrderController::class, 'updateStatus'])->name('dashboard.queues.status');

    // Batch Stock Management (delegated to Web\BatchController)
    Route::get('/batches', [WebBatchController::class, 'index'])->name('dashboard.batches');
    Route::post('/batches', [WebBatchController::class, 'store'])->name('dashboard.batches.store');
    Route::get('/batches/{id}/edit', [WebBatchController::class, 'edit'])->name('dashboard.batches.edit');
    Route::put('/batches/{id}', [WebBatchController::class, 'update'])->name('dashboard.batches.update');
    Route::post('/batches/{id}/toggle', [WebBatchController::class, 'toggleActive'])->name('dashboard.batches.toggle');
    Route::delete('/batches/{id}', [WebBatchController::class, 'destroy'])->name('dashboard.batches.destroy');

    // Stock Card Report (delegated to Web\StockCardController)
    Route::get('/reports/stock-card', [StockCardController::class, 'index'])->name('dashboard.reports.stock-card');

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
