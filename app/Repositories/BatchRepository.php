<?php

namespace App\Repositories;

use App\Models\Batch;

class BatchRepository
{
    public function getAll($filters = [])
    {
        $query = Batch::with(['productBrand.product', 'productBrand.brand', 'creator']);

        if (isset($filters['product_brand_id'])) {
            $query->where('product_brand_id', $filters['product_brand_id']);
        }

        if (isset($filters['is_active'])) {
            $query->where('is_active', $filters['is_active']);
        }

        return $query->get();
    }

    public function findById($id)
    {
        return Batch::with(['productBrand.product', 'productBrand.brand', 'creator'])->findOrFail($id);
    }

    public function create(array $data)
    {
        return Batch::create($data);
    }

    public function update(Batch $batch, array $data)
    {
        $batch->update($data);
        return $batch;
    }

    public function delete(Batch $batch)
    {
        return $batch->delete();
    }

    public function getActiveBatchesWithStock($productBrandId)
    {
        return Batch::where('product_brand_id', $productBrandId)
            ->where('is_active', true)
            ->where('current_stock', '>', 0)
            ->orderBy('created_at', 'asc') // FIFO
            ->get();
    }
}
