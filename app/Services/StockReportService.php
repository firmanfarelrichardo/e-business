<?php

namespace App\Services;

use App\Repositories\ProductBrandRepository;
use App\Repositories\BatchRepository;

class StockReportService
{
    protected $productBrandRepository;
    protected $batchRepository;

    public function __construct(
        ProductBrandRepository $productBrandRepository,
        BatchRepository $batchRepository
    ) {
        $this->productBrandRepository = $productBrandRepository;
        $this->batchRepository = $batchRepository;
    }

    public function getLowStockAlerts($threshold = 10)
    {
        return $this->productBrandRepository->getLowStockAlerts($threshold);
    }

    public function getStockRecap($filters = [])
    {
        // For recap, we return all product brands with their batches history
        return $this->productBrandRepository->getAll($filters);
    }
}
