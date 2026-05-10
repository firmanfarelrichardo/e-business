<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\OrderService;

class OrderController extends Controller
{
    public function store(Request $request, OrderService $orderService)
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
            $order = $orderService->createOrder($data);
            session()->forget('cart');
            return redirect('/invoice/' . $order->id)->with('success', 'Pesanan berhasil dibuat.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal membuat pesanan: ' . $e->getMessage());
        }
    }
    public function create()
    {
        return view('kasir.index', [
            'products' => Product::all(),
            'users' => User::all()
        ]);
    }

    public function history()
    {
        if (!auth()->check()) {
            return redirect('/login')->with('error', 'Silahkan login terlebih dahulu');
        }

        $orders = Order::with('items')->where('user_id', auth()->id())->orderBy('created_at', 'desc')->get();
        return view('customer.history', compact('orders'));
    }

    public function invoice($id)
    {
        $order = Order::with('items.productBrand.product', 'items.service', 'user')->where('user_id', auth()->id())->findOrFail($id);
        return view('invoice.show', compact('order'));
    }
}