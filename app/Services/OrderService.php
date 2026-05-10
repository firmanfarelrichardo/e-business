<?php

namespace App\Services;

use App\Repositories\OrderRepository;
use App\Repositories\OrderItemRepository;
use App\Repositories\BatchRepository;
use App\Repositories\ProductBrandRepository;
use App\Repositories\ServiceRepository;
use App\Repositories\CartRepository;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Log;

/**
 * OrderService
 *
 * Central business logic layer for order management.
 * Coordinates the complete order lifecycle: creation with thread-safe
 * queue numbering, item assembly with price calculation, status
 * transitions with stock deduction, and dashboard query delegation.
 *
 * All database mutations occur within transactions to maintain data
 * consistency across the orders, order_items, and batches tables.
 */
class OrderService
{
    protected OrderRepository $orderRepository;
    protected OrderItemRepository $orderItemRepository;
    protected BatchRepository $batchRepository;
    protected ProductBrandRepository $productBrandRepository;
    protected ServiceRepository $serviceRepository;
    protected CartRepository $cartRepository;
    protected XenditService $xenditService;

    public function __construct(
        OrderRepository $orderRepository,
        OrderItemRepository $orderItemRepository,
        BatchRepository $batchRepository,
        ProductBrandRepository $productBrandRepository,
        ServiceRepository $serviceRepository,
        CartRepository $cartRepository,
        XenditService $xenditService
    ) {
        $this->orderRepository = $orderRepository;
        $this->orderItemRepository = $orderItemRepository;
        $this->batchRepository = $batchRepository;
        $this->productBrandRepository = $productBrandRepository;
        $this->serviceRepository = $serviceRepository;
        $this->cartRepository = $cartRepository;
        $this->xenditService = $xenditService;
    }

    /**
     * Retrieve all orders with optional filtering.
     *
     * Delegates directly to the repository. Used by the API layer
     * where pagination is not required.
     *
     * @param  array $filters  Optional status/user filters
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllOrders($filters = [])
    {
        return $this->orderRepository->getAll($filters);
    }

    /**
     * Retrieve paginated active orders for the dashboard queue view.
     *
     * Supports status filtering (active, pending, processing) and
     * returns a paginator instance compatible with Blade's links().
     *
     * @param  string $statusFilter  Filter key: 'active', 'pending', 'processing'
     * @param  int    $perPage       Results per page
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getActiveOrders(string $statusFilter = 'active', int $perPage = 20)
    {
        return $this->orderRepository->getAll(
            ['status' => $statusFilter],
            $perPage
        );
    }

    /**
     * Retrieve a single order by UUID.
     *
     * @param  string $id  UUID of the order
     * @return \App\Models\Order
     */
    public function getOrderById($id)
    {
        return $this->orderRepository->findById($id);
    }

    /**
     * Get the count of pending orders.
     *
     * Used for notification badges in the dashboard UI to indicate
     * how many orders are waiting to be processed.
     *
     * @return int
     */
    public function getPendingCount(): int
    {
        return $this->orderRepository->getPendingCount();
    }

