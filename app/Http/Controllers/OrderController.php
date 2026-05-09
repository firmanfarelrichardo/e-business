<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;

/**
 * OrderController (Root Namespace)
 *
 * Handles customer-facing pages: kasir (POS), order history,
 * and invoice detail. This is distinct from Api\OrderController
 * (JSON API) and Web\OrderController (dashboard queue admin).
 */
class OrderController extends Controller
{
    /**
     * Display the kasir (POS) page for creating new orders.
     *
     * Loads all available products and users for the order form.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('kasir.index', [
            'products' => Product::all(),
            'users' => User::all(),
        ]);
    }

    /**
     * Display order history for the authenticated customer.
     *
     * @return \Illuminate\View\View
     */
    public function history()
    {
        return view('customer.history', [
            'orders' => Order::where('user_id', auth()->id())->get(),
        ]);
    }

    protected \App\Services\OrderService $orderService;

    public function __construct(\App\Services\OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    /**
     * Display a specific order invoice with its line items.
     *
     * @param  string $id  UUID of the order
     * @return \Illuminate\View\View
     */
    public function invoice($id)
    {
        $order = $this->orderService->getOrderById($id);
        return view('invoice.show', compact('order'));
    }
}