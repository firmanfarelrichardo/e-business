<?php

namespace App\Services;

use App\Repositories\OrderRepository;
use App\Repositories\OrderItemRepository;
use App\Repositories\BatchRepository;
use App\Repositories\ProductBrandRepository;
use App\Repositories\ServiceRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Exception;

class OrderService
{
    protected $orderRepository;
    protected $orderItemRepository;
    protected $batchRepository;
    protected $productBrandRepository;
    protected $serviceRepository;

    public function __construct(
        OrderRepository $orderRepository,
        OrderItemRepository $orderItemRepository,
        BatchRepository $batchRepository,
        ProductBrandRepository $productBrandRepository,
        ServiceRepository $serviceRepository
    ) {
        $this->orderRepository = $orderRepository;
        $this->orderItemRepository = $orderItemRepository;
        $this->batchRepository = $batchRepository;
        $this->productBrandRepository = $productBrandRepository;
        $this->serviceRepository = $serviceRepository;
    }

    public function getAllOrders($filters = [])
    {
        return $this->orderRepository->getAll($filters);
    }

    public function getOrderById($id)
    {
        return $this->orderRepository->findById($id);
    }

    public function createOrder(array $data)
    {
        DB::beginTransaction();
        try {
            // Generate order number
            $orderNumber = 'ORD-' . strtoupper(Str::random(6)) . '-' . date('Ymd');
            
            // Get Queue Number
            $queueNumber = $this->orderRepository->getNextQueueNumber();

            // Calculate total price and prepare items
            $totalPrice = 0;
            $itemsData = [];

            foreach ($data['items'] as $item) {
                $subtotal = 0;
                $pricePerUnit = 0;

                if (isset($item['product_brand_id'])) {
                    $productBrand = $this->productBrandRepository->findById($item['product_brand_id']);
                    
                    // Check stock
                    if ($productBrand->current_stock < $item['quantity']) {
                        throw new Exception("Insufficient stock for product: " . $productBrand->product->name);
                    }
                    
                    $pricePerUnit = $productBrand->selling_price;
                    $subtotal = $pricePerUnit * $item['quantity'];

                } elseif (isset($item['service_id'])) {
                    $service = $this->serviceRepository->findById($item['service_id']);
                    $pricePerUnit = $service->price; // Assuming service has price
                    $subtotal = $pricePerUnit * $item['quantity'];
                } else {
                    throw new Exception("Item must have either product_brand_id or service_id");
                }

                $totalPrice += $subtotal;

                $itemsData[] = [
                    'id' => (string) Str::uuid(),
                    'product_brand_id' => $item['product_brand_id'] ?? null,
                    'service_id' => $item['service_id'] ?? null,
                    'quantity' => $item['quantity'],
                    'price_per_unit' => $pricePerUnit,
                    'subtotal_price' => $subtotal,
                    'note' => $item['note'] ?? null,
                ];
            }

            // Create Order
            $orderData = [
                'order_number' => $orderNumber,
                'queue_number' => $queueNumber,
                'status' => 'pending',
                'user_id' => $data['user_id'],
                'employee_id' => $data['employee_id'] ?? null,
                'note' => $data['note'] ?? null,
                'total_price' => $totalPrice,
            ];

            $order = $this->orderRepository->create($orderData);

            // Create Items
            foreach ($itemsData as &$itemData) {
                $itemData['order_id'] = $order->id;
            }
            $this->orderItemRepository->insertMany($itemsData);

            DB::commit();
            return $this->getOrderById($order->id);

        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function updateOrderStatus($id, $status)
    {
        DB::beginTransaction();
        try {
            $order = $this->orderRepository->findById($id);

            // If changing to completed, perform stock deduction
            if ($status === 'completed' && $order->status !== 'completed') {
                $this->deductStock($order);
                $order->completed_at = Carbon::now();
                if (!$order->paid_at) {
                    $order->paid_at = Carbon::now();
                }
            }

            $order = $this->orderRepository->update($order, ['status' => $status]);

            DB::commit();
            return $order;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    protected function deductStock($order)
    {
        foreach ($order->items as $item) {
            if ($item->product_brand_id) {
                $quantityToDeduct = $item->quantity;
                
                // Get active batches with stock, ordered by oldest first (FIFO)
                $batches = $this->batchRepository->getActiveBatchesWithStock($item->product_brand_id);

                foreach ($batches as $batch) {
                    if ($quantityToDeduct <= 0) {
                        break;
                    }

                    if ($batch->current_stock >= $quantityToDeduct) {
                        // Batch has enough stock
                        $newStock = $batch->current_stock - $quantityToDeduct;
                        $this->batchRepository->update($batch, ['current_stock' => $newStock]);
                        $quantityToDeduct = 0;
                    } else {
                        // Batch doesn't have enough, take all from this batch and continue to next
                        $quantityToDeduct -= $batch->current_stock;
                        $this->batchRepository->update($batch, ['current_stock' => 0]);
                    }
                }

                if ($quantityToDeduct > 0) {
                    throw new Exception("Stock deduction failed. Not enough stock in active batches for product brand ID: " . $item->product_brand_id);
                }
            }
        }
    }
}
