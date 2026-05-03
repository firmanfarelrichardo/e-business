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
    public function index()
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

        // 2. Ambil subcategories (Saat ini Product model tidak punya relasi subcategory, jadi kita ambil unique Product names atau Brands sebagai representasi filter di UI)
        $subcategories = \App\Models\Brand::pluck('name')->toArray();
        if (empty($subcategories)) {
            $subcategories = ['Belum ada Merek'];
        }

        // 3. Ambil produk dari ProductBrand (Kombinasi Produk & Merek + Harga)
        $dbProductBrands = $this->productBrandService->getAllProductBrands();
        $products = [];
        
        foreach ($dbProductBrands as $pb) {
            $products[] = [
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

        return view('katalog.index', compact('categories', 'subcategories', 'products'));
    }

    public function jasa()
    {
        $dbServices = $this->serviceService->getAllServices();
        $categories = [];
        
        // Asumsikan kita membuat unique categories dari nama Service
        foreach ($dbServices as $svc) {
            $categories[] = [
                'name' => $svc->name,
                'url' => url('/jasa?service=' . $svc->id)
            ];
        }

        if (empty($categories)) {
            $categories = [['name' => 'Belum ada Jasa Terdaftar', 'url' => '#']];
        }

        return view('jasa.index', compact('categories'));
    }

    public function keranjang()
    {
        return view('keranjang.index');
    }
}
