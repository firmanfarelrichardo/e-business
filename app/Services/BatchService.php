<?php

namespace App\Services;

use App\Repositories\BatchRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Exception;
use Carbon\Carbon;

class BatchService
{
    protected $batchRepository;

    public function __construct(BatchRepository $batchRepository)
    {
        $this->batchRepository = $batchRepository;
    }

    public function getAllBatches($filters = [])
    {
        return $this->batchRepository->getAll($filters);
    }

    public function getBatchById($id)
    {
        return $this->batchRepository->findById($id);
    }

    public function createBatch(array $data)
    {
        DB::beginTransaction();
        try {
            // Automatically set current_stock to initial_stock if not provided
            if (!isset($data['current_stock'])) {
                $data['current_stock'] = $data['initial_stock'];
            }

            // Generate batch code if not provided
            if (!isset($data['batch_code'])) {
                $data['batch_code'] = 'BCH-' . strtoupper(Str::random(6)) . '-' . date('Ymd');
            }

            $data['created_at'] = Carbon::now();

            $batch = $this->batchRepository->create($data);

            // Trigger WAC recalculation on the related ProductBrand
            if (!empty($data['product_brand_id'])) {
                \App\Models\ProductBrand::find($data['product_brand_id'])?->recalculateWAC();
            }

            DB::commit();
            return $batch;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function updateBatch($id, array $data)
    {
        DB::beginTransaction();
        try {
            $batch = $this->batchRepository->findById($id);
            $batch = $this->batchRepository->update($batch, $data);
            DB::commit();
            return $batch;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function deleteBatch($id)
    {
        DB::beginTransaction();
        try {
            $batch = $this->batchRepository->findById($id);
            $this->batchRepository->delete($batch);
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
