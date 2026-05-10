<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\OrderService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
        if (!Auth::check()) {
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
            'user_id' => Auth::id(),
            'employee_id' => null,
            'note' => $request->note,
            'items' => $items,
        ];

        try {
            $order = $this->orderService->createOrder($data);
            session()->forget('cart');

            if ($order->transaction && $order->transaction->payment_url) {
                return redirect()->away($order->transaction->payment_url);
            }

            return redirect('/invoice/' . $order->id)
                ->with('error', 'Pesanan dibuat, tetapi link pembayaran Xendit belum tersedia. Silakan coba refresh invoice.');
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
        if (!Auth::check()) {
            return redirect('/login')->with('error', 'Silahkan login terlebih dahulu');
        }

        $orders = Order::with('items')->where('user_id', Auth::id())->orderBy('created_at', 'desc')->get();
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
        if (!Auth::check()) {
            return redirect('/login')->with('error', 'Silahkan login terlebih dahulu untuk melihat invoice.');
        }

        // Fetch the order with its related items, product, brand, service, and user
        $order = Order::with('items.productBrand.product', 'items.productBrand.brand', 'items.service', 'user', 'transaction')
            ->findOrFail($id);

        // Enforce user ownership for regular members to prevent unauthorized access
        if (Auth::user()?->role === 'member' && $order->user_id !== Auth::id()) {
            abort(404);
        }

        // Fallback reconciliation in case Xendit webhook has not updated local status yet.
        $this->orderService->refreshPendingPaymentStatus($order);
        $order->refresh()->load('transaction');

        return view('invoice.show', compact('order'));
    }

    public function simulatePayment(string $transactionCode)
    {
        if (!app()->environment('local')) {
            abort(404);
        }

        $transaction = Transaction::where('transaction_code', $transactionCode)->with('order')->firstOrFail();

        if (Auth::user()?->role === 'member' && $transaction->order?->user_id !== Auth::id()) {
            abort(404);
        }

        DB::transaction(function () use ($transaction) {
            $transaction->update([
                'transaction_status' => 'paid',
                'paid_at' => now(),
            ]);

            if ($transaction->order) {
                $transaction->order->update([
                    'status' => 'processing',
                    'paid_at' => now(),
                ]);
            }
        });

        return redirect('/invoice/' . $transaction->order_id)->with('success', 'Simulasi pembayaran berhasil.');
    }
}
