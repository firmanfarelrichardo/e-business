<?php

namespace App\Repositories;

use App\Models\Product;

class ProductRepository
{
    public function getAllWithRelations()
    {
        return Product::with('category')->get();
    }

    public function findById($id)
    {
        return Product::with('category')->findOrFail($id);
    }

    public function create(array $data)
    {
        return Product::create($data);
    }

    public function update($id, array $data)
    {
        $product = $this->findById($id);
        $product->update($data);
        return $product;
    }

    public function delete($id)
    {
        $product = $this->findById($id);
        return $product->delete();
    }
}
