<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\OrderService;

/**
 * OrderController (Root Namespace)
 *
 * Handles customer-facing pages: kasir (POS), order history,
 * and invoice detail. This is distinct from Api\OrderController
 * (JSON API) and Web\OrderController (dashboard queue admin).
 */
class OrderController extends Controller
{
    protected \App\Services\OrderService $orderService;

    public function __construct(\App\Services\OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function store(Request $request)
    {
        if (!auth()->check()) {
            return redirect('/login')->with('error', 'Silakan login terlebih dahulu untuk melakukan checkout.');
        }

        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return back()->with('error', 'Keranjang belanja Anda masih kosong.');
        }

        $items = [];
        foreach ($cart as $item) {
            $items[] = [
                'product_brand_id' => $item['type'] === 'product' ? $item['id'] : null,
                'service_id' => $item['type'] === 'service' ? $item['id'] : null,
                'quantity' => $item['quantity'],
                'note' => null,
            ];
        }

        $data = [
            'user_id' => auth()->id(),
            'employee_id' => null,
            'note' => $request->note,
            'items' => $items,
        ];

        try {
            $order = $this->orderService->createOrder($data);
            session()->forget('cart');
            return redirect('/invoice/' . $order->id)->with('success', 'Pesanan berhasil dibuat.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal membuat pesanan: ' . $e->getMessage());
        }
    }

    /**
     * Display the kasir (POS) page for creating new orders.
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
     * @return \Illuminate\View\View|Illuminate\Http\RedirectResponse
     */
    public function history()
    {
        if (!auth()->check()) {
            return redirect('/login')->with('error', 'Silahkan login terlebih dahulu');
        }

        $orders = Order::with('items')->where('user_id', auth()->id())->orderBy('created_at', 'desc')->get();
        return view('customer.history', compact('orders'));
    }

    /**
     * Display a specific order invoice with its line items.
     *
     * @param  string $id  UUID of the order
     * @return \Illuminate\View\View
     */
    public function invoice($id)
    {
        if (!auth()->check()) {
            return redirect('/login')->with('error', 'Silahkan login terlebih dahulu untuk melihat invoice.');
        }

        // Fetch the order with its related items, product, brand, service, and user
        $order = Order::with('items.productBrand.product', 'items.productBrand.brand', 'items.service', 'user')
            ->findOrFail($id);

        // Enforce user ownership for regular members to prevent unauthorized access
        if (auth()->user()->role === 'member' && $order->user_id !== auth()->id()) {
            abort(404);
        }

        return view('invoice.show', compact('order'));
    }
}