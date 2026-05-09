<?php

namespace App\Repositories;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * OrderRepository
 *
 * Handles all direct database interactions for the Order model.
 * Contains the thread-safe queue number generation using database-level
 * locking to prevent race conditions in concurrent order creation.
 */
class OrderRepository
{
    /**
     * Retrieve orders with filtering and pagination.
     *
     * Accepts an optional status filter and per-page count for
     * the dashboard queue management view. Eager-loads all relations
     * needed to render order cards without additional queries.
     *
     * @param  array $filters   Optional filter criteria (status, user_id)
     * @param  int   $perPage   Number of results per page
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getAll($filters = [], $perPage = 0)
    {
        $query = Order::with(['user', 'employee', 'items.productBrand.product', 'items.service']);

        if (isset($filters['status'])) {
            if ($filters['status'] === 'active') {
                $query->whereIn('status', ['pending', 'processing']);
            } else {
                $query->where('status', $filters['status']);
            }
        }

        if (isset($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        $query->orderBy('created_at', 'asc');

        // Return paginated results when perPage is specified, otherwise a full collection
        if ($perPage > 0) {
            return $query->paginate($perPage)->withQueryString();
        }

        return $query->get();
    }

    /**
     * Find a single order by UUID with all relations.
     *
     * @param  string $id  UUID of the order
     * @return \App\Models\Order
     */
    public function findById($id)
    {
        return Order::with(['user', 'employee', 'items.productBrand.product', 'items.service'])
            ->findOrFail($id);
    }

    /**
     * Persist a new order record.
     *
     * @param  array $data  Mass-assignable order attributes
     * @return \App\Models\Order
     */
    public function create(array $data)
    {
        return Order::create($data);
    }

    /**
     * Update an existing order record.
     *
     * @param  \App\Models\Order $order  The order model instance
     * @param  array             $data   Attributes to update
     * @return \App\Models\Order
     */
    public function update(Order $order, array $data)
    {
        $order->update($data);
        return $order;
    }

    /**
     * Generate the next queue number in a thread-safe manner.
     *
     * Uses SELECT ... FOR UPDATE (pessimistic locking) to prevent
     * two concurrent transactions from reading the same max queue_number
     * before either has committed. This is critical in a multi-user
     * environment where multiple cashiers may create orders simultaneously.
     *
     * The lock is scoped to today's orders only, as queue numbers
     * reset daily (common pattern in retail/service businesses).
     *
     * IMPORTANT: This method MUST be called within a DB::transaction()
     * block for the lock to be effective.
     *
     * @return int  The next sequential queue number for today
     */
    public function getNextQueueNumberLocked(): int
    {
        $latestOrder = Order::whereDate('created_at', Carbon::today())
            ->lockForUpdate()
            ->orderBy('queue_number', 'desc')
            ->first();

        if ($latestOrder && $latestOrder->queue_number) {
            return $latestOrder->queue_number + 1;
        }

        return 1;
    }

    /**
     * Retrieve the next queue number without locking.
     *
     * Retained for backward compatibility with the API layer.
     * For concurrent-safe usage, prefer getNextQueueNumberLocked().
     *
     * @return int
     */
    public function getNextQueueNumber(): int
    {
        $latestOrder = Order::whereDate('created_at', Carbon::today())
            ->orderBy('queue_number', 'desc')
            ->first();

        if ($latestOrder && $latestOrder->queue_number) {
            return $latestOrder->queue_number + 1;
        }

        return 1;
    }

    /**
     * Count orders with pending status.
     *
     * Used for badge display in the dashboard sidebar and queue
     * management tab headers to indicate unprocessed orders.
     *
     * @return int
     */
    public function getPendingCount(): int
    {
        return Order::where('status', 'pending')->count();
    }
}
