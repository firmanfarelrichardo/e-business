<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\BatchService;
use Illuminate\Http\Request;

class BatchController extends Controller
{
    protected $batchService;

    public function __construct(BatchService $batchService)
    {
        $this->batchService = $batchService;
    }

    public function index(Request $request)
    {
        $batches = $this->batchService->getAllBatches($request->all());
        return response()->json([
            'success' => true,
            'message' => 'List of Batches',
            'data' => $batches
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'batch_code' => 'sometimes|string|max:50|unique:batches,batch_code',
            'initial_stock' => 'required|integer|min:1',
            'purchase_price' => 'required|numeric|min:0',
            'product_brand_id' => 'required|exists:product_brands,id',
            'created_by' => 'nullable|exists:users,id',
            'is_active' => 'sometimes|boolean'
        ]);

        $batch = $this->batchService->createBatch($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Batch created successfully',
            'data' => $batch
        ], 201);
    }

    public function show($id)
    {
        $batch = $this->batchService->getBatchById($id);
        
        return response()->json([
            'success' => true,
            'message' => 'Batch detail',
            'data' => $batch
        ]);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'batch_code' => 'sometimes|required|string|max:50|unique:batches,batch_code,' . $id,
            'current_stock' => 'sometimes|required|integer|min:0',
            'initial_stock' => 'sometimes|required|integer|min:1',
            'purchase_price' => 'sometimes|required|numeric|min:0',
            'product_brand_id' => 'sometimes|required|exists:product_brands,id',
            'is_active' => 'sometimes|boolean'
        ]);

        $batch = $this->batchService->updateBatch($id, $validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Batch updated successfully',
            'data' => $batch
        ]);
    }

    public function destroy($id)
    {
        $this->batchService->deleteBatch($id);

        return response()->json([
            'success' => true,
            'message' => 'Batch deleted successfully'
        ]);
    }
}
