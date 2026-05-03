<?php

namespace App\Repositories;

use App\Models\Brand;

class BrandRepository
{
    public function getAll()
    {
        return Brand::all();
    }

    public function findById($id)
    {
        return Brand::findOrFail($id);
    }

    public function create(array $data)
    {
        return Brand::create($data);
    }

    public function update($id, array $data)
    {
        $brand = $this->findById($id);
        $brand->update($data);
        return $brand;
    }

    public function delete($id)
    {
        $brand = $this->findById($id);
        return $brand->delete();
    }
}
