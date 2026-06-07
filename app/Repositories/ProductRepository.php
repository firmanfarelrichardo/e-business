<?php

namespace App\Repositories;

use App\Models\Product;

/**
 * ProductRepository
 *
 * Handles all direct database interactions for the Product model.
 * No business logic resides here; this layer exists purely to abstract
 * Eloquent queries so they can be swapped or mocked independently of
 * the service layer.
 */
class ProductRepository
{
    /**
     * Retrieve all products with their category relation.
     *
     * Used primarily by the API layer where inventory detail
     * (brands, batches) is not required in the listing response.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllWithRelations()
    {
        return Product::with('category')->get();
    }

    /**
     * Retrieve all products with full inventory hierarchy.
     *
     * Eager-loads category, brands (variants), each brand's batches,
     * and the brand master data. This prevents N+1 queries on the
     * dashboard products table where stock and WAC are calculated
     * per-variant row.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllWithBrandsAndBatches()
    {
        return Product::with(['category', 'brands.batches', 'brands.brand'])
            ->latest()
            ->get();
    }

    /**
     * Find a single product by ID with basic relations.
     *
     * Throws ModelNotFoundException if the UUID does not match any
     * existing (non-soft-deleted) record, which Laravel automatically
     * converts to a 404 response.
     *
     * @param  string $id  UUID of the product
     * @return \App\Models\Product
     */
    public function findById($id)
    {
        return Product::with('category')->findOrFail($id);
    }

    /**
     * Find a single product by ID with full inventory relations.
     *
     * Used for edit forms and detail views that need variant/stock
     * information alongside the base product data.
     *
     * @param  string $id  UUID of the product
     * @return \App\Models\Product
     */
    public function findByIdWithRelations($id)
    {
        return Product::with(['category', 'brands.batches', 'brands.brand'])
            ->findOrFail($id);
    }

    /**
     * Persist a new product record.
     *
     * Expects an already-prepared data array (attachments already
     * processed into storage paths by the service layer).
     *
     * @param  array $data  Mass-assignable product attributes
     * @return \App\Models\Product
     */
    public function create(array $data)
    {
        return Product::create($data);
    }

    /**
     * Update an existing product record.
     *
     * Retrieves the model first to ensure it exists, then applies
     * the update. Returns the refreshed model instance.
     *
     * @param  string $id    UUID of the product
     * @param  array  $data  Attributes to update
     * @return \App\Models\Product
     */
    public function update($id, array $data)
    {
        $product = $this->findById($id);
        $product->update($data);
        return $product;
    }

    /**
     * Soft-delete a product.
     *
     * Soft deletion preserves historical data integrity: order_items
     * that reference this product's brands will still have valid
     * foreign keys in the database.
     *
     * @param  string $id  UUID of the product
     * @return bool
     */
    public function delete($id)
    {
        $product = $this->findById($id);
        return $product->delete();
    }
}
