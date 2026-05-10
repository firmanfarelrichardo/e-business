<?php

namespace App\Services;

use App\Repositories\ProductRepository;
use Illuminate\Support\Facades\Storage;
use App\Models\Brand;
use App\Models\ProductBrand;

/**
 * ProductService
 *
 * Central business logic layer for all product operations.
 * This service orchestrates file handling (upload/removal of product
 * attachments) and delegates pure data persistence to the repository.
 *
 * Both the API controllers and the dashboard web controllers consume
 * this single service, ensuring consistent behavior regardless of
 * the entry point.
 */
class ProductService
{
    protected ProductRepository $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * Retrieve all products with basic category relation.
     *
     * Suitable for API responses where inventory data is fetched
     * separately or not needed at all.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllProducts()
    {
        return $this->productRepository->getAllWithRelations();
    }

    /**
     * Retrieve all products with full inventory hierarchy.
     *
     * Returns products eager-loaded with category, brand variants,
     * and their respective batch stock data. Designed for the dashboard
     * products table where stock counts and WAC are displayed inline.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllProductsWithInventory()
    {
        return $this->productRepository->getAllWithBrandsAndBatches();
    }

    /**
     * Retrieve a single product by UUID.
     *
     * @param  string $id  UUID of the product
     * @return \App\Models\Product
     */
    public function getProductById($id)
    {
        return $this->productRepository->findById($id);
    }

    /**
     * Retrieve a single product with full inventory relations.
     *
     * @param  string $id  UUID of the product
     * @return \App\Models\Product
     */
    public function getProductByIdWithRelations($id)
    {
        return $this->productRepository->findByIdWithRelations($id);
    }

    /**
     * Create a new product with optional attachment uploads.
     *
     * Processes uploaded image files first, storing them in the
     * public disk under the `products/` directory. The resulting
     * storage paths are saved as a JSON array in the `attachments`
     * column, enabling the frontend to render them via Storage::url().
     *
     * @param  array $data  Validated request data (may include 'attachments' file array)
     * @return \App\Models\Product
     */
    public function createProduct(array $data)
    {
        $paths = [];

        if (isset($data['attachments']) && is_array($data['attachments'])) {
            $paths = $this->uploadAttachments($data['attachments']);
        }

        $product = $this->productRepository->create([
            'name' => $data['name'],
            'category_id' => $data['category_id'],
            'description' => $data['description'] ?? null,
            'attachments' => !empty($paths) ? $paths : null,
        ]);

        $this->syncProductBrand($product->id, $data);

        return $product;
    }

    /**
     * Update an existing product with attachment management.
     *
     * Supports three attachment operations in a single request:
     * 1. Keep existing attachments that are not flagged for removal
     * 2. Remove specific attachments (deletes files from storage)
     * 3. Add new uploaded attachments to the collection
     *
     * This approach prevents data loss when only adding/removing
     * individual images rather than replacing the entire set.
     *
     * @param  string $id    UUID of the product
     * @param  array  $data  Validated request data
     * @return \App\Models\Product
     */
    public function updateProduct($id, array $data)
    {
        $product = $this->getProductById($id);

        // Start with the current attachment list
        $existing = $product->attachments ?? [];

        // Remove attachments flagged by the user
        if (!empty($data['remove_attachments'])) {
            foreach ($data['remove_attachments'] as $path) {
                Storage::disk('public')->delete($path);
            }
            $existing = array_values(array_diff($existing, $data['remove_attachments']));
        }

        // Append newly uploaded files
        if (isset($data['attachments']) && is_array($data['attachments'])) {
            $newPaths = $this->uploadAttachments($data['attachments']);
            $existing = array_merge($existing, $newPaths);
        }

        $updatedProduct = $this->productRepository->update($id, [
            'name' => $data['name'],
            'category_id' => $data['category_id'],
            'description' => $data['description'] ?? null,
            'attachments' => !empty($existing) ? array_values($existing) : null,
        ]);

        $this->syncProductBrand($id, $data);

        return $updatedProduct;
    }

    /**
     * Soft-delete a product and clean up its stored attachments.
     *
     * Files are removed from disk before the soft-delete because
     * orphaned files in storage waste space and cannot be traced
     * back to a deleted record. The database record itself is
     * retained (soft-deleted) for order history integrity.
     *
     * @param  string $id  UUID of the product
     * @return bool
     */
    public function deleteProduct($id)
    {
        $product = $this->getProductById($id);

        // Clean up stored image files
        if (!empty($product->attachments) && is_array($product->attachments)) {
            foreach ($product->attachments as $path) {
                Storage::disk('public')->delete($path);
            }
        }

        return $this->productRepository->delete($id);
    }

    /**
     * Store multiple image files to the public disk.
     *
     * Each file is stored under `products/` with a unique hash-based
     * filename generated by Laravel's Storage facade. Returns an array
     * of relative paths suitable for JSON serialization.
     *
     * @param  array $files  Array of UploadedFile instances
     * @return array          Array of relative storage paths
     */
    protected function uploadAttachments(array $files): array
    {
        $paths = [];
        foreach ($files as $file) {
            if ($file instanceof \Illuminate\Http\UploadedFile) {
                $paths[] = $file->store('products', 'public');
            }
        }
        return $paths;
    }

    /**
     * Sycn ProductBrand relation (new brand variant addition).
     *
     * @param string $productId
     * @param array $data
     */
    protected function syncProductBrand(string $productId, array $data): void
    {
        $brandName = trim($data['brand_name'] ?? '');
        $brandId = $data['brand_id'] ?? null;

        if ($brandName !== '' || $brandId) {
            $brand = $brandName !== ''
                ? Brand::firstOrCreate(['name' => $brandName], ['description' => null])
                : Brand::findOrFail($brandId);

            $alreadyLinked = ProductBrand::where('product_id', $productId)
                ->where('brand_id', $brand->id)
                ->exists();

            if (!$alreadyLinked) {
                ProductBrand::create([
                    'unit' => $data['unit'],
                    'selling_price' => collect($data)->get('selling_price'),
                    'product_id' => $productId,
                    'brand_id' => $brand->id,
                ]);
            }
        }
    }
}
