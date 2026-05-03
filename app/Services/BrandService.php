<?php

namespace App\Services;

use App\Repositories\BrandRepository;

class BrandService
{
    protected $brandRepository;

    public function __construct(BrandRepository $brandRepository)
    {
        $this->brandRepository = $brandRepository;
    }

    public function getAllBrands()
    {
        return $this->brandRepository->getAll();
    }

    public function getBrandById($id)
    {
        return $this->brandRepository->findById($id);
    }

    public function createBrand(array $data)
    {
        return $this->brandRepository->create($data);
    }

    public function updateBrand($id, array $data)
    {
        return $this->brandRepository->update($id, $data);
    }

    public function deleteBrand($id)
    {
        return $this->brandRepository->delete($id);
    }
}
