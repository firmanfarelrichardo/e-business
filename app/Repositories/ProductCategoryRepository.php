<?php

namespace App\Repositories;

use App\Models\ProductCategory;

class ProductCategoryRepository
{
    public function getAll()
    {
        return ProductCategory::all();
    }

    public function findById($id)
    {
        return ProductCategory::findOrFail($id);
    }

    public function create(array $data)
    {
        return ProductCategory::create($data);
    }

    public function update($id, array $data)
    {
        $category = $this->findById($id);
        $category->update($data);
        return $category;
    }

    public function delete($id)
    {
        $category = $this->findById($id);
        return $category->delete();
    }
}
