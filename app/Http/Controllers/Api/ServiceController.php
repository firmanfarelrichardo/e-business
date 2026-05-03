<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreServiceRequest;
use App\Http\Requests\UpdateServiceRequest;
use App\Services\ServiceService;
use Illuminate\Http\JsonResponse;

class ServiceController extends Controller
{
    protected $serviceService;

    public function __construct(ServiceService $serviceService)
    {
        $this->serviceService = $serviceService;
    }

    public function index(): JsonResponse
    {
        $services = $this->serviceService->getAllServices();
        return response()->json(['data' => $services]);
    }

    public function store(StoreServiceRequest $request): JsonResponse
    {
        $service = $this->serviceService->createService($request->validated());
        return response()->json(['message' => 'Service created successfully', 'data' => $service], 201);
    }

    public function show($id): JsonResponse
    {
        $service = $this->serviceService->getServiceById($id);
        return response()->json(['data' => $service]);
    }

    public function update(UpdateServiceRequest $request, $id): JsonResponse
    {
        $service = $this->serviceService->updateService($id, $request->validated());
        return response()->json(['message' => 'Service updated successfully', 'data' => $service]);
    }

    public function destroy($id): JsonResponse
    {
        $this->serviceService->deleteService($id);
        return response()->json(['message' => 'Service deleted successfully']);
    }
}
