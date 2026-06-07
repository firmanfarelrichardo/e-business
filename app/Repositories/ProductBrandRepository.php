<?php

namespace App\Repositories;

use App\Models\ProductBrand;

class ProductBrandRepository
{
    public function getAll($filters = [])
    {
        $query = ProductBrand::with(['product', 'brand', 'batches']);

        if (isset($filters['product_id'])) {
            $query->where('product_id', $filters['product_id']);
        }
        
        if (isset($filters['brand_id'])) {
            $query->where('brand_id', $filters['brand_id']);
        }

        return $query->get();
    }

    public function findById($id)
    {
        return ProductBrand::with(['product', 'brand', 'batches'])->findOrFail($id);
    }

    public function create(array $data)
    {
        return ProductBrand::create($data);
    }

    public function update(ProductBrand $productBrand, array $data)
    {
        $productBrand->update($data);
        return $productBrand;
    }

    public function delete(ProductBrand $productBrand)
    {
        return $productBrand->delete();
    }

    public function getLowStockAlerts($threshold = 10)
    {
        // Get product brands where total current stock from active batches is below threshold
        $productBrands = ProductBrand::with(['product', 'brand'])
            ->get()
            ->filter(function ($pb) use ($threshold) {
                return $pb->current_stock < $threshold;
            })
            ->values();

        return $productBrands;
    }
}
