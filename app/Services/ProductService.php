<?php

namespace App\Services;

use App\Repositories\ProductRepository;
use Illuminate\Support\Facades\Storage;

class ProductService
{
    protected $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function getAllProducts()
    {
        return $this->productRepository->getAllWithRelations();
    }

    public function getProductById($id)
    {
        return $this->productRepository->findById($id);
    }

    public function createProduct(array $data)
    {
        if (isset($data['images']) && is_array($data['images'])) {
            $data['attachments'] = $this->uploadImages($data['images']);
        }

        return $this->productRepository->create($data);
    }

    public function updateProduct($id, array $data)
    {
        $product = $this->getProductById($id);

        if (isset($data['images']) && is_array($data['images'])) {
            // Optionally delete old images here if needed
            $data['attachments'] = $this->uploadImages($data['images']);
        }

        return $this->productRepository->update($id, $data);
    }

    public function deleteProduct($id)
    {
        return $this->productRepository->delete($id);
    }

    protected function uploadImages(array $images)
    {
        $paths = [];
        foreach ($images as $image) {
            $paths[] = $image->store('products', 'public');
        }
        return $paths;
    }
}
