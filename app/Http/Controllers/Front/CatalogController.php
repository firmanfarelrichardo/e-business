<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ProductCategoryService;
use App\Services\ProductBrandService;
use App\Services\ServiceService;

class CatalogController extends Controller
{
    protected $categoryService;
    protected $productBrandService;
    protected $serviceService;

    public function __construct(
        ProductCategoryService $categoryService,
        ProductBrandService $productBrandService,
        ServiceService $serviceService
    ) {
        $this->categoryService = $categoryService;
        $this->productBrandService = $productBrandService;
        $this->serviceService = $serviceService;
    }
    public function index(Request $request)
    {
        // 1. Ambil kategori dari database
        $dbCategories = $this->categoryService->getAllCategories();
        $categories = [];
        foreach ($dbCategories as $cat) {
            $categories[] = [
                'name' => $cat->name,
                'url' => url('/katalog?category=' . $cat->id)
            ];
        }

        if (empty($categories)) {
            // Fallback kosong jika DB belum di-seed
            $categories = [['name' => 'Belum ada kategori', 'url' => '#']];
        }

        // 2. Ambil subcategories (Merek) secara dinamis sesuai kategori produk yang ada
        $brandsQuery = \App\Models\Brand::whereHas('productBrands.product', function ($q) use ($request) {
            if ($request->has('category') && $request->category != '') {
                $q->where('category_id', $request->category);
            }
        });

        $dbBrands = $brandsQuery->get();
        $subcategories = [];
        foreach ($dbBrands as $brand) {
            $subcategories[] = [
                'id' => $brand->id,
                'name' => $brand->name,
                'url' => request()->fullUrlWithQuery(['brand' => $brand->id])
            ];
        }

        if (empty($subcategories)) {
            $subcategories = [];
        }

        $productsQuery = \App\Models\ProductBrand::with(['product', 'brand'])
            ->whereHas('product', function ($q) use ($request) {
                if ($request->has('category') && $request->category != '') {
                    $q->where('category_id', $request->category);
                }
            });

        if ($request->has('brand') && $request->brand != '') {
            $productsQuery->where('brand_id', $request->brand);
        }

        $dbProductBrands = $productsQuery->paginate(10)->withQueryString();

        $products = [];

        foreach ($dbProductBrands as $pb) {
            $products[] = [
                'id' => $pb->id, // Add ID for cart operations
                'name' => $pb->product->name ?? 'Unknown Product',
                'brand' => $pb->brand->name ?? 'No Brand',
                'price' => $pb->selling_price,
                'original_price' => null, // Implementasi diskon bisa ditambahkan nanti
                'badge' => $pb->current_stock <= 0 ? 'Sold Out' : null,
                'image' => (isset($pb->product->attachments) && count($pb->product->attachments) > 0)
                    ? asset('storage/' . $pb->product->attachments[0])
                    : 'https://images.unsplash.com/photo-1585336261022-680e295ce3fe?q=80&w=300&auto=format&fit=crop', // Fallback placeholder
            ];
        }

        if (empty($products)) {
            // Jika DB kosong, kita biarkan kosong agar logic berjalan baik, 
            // tapi UI akan menampilkan tidak ada produk.
        }

        return view('katalog.index', compact('categories', 'subcategories', 'products', 'dbProductBrands'));
    }

    public function jasa(Request $request)
    {
        $dbServices = \App\Models\Service::paginate(10)->withQueryString();
        // Fallback for categories if empty
        $categories = [];
        if (empty($categories)) {
            $categories = [['name' => 'Kategori Jasa (Semua)', 'url' => '#']];
        }

        return view('jasa.index', compact('categories', 'dbServices'));
    }

    public function keranjang()
    {
        $cart = session()->get('cart', []);
        $totalProduk = 0;
        $totalJasa = 0;

        foreach ($cart as $item) {
            if ($item['type'] === 'product') {
                $totalProduk += $item['price'] * $item['quantity'];
            } else {
                $totalJasa += $item['price'] * $item['quantity'];
            }
        }

        $grandTotal = $totalProduk + $totalJasa;

        return view('keranjang.index', compact('cart', 'totalProduk', 'totalJasa', 'grandTotal'));
    }
}
