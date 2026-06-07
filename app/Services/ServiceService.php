<?php

namespace App\Services;

use App\Repositories\ServiceRepository;
use Illuminate\Support\Facades\Storage;

class ServiceService
{
    protected $serviceRepository;

    public function __construct(ServiceRepository $serviceRepository)
    {
        $this->serviceRepository = $serviceRepository;
    }

    public function getAllServices()
    {
        return $this->serviceRepository->getAll();
    }

    public function getServiceById($id)
    {
        return $this->serviceRepository->findById($id);
    }

    public function createService(array $data)
    {
        if (isset($data['images']) && is_array($data['images'])) {
            $data['attachments'] = $this->uploadImages($data['images']);
        }

        return $this->serviceRepository->create($data);
    }

    public function updateService($id, array $data)
    {
        $service = $this->getServiceById($id);

        if (isset($data['images']) && is_array($data['images'])) {
            $data['attachments'] = $this->uploadImages($data['images']);
        }

        return $this->serviceRepository->update($id, $data);
    }

    public function deleteService($id)
    {
        return $this->serviceRepository->delete($id);
    }

    protected function uploadImages(array $images)
    {
        $paths = [];
        foreach ($images as $image) {
            $paths[] = $image->store('services', 'public');
        }
        return $paths;
    }
}
