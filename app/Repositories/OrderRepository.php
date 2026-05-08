<?php

namespace App\Repositories;

use App\Models\Order;
use Carbon\Carbon;

class OrderRepository
{
    public function getAll($filters = [])
    {
        $query = Order::with(['user', 'employee', 'items.productBrand.product', 'items.service']);

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    public function findById($id)
    {
        return Order::with(['user', 'employee', 'items.productBrand.product', 'items.service'])->findOrFail($id);
    }

    public function create(array $data)
    {
        return Order::create($data);
    }

    public function update(Order $order, array $data)
    {
        $order->update($data);
        return $order;
    }

    public function getNextQueueNumber()
    {
        // Get the latest order for today
        $latestOrder = Order::whereDate('created_at', Carbon::today())
            ->orderBy('queue_number', 'desc')
            ->first();

        if ($latestOrder && $latestOrder->queue_number) {
            return $latestOrder->queue_number + 1;
        }

        return 1;
    }
}
