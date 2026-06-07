<?php

namespace App\Repositories;

use App\Models\Service;

class ServiceRepository
{
    public function getAll()
    {
        return Service::all();
    }

    public function findById($id)
    {
        return Service::findOrFail($id);
    }

    public function create(array $data)
    {
        return Service::create($data);
    }

    public function update($id, array $data)
    {
        $service = $this->findById($id);
        $service->update($data);
        return $service;
    }

    public function delete($id)
    {
        $service = $this->findById($id);
        return $service->delete();
    }
}
