<?php

namespace App\Services;

use App\Repositories\ProductBrandRepository;
use Illuminate\Support\Facades\DB;
use Exception;

class ProductBrandService
{
    protected $productBrandRepository;

    public function __construct(ProductBrandRepository $productBrandRepository)
    {
        $this->productBrandRepository = $productBrandRepository;
    }

    public function getAllProductBrands($filters = [])
    {
        return $this->productBrandRepository->getAll($filters);
    }

    public function getProductBrandById($id)
    {
        return $this->productBrandRepository->findById($id);
    }

    public function createProductBrand(array $data)
    {
        DB::beginTransaction();
        try {
            $productBrand = $this->productBrandRepository->create($data);
            DB::commit();
            return $productBrand;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function updateProductBrand($id, array $data)
    {
        DB::beginTransaction();
        try {
            $productBrand = $this->productBrandRepository->findById($id);
            $productBrand = $this->productBrandRepository->update($productBrand, $data);
            DB::commit();
            return $productBrand;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function deleteProductBrand($id)
    {
        DB::beginTransaction();
        try {
            $productBrand = $this->productBrandRepository->findById($id);
            $this->productBrandRepository->delete($productBrand);
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
