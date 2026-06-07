<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBrandRequest;
use App\Http\Requests\UpdateBrandRequest;
use App\Services\BrandService;
use Illuminate\Http\JsonResponse;

class BrandController extends Controller
{
    protected $brandService;

    public function __construct(BrandService $brandService)
    {
        $this->brandService = $brandService;
    }

    public function index(): JsonResponse
    {
        $brands = $this->brandService->getAllBrands();
        return response()->json(['data' => $brands]);
    }

    public function store(StoreBrandRequest $request): JsonResponse
    {
        $brand = $this->brandService->createBrand($request->validated());
        return response()->json(['message' => 'Brand created successfully', 'data' => $brand], 201);
    }

    public function show($id): JsonResponse
    {
        $brand = $this->brandService->getBrandById($id);
        return response()->json(['data' => $brand]);
    }

    public function update(UpdateBrandRequest $request, $id): JsonResponse
    {
        $brand = $this->brandService->updateBrand($id, $request->validated());
        return response()->json(['message' => 'Brand updated successfully', 'data' => $brand]);
    }

    public function destroy($id): JsonResponse
    {
        $this->brandService->deleteBrand($id);
        return response()->json(['message' => 'Brand deleted successfully']);
    }
}
