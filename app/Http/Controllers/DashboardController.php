<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\Product;
use App\Models\Service;
use App\Models\Expense;
use App\Models\User;
use App\Models\ProductBrand;
use App\Models\Brand;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function __construct()
    {
        // Auth checks done per method
    }

    public function index()
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        if (Auth::user()->role === 'member') {
            return redirect('/katalog');
        }

        $income = Order::whereIn('status', ['paid', 'completed', 'done'])->sum('total_price') ?? 0;
        $expense = Expense::sum('total_amount') ?? 0;
        $profit = $income - $expense;

        $queues = Order::with('user')
            ->whereIn('status', ['pending', 'processing'])
            ->orderBy('id', 'desc')
            ->take(8)
            ->get();

        $recentActivities = Order::with('user')
            ->latest('id')
            ->take(5)
            ->get();

        return view('dashboard.index', compact('income', 'expense', 'profit', 'queues', 'recentActivities'));
    }

    public function profile()
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        /** @var \App\Models\User|null $user */
        $user = Auth::user();

        if (!$user) {
            return response()->json(['ok' => false], 401);
        }
        $history = collect();

        if ($user->role === 'member') {
            $history = Order::where('user_id', $user->id)->orderBy('created_at', 'desc')->get();
        }

        $viewNamespace = $user->role === 'member' ? 'dashboard.profile-member' : 'dashboard.profile-admin';

        return view($viewNamespace, compact('user', 'history'));
    }

    public function updatePhoto(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['ok' => false], 401);
        }

        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        $user = Auth::user();

        if ($request->file('photo')) {
            $path = $request->file('photo')->store('profiles', 'public');
            \App\Models\User::whereKey($user->id)->update(['profile' => $path]);
        }

        return redirect()->back();
    }

    // ===================== PRODUCTS =====================

    public function products()
    {
        if (!Auth::check() || Auth::user()->role === 'member') {
            return redirect('/');
        }

        $products = Product::with(['category', 'brands.batches', 'brands.brand'])->latest()->get();
        $categories = \App\Models\ProductCategory::orderBy('name')->get();
        $brands = Brand::orderBy('name')->get();

        return view('dashboard.products.index', compact('products', 'categories', 'brands'));
    }

    public function storeProduct(Request $request)
    {
        if (Auth::user()->role === 'member')
            abort(403);

        $data = $request->validate([
            'name' => 'required|string|max:50',
            'category_id' => 'required|exists:product_categories,id',
            'description' => 'nullable|string',
            'attachments' => 'nullable|array',
            'attachments.*' => 'image|mimes:jpeg,png,jpg,webp|max:4096',
            'brand_id' => 'nullable|exists:brands,id',
            'brand_name' => 'nullable|string|max:50',
            'unit' => 'nullable|string|max:100',
            'selling_price' => 'nullable|numeric|min:0',
        ]);

        $paths = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $paths[] = $file->store('products', 'public');
            }
        }

        $product = Product::create([
            'name' => $data['name'],
            'category_id' => $data['category_id'],
            'description' => $data['description'] ?? null,
            'attachments' => !empty($paths) ? $paths : null,
        ]);

        $brandName = trim($data['brand_name'] ?? '');
        $brandId = $data['brand_id'] ?? null;
        if ($brandName !== '' || $brandId) {
            if (empty($data['unit']) || (!isset($data['selling_price']) || $data['selling_price'] === '')) {
                return redirect()->back()->withErrors([
                    'unit' => 'Unit wajib diisi saat menambahkan varian brand.',
                    'selling_price' => 'Harga jual wajib diisi saat menambahkan varian brand.'
                ])->withInput();
            }

            $brand = $brandName !== ''
                ? Brand::firstOrCreate(['name' => $brandName], ['description' => null])
                : Brand::findOrFail($brandId);

            ProductBrand::create([
                'unit' => $data['unit'],
                'selling_price' => $data['selling_price'],
                'product_id' => $product->id,
                'brand_id' => $brand->id,
            ]);
        }

        return redirect('/dashboard/products')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function updateProduct(Request $request, string $id)
    {
        if (Auth::user()->role === 'member')
            abort(403);

        $product = Product::findOrFail($id);
        $data = $request->validate([
            'name' => 'required|string|max:50',
            'category_id' => 'required|exists:product_categories,id',
            'description' => 'nullable|string',
            'attachments' => 'nullable|array',
            'attachments.*' => 'image|mimes:jpeg,png,jpg,webp|max:4096',
            'remove_attachments' => 'nullable|array',
            'brand_id' => 'nullable|exists:brands,id',
            'brand_name' => 'nullable|string|max:50',
            'unit' => 'nullable|string|max:100',
            'selling_price' => 'nullable|numeric|min:0',
        ]);

        // Start with existing attachments
        $existing = $product->attachments ?? [];

        // Remove flagged ones
        if (!empty($data['remove_attachments'])) {
            foreach ($data['remove_attachments'] as $path) {
                Storage::disk('public')->delete($path);
            }
            $existing = array_values(array_diff($existing, $data['remove_attachments']));
        }

        // Add new uploads
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $existing[] = $file->store('products', 'public');
            }
        }

        $product->update([
            'name' => $data['name'],
            'category_id' => $data['category_id'],
            'description' => $data['description'] ?? null,
            'attachments' => !empty($existing) ? $existing : null,
        ]);

        // Handle optional brand addition (same logic as storeProduct)
        $brandName = trim($data['brand_name'] ?? '');
        $brandId = $data['brand_id'] ?? null;
        if ($brandName !== '' || $brandId) {
            if (empty($data['unit']) || (!isset($data['selling_price']) || $data['selling_price'] === '')) {
                return redirect()->back()->withErrors([
                    'unit' => 'Unit wajib diisi saat menambahkan varian brand.',
                    'selling_price' => 'Harga jual wajib diisi saat menambahkan varian brand.',
                ])->withInput();
            }

            $brand = $brandName !== ''
                ? Brand::firstOrCreate(['name' => $brandName], ['description' => null])
                : Brand::findOrFail($brandId);

            // Only add if this brand isn't already linked to this product
            $alreadyLinked = ProductBrand::where('product_id', $product->id)
                ->where('brand_id', $brand->id)
                ->exists();

            if (!$alreadyLinked) {
                ProductBrand::create([
                    'unit' => $data['unit'],
                    'selling_price' => $data['selling_price'],
                    'product_id' => $product->id,
                    'brand_id' => $brand->id,
                ]);
            }
        }

        return redirect('/dashboard/products')->with('success', 'Produk berhasil diupdate.');
    }

    public function deleteProduct(string $id)
    {
        if (Auth::user()->role === 'member')
            abort(403);

        $product = Product::findOrFail($id);

        // Delete stored images
        if (!empty($product->attachments)) {
            foreach ($product->attachments as $path) {
                Storage::disk('public')->delete($path);
            }
        }

        $product->delete();
        return redirect('/dashboard/products')->with('success', 'Produk berhasil dihapus.');
    }

    // ===================== PRODUCT BRAND PRICE =====================

    public function updateProductBrandPrice(Request $request, string $id)
    {
        if (!in_array(Auth::user()->role, ['owner', 'employee']))
            abort(403);

        $data = $request->validate([
            'selling_price' => 'required|numeric|min:0',
        ]);

        $pb = ProductBrand::findOrFail($id);
        $pb->update(['selling_price' => $data['selling_price']]);

        return redirect()->back()->with('success', 'Harga jual berhasil diperbarui.');
    }

    // ===================== SERVICES =====================

    public function services()
    {
        if (!Auth::check() || Auth::user()->role === 'member') {
            return redirect('/');
        }

        $services = Service::latest()->get();
        return view('dashboard.services.index', compact('services'));
    }

    public function storeService(Request $request)
    {
        if (Auth::user()->role === 'member')
            abort(403);

        $data = $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'piece_price' => 'required|numeric|min:0',
            'attachments' => 'nullable|array',
            'attachments.*' => 'image|mimes:jpeg,png,jpg,webp|max:4096',
        ]);

        $paths = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $paths[] = $file->store('services', 'public');
            }
        }

        Service::create([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'piece_price' => $data['piece_price'],
            'created_by' => Auth::id(),
            'attachments' => !empty($paths) ? $paths : null,
        ]);

        return redirect('/dashboard/services')->with('success', 'Jasa berhasil ditambahkan.');
    }

    public function updateService(Request $request, string $id)
    {
        if (Auth::user()->role === 'member')
            abort(403);

        $service = Service::findOrFail($id);
        $data = $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'piece_price' => 'required|numeric|min:0',
            'attachments' => 'nullable|array',
            'attachments.*' => 'image|mimes:jpeg,png,jpg,webp|max:4096',
            'remove_attachments' => 'nullable|array',
        ]);

        $existing = $service->attachments ?? [];

        if (!empty($data['remove_attachments'])) {
            foreach ($data['remove_attachments'] as $path) {
                Storage::disk('public')->delete($path);
            }
            $existing = array_values(array_diff($existing, $data['remove_attachments']));
        }

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $existing[] = $file->store('services', 'public');
            }
        }

        $service->update([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'piece_price' => $data['piece_price'],
            'attachments' => !empty($existing) ? $existing : null,
        ]);

        return redirect('/dashboard/services')->with('success', 'Jasa berhasil diupdate.');
    }

    public function deleteService(string $id)
    {
        if (Auth::user()->role === 'member')
            abort(403);

        $service = Service::findOrFail($id);

        if (!empty($service->attachments)) {
            foreach ($service->attachments as $path) {
                Storage::disk('public')->delete($path);
            }
        }

        $service->delete();
        return redirect('/dashboard/services')->with('success', 'Jasa berhasil dihapus.');
    }

    // ===================== USERS (Owner only) =====================

    public function users()
    {
        if (!Auth::check() || Auth::user()->role !== 'owner') {
            abort(403, 'Akses ditolak. Fitur ini khusus Owner.');
        }

        $users = \App\Models\User::orderBy('created_at', 'desc')->get();
        return view('dashboard.users', compact('users'));
    }

    public function storeUser(Request $request)
    {
        if (Auth::user()->role !== 'owner')
            abort(403);

        $data = $request->validate([
            'name' => 'required|string|max:50',
            'username' => 'required|string|max:50|unique:users,username',
            'email' => 'required|email|max:50|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'required|in:member,employee,owner',
            'address' => 'nullable|string|max:50',
        ]);

        \App\Models\User::create([
            ...$data,
            'password' => bcrypt($data['password']),
            'created_by' => Auth::id(),
        ]);

        return redirect('/dashboard/users')->with('success', 'User berhasil ditambahkan.');
    }

    public function updateUser(Request $request, string $id)
    {
        if (Auth::user()->role !== 'owner')
            abort(403);

        $user = \App\Models\User::findOrFail($id);
        $data = $request->validate([
            'name' => 'required|string|max:50',
            'email' => 'required|email|max:50|unique:users,email,' . $id,
            'role' => 'required|in:member,employee,owner',
            'address' => 'nullable|string|max:50',
        ]);

        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        $user->update($data);
        return redirect('/dashboard/users')->with('success', 'User berhasil diupdate.');
    }

    public function deleteUser(string $id)
    {
        if (Auth::user()->role !== 'owner')
            abort(403);
        \App\Models\User::findOrFail($id)->delete();
        return redirect('/dashboard/users')->with('success', 'User berhasil dihapus.');
    }

    // ===================== EXPENSES =====================

    public function expenses()
    {
        if (!Auth::check() || Auth::user()->role === 'member') {
            abort(403, 'Akses ditolak.');
        }

        $expenses = Expense::with(['items.productBrand.product', 'creator'])
            ->latest('created_at')->paginate(15);
        $productBrands = ProductBrand::with(['product', 'brand'])->get();

        return view('dashboard.expenses.index', compact('expenses', 'productBrands'));
    }

    public function storeExpense(Request $request)
    {
        // Only owners may record new expenses
        if (Auth::user()->role !== 'owner')
            abort(403);

        $data = $request->validate([
            'note' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_brand_id' => 'required|exists:product_brands,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.purchase_price' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($data) {
            $total = collect($data['items'])->sum(fn($i) => $i['quantity'] * $i['purchase_price']);

            $expense = Expense::create([
                'total_amount' => $total,
                'note' => $data['note'] ?? null,
                'created_by' => Auth::id(),
                'created_at' => now(),
            ]);

            $affectedBrands = [];
            foreach ($data['items'] as $item) {
                \App\Models\ExpenseItem::create([
                    'expense_id' => $expense->id,
                    'product_brand_id' => $item['product_brand_id'],
                    'quantity' => $item['quantity'],
                    'purchase_price' => $item['purchase_price'],
                    'subtotal' => $item['quantity'] * $item['purchase_price'],
                    'created_at' => now(),
                ]);

                // Auto-generate batch code: BCH-YYYYMMDD-XXXXX
                $batchCode = 'BCH-' . now()->format('Ymd') . '-' . strtoupper(substr(uniqid(), -5));

                \App\Models\Batch::create([
                    'batch_code' => $batchCode,
                    'product_brand_id' => $item['product_brand_id'],
                    'initial_stock' => $item['quantity'],
                    'current_stock' => $item['quantity'],
                    'purchase_price' => $item['purchase_price'],
                    'is_active' => true,
                    'created_by' => Auth::id(),
                    'created_at' => now(),
                ]);

                $affectedBrands[] = $item['product_brand_id'];
            }

            // Recalculate WAC for affected ProductBrands (excludes current_stock=0 batches)
            foreach (array_unique($affectedBrands) as $pbId) {
                ProductBrand::find($pbId)?->recalculateWAC();
            }
        });

        return redirect('/dashboard/expenses')->with('success', 'Pengeluaran berhasil dicatat.');
    }

    // ===================== ORDER QUEUE MANAGEMENT =====================

    public function queues()
    {
        if (!Auth::check() || Auth::user()->role === 'member') {
            abort(403, 'Akses ditolak.');
        }

        $statusFilter = request('status', 'active');

        $query = Order::with([
            'user',
            'employee',
            'items.productBrand.product',
            'items.service',
        ])->orderBy('created_at', 'asc');

        if ($statusFilter === 'pending') {
            $query->where('status', 'pending');
        } elseif ($statusFilter === 'processing') {
            $query->where('status', 'processing');
        } else {
            $query->whereIn('status', ['pending', 'processing']);
        }

        $orders = $query->paginate(20)->withQueryString();
        $pendingCount = Order::where('status', 'pending')->count();

        return view('dashboard.queues.index', compact('orders', 'pendingCount', 'statusFilter'));
    }

    public function updateOrderStatus(Request $request, string $id)
    {
        if (!Auth::check() || Auth::user()->role === 'member') {
            abort(403);
        }

        $data = $request->validate([
            'status' => 'required|in:processing,completed,cancelled',
        ]);

        $order = Order::findOrFail($id);
        $update = ['status' => $data['status']];

        if ($data['status'] === 'completed') {
            $update['completed_at'] = now();
        }

        // Assign current staff as handler if not already set
        if (is_null($order->employee_id)) {
            $update['employee_id'] = Auth::id();
        }

        $order->update($update);

        return redirect()->back()->with('success', 'Status order berhasil diperbarui.');
    }

    // ===================== REPORTS =====================

    public function chart()
    {
        return response()->json([
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            'income' => [500000, 750000, 600000, 1200000, 1000000, 1400000],
            'expense' => [300000, 200000, 450000, 300000, 500000, 600000],
        ]);
    }

    public function reportOrders()
    {
        return response()->json(['message' => 'Not yet implemented']);
    }

    public function reportTopProducts()
    {
        return response()->json(['message' => 'Not yet implemented']);
    }

    public function reportTopServices()
    {
        return response()->json(['message' => 'Not yet implemented']);
    }
}
