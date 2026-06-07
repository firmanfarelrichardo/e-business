<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ProductBrandService;
use Illuminate\Http\Request;

class ProductBrandController extends Controller
{
    protected $productBrandService;

    public function __construct(ProductBrandService $productBrandService)
    {
        $this->productBrandService = $productBrandService;
    }

    public function index(Request $request)
    {
        $productBrands = $this->productBrandService->getAllProductBrands($request->all());
        return response()->json([
            'success' => true,
            'message' => 'List of Product Brands',
            'data' => $productBrands
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'unit' => 'required|string|max:100',
            'selling_price' => 'required|numeric|min:0',
            'product_id' => 'required|exists:products,id',
            'brand_id' => 'required|exists:brands,id',
        ]);

        $productBrand = $this->productBrandService->createProductBrand($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Product Brand created successfully',
            'data' => $productBrand
        ], 201);
    }

    public function show($id)
    {
        $productBrand = $this->productBrandService->getProductBrandById($id);
        
        return response()->json([
            'success' => true,
            'message' => 'Product Brand detail',
            'data' => $productBrand
        ]);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'unit' => 'sometimes|required|string|max:100',
            'selling_price' => 'sometimes|required|numeric|min:0',
            'product_id' => 'sometimes|required|exists:products,id',
            'brand_id' => 'sometimes|required|exists:brands,id',
        ]);

        $productBrand = $this->productBrandService->updateProductBrand($id, $validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Product Brand updated successfully',
            'data' => $productBrand
        ]);
    }

    public function destroy($id)
    {
        $this->productBrandService->deleteProductBrand($id);

        return response()->json([
            'success' => true,
            'message' => 'Product Brand deleted successfully'
        ]);
    }
}
