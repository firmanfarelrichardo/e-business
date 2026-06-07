<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Services\ProductService;
use App\Models\ProductCategory;
use App\Models\Brand;

/**
 * Web\ProductController
 *
 * Thin controller for the dashboard product management pages.
 * Each method's sole responsibility is to receive a validated request,
 * delegate to the ProductService, and return the appropriate response.
 *
 * Authorization is handled by the FormRequest classes, not here.
 * Business logic (file handling, data transformation) lives in ProductService.
 */
class ProductController extends Controller
{
    protected ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * Display the product management dashboard page.
     *
     * Fetches all products with their full inventory hierarchy
     * and the category list for the create/edit form dropdowns.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $products = $this->productService->getAllProductsWithInventory();
        $categories = ProductCategory::orderBy('name')->get();
        $brands = Brand::orderBy('name')->get();

        return view('dashboard.products.index', compact('products', 'categories', 'brands'));
    }

    /**
     * Store a newly created product.
     *
     * The StoreProductRequest handles both validation and authorization.
     * The service processes file uploads and persists the record.
     *
     * @param  \App\Http\Requests\StoreProductRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreProductRequest $request)
    {
        $this->productService->createProduct($request->validated());

        return redirect('/dashboard/products')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    /**
     * Update the specified product.
     *
     * The UpdateProductRequest validates all fields including the
     * remove_attachments array for selective image deletion.
     *
     * @param  \App\Http\Requests\UpdateProductRequest $request
     * @param  string $id  UUID of the product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateProductRequest $request, string $id)
    {
        $this->productService->updateProduct($id, $request->validated());

        return redirect('/dashboard/products')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    /**
     * Soft-delete the specified product.
     *
     * Files are cleaned up by the service layer before the record
     * is soft-deleted to prevent orphaned storage files.
     *
     * @param  string $id  UUID of the product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(string $id)
    {
        $this->productService->deleteProduct($id);

        return redirect('/dashboard/products')
            ->with('success', 'Produk berhasil dihapus.');
    }
}