    /**
     * Create a new order with items and thread-safe queue numbering.
     *
     * The entire operation is wrapped in a database transaction to ensure
     * atomicity: if any step fails (stock check, item creation), no
     * partial data is committed.
     *
     * Queue number generation uses lockForUpdate() to prevent two
     * concurrent transactions from generating the same number.
     *
     * @param  array $data  Validated order data with 'items' array
     * @return \App\Models\Order
     *
     * @throws \Exception  If stock is insufficient or item type is invalid
     */
    public function createOrder(array $data)
    {
        DB::beginTransaction();
        try {
            // Generate a unique order number using random characters and date
            $orderNumber = 'ORD-' . strtoupper(Str::random(6)) . '-' . date('Ymd');

            // Thread-safe queue number generation within the transaction lock
            $queueNumber = $this->orderRepository->getNextQueueNumberLocked();

            // Calculate total price and prepare item records
            $totalPrice = 0;
            $itemsData = [];

            foreach ($data['items'] as $item) {
                $subtotal = 0;
                $pricePerUnit = 0;

                if (isset($item['product_brand_id'])) {
                    $productBrand = $this->productBrandRepository->findById($item['product_brand_id']);

                    // Verify sufficient stock before accepting the order
                    if ($productBrand->current_stock < $item['quantity']) {
                        throw new Exception("Stok tidak mencukupi untuk produk: " . $productBrand->product->name);
                    }

                    $pricePerUnit = $productBrand->selling_price;
                    $subtotal = $pricePerUnit * $item['quantity'];

                } elseif (isset($item['service_id'])) {
                    $service = $this->serviceRepository->findById($item['service_id']);
                    $pricePerUnit = $service->piece_price;
                    $subtotal = $pricePerUnit * $item['quantity'];

                } else {
                    throw new Exception("Item harus memiliki product_brand_id atau service_id.");
                }

                $totalPrice += $subtotal;

                $itemsData[] = [
                    'id'               => (string) Str::uuid(),
                    'product_brand_id' => $item['product_brand_id'] ?? null,
                    'service_id'       => $item['service_id'] ?? null,
                    'quantity'         => $item['quantity'],
                    'price_per_unit'   => $pricePerUnit,
                    'subtotal_price'   => $subtotal,
                    'note'             => $item['note'] ?? null,
                ];
            }

            // Persist the order header
            $orderData = [
                'order_number' => $orderNumber,
                'queue_number' => $queueNumber,
                'status'       => 'pending',
                'user_id'      => $data['user_id'],
                'employee_id'  => $data['employee_id'] ?? null,
                'note'         => $data['note'] ?? null,
                'total_price'  => $totalPrice,
            ];

            $order = $this->orderRepository->create($orderData);

            // Attach the order UUID to each item and bulk-insert
            foreach ($itemsData as &$itemData) {
                $itemData['order_id'] = $order->id;
            }
            $this->orderItemRepository->insertMany($itemsData);

            // Create payment invoice in Xendit so customer can continue to sandbox payment page.
            $invoice = $this->xenditService->createInvoice($order->loadMissing('user'));

            Transaction::create([
                'order_id' => $order->id,
                'transaction_code' => $invoice['external_id'],
                'transaction_status' => $invoice['status'],
                'payment_url' => $invoice['invoice_url'],
                'gateway_response' => $invoice['raw'],
                'created_at' => now(),
            ]);

            DB::commit();
            return $this->getOrderById($order->id);

        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Transition an order's status with business rule enforcement.
     *
     * Validates that the requested transition is legal:
     * - pending    -> processing | cancelled
     * - processing -> completed  | cancelled
     * - completed  -> (terminal state, no further transitions)
     * - cancelled  -> (terminal state, no further transitions)
     *
     * When completing an order, performs FIFO stock deduction from
     * active batches and sets the completed_at timestamp.
     *
     * Auto-assigns the current authenticated user as the handling
     * employee if no employee was previously assigned.
     *
     * @param  string $id      UUID of the order
     * @param  string $status  Target status
     * @return \App\Models\Order
     *
     * @throws \Exception  If the transition is invalid or stock deduction fails
     */
    public function updateOrderStatus($id, $status)
    {
        DB::beginTransaction();
        try {
            $order = $this->orderRepository->findById($id);

            // Enforce valid status transitions
            $this->validateStatusTransition($order->status, $status);

            $updateData = ['status' => $status];

            // Perform stock deduction when completing an order
            if ($status === 'completed' && $order->status !== 'completed') {
                $this->deductStock($order);
                $updateData['completed_at'] = Carbon::now();

                if (!$order->paid_at) {
                    $updateData['paid_at'] = Carbon::now();
                }
            }

            // Auto-assign the current staff member as the order handler
            if (is_null($order->employee_id) && Auth::check()) {
                $updateData['employee_id'] = Auth::id();
            }

            $order = $this->orderRepository->update($order, $updateData);

            DB::commit();
            return $order;

        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Validate that a status transition is permitted.
     *
     * Prevents illogical transitions such as reverting a completed
     * order to pending, which would break inventory consistency
     * (stock was already deducted upon completion).
     *
     * @param  string $currentStatus  Current order status
     * @param  string $newStatus      Requested target status
     * @return void
     *
     * @throws \Exception  If the transition is not allowed
     */
    protected function validateStatusTransition(string $currentStatus, string $newStatus): void
    {
        $allowed = [
            'pending'    => ['processing', 'cancelled'],
            'processing' => ['completed', 'cancelled'],
            'completed'  => [],
            'cancelled'  => [],
        ];

        if (!in_array($newStatus, $allowed[$currentStatus] ?? [])) {
            throw new Exception(
                "Transisi status tidak valid: {$currentStatus} -> {$newStatus}."
            );
        }
    }

    /**
     * Deduct stock from active batches using FIFO (First In, First Out).
     *
     * Iterates through the order's product items and reduces stock
     * from the oldest active batches first. This ensures that older
     * inventory is consumed before newer stock, which is standard
     * practice in retail accounting.
     *
     * @param  \App\Models\Order $order  The order being completed
     * @return void
     *
     * @throws \Exception  If insufficient stock exists across all batches
     */
    protected function deductStock($order): void
    {
        foreach ($order->items as $item) {
            if ($item->product_brand_id) {
                $quantityToDeduct = $item->quantity;
                $totalCogs = 0;

                // CRITICAL HOOK: Pessimistic locking to prevent race condition during checkout
                $this->batchRepository->lockProductBrandForUpdate($item->product_brand_id);

                // Retrieve batches ordered by creation date (FIFO)
                $batches = $this->batchRepository->getActiveBatchesWithStock($item->product_brand_id);

                foreach ($batches as $batch) {
                    if ($quantityToDeduct <= 0) {
                        break;
                    }

                    if ($batch->current_stock >= $quantityToDeduct) {
                        // This batch has enough stock to fulfill the remainder
                        $totalCogs += $quantityToDeduct * $batch->purchase_price;
                        $newStock = $batch->current_stock - $quantityToDeduct;
                        $this->batchRepository->update($batch, ['current_stock' => $newStock]);
                        $quantityToDeduct = 0;
                    } else {
                        // Consume all stock from this batch and continue to the next
                        $totalCogs += $batch->current_stock * $batch->purchase_price;
                        $quantityToDeduct -= $batch->current_stock;
                        $this->batchRepository->update($batch, ['current_stock' => 0]);
                    }
                }

                if ($quantityToDeduct > 0) {
                    throw new Exception(
                        "Stok tidak cukup untuk product brand ID: {$item->product_brand_id}. " .
                        "Sisa {$quantityToDeduct} unit tidak dapat dipenuhi dari batch aktif."
                    );
                }

                /*
                 * DATA IMMUTABILITY GUARANTEE:
                 * COGS (Cost of Goods Sold) is calculated strictly based on the physical
                 * batches consumed during this exact transaction. By snapshotting this
                 * value into the order_item, historical profit margins are permanently locked.
                 * Even if batch prices are edited or recalculated in the future, this
                 * order's profitability metrics will remain perfectly intact and accurate.
                 */
                $this->orderItemRepository->update($item, ['cogs' => $totalCogs]);

            } else {
                // For Services, COGS is currently 0 as there is no inventory cost.
                $this->orderItemRepository->update($item, ['cogs' => 0]);
            }
        }
    }

    /**
     * Checkout the user's cart into an order.
     *
     * Converts cart items to an order creation array and delegates
     * to createOrder() to handle the transaction and stock validation.
     * Clears the cart upon success.
     *
     * @param  \App\Models\User $user
     * @return \App\Models\Order
     * @throws \Exception
     */
    public function checkoutCart(User $user)
    {
        $cart = $this->cartRepository->findByUserId($user->id);

        if (!$cart || $cart->items->isEmpty()) {
            throw new Exception("Keranjang belanja kosong.");
        }

        $itemsData = [];
        foreach ($cart->items as $item) {
            $itemsData[] = [
                'product_brand_id' => $item->product_brand_id,
                'service_id'       => $item->service_id,
                'quantity'         => $item->quantity,
                'note'             => $item->note,
            ];
        }

        $orderData = [
            'user_id' => $user->id,
            'items'   => $itemsData,
            // 'employee_id' => null, (will be picked up by auto-assign if applicable, or left null)
        ];

        return DB::transaction(function () use ($orderData, $cart) {
            $order = $this->createOrder($orderData);
            $this->cartRepository->clearCart($cart->id);
            return $order;
        });
    }

    /**
     * Fallback sync when webhook is delayed/failed:
     * query Xendit by external_id and update local transaction/order state.
     */
    public function refreshPendingPaymentStatus(\App\Models\Order $order): void
    {
        $order->loadMissing('transaction');
        $transaction = $order->transaction;

        if (!$transaction || $transaction->transaction_code === '') {
            return;
        }

        if (!in_array($transaction->transaction_status, ['pending', 'created'], true)) {
            return;
        }

        try {
            $invoice = $this->xenditService->getInvoiceByExternalId($transaction->transaction_code);
            if (!$invoice) {
                return;
            }

            DB::transaction(function () use ($order, $transaction, $invoice) {
                $updates = [
                    'transaction_status' => $invoice['status'],
                    'gateway_response' => $invoice['raw'],
                ];

                if (!empty($invoice['invoice_url']) && empty($transaction->payment_url)) {
                    $updates['payment_url'] = $invoice['invoice_url'];
                }

                if (in_array($invoice['status'], ['paid', 'settled'], true)) {
                    $updates['paid_at'] = $transaction->paid_at ?? now();
                }

                $transaction->update($updates);

                if (in_array($invoice['status'], ['paid', 'settled'], true) && $order->status === 'pending') {
                    $order->update([
                        'status' => 'processing',
                        'paid_at' => $order->paid_at ?? now(),
                    ]);
                }
            });
        } catch (\Throwable $e) {
            Log::warning('Gagal sinkronisasi fallback status Xendit', [
                'order_id' => $order->id,
                'transaction_code' => $transaction->transaction_code,
                'message' => $e->getMessage(),
            ]);
        }
    }
}
