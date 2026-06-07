<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CashierController extends Controller
{
    public function index()
    {
        // Products: ganti dengan model yang sesuai (Product, Service, dll)
        // Contoh: $products = Product::where('is_active', true)->orderBy('name')->get();
        $products        = collect(); // ganti dengan query produk/layanan kamu
        $customers       = User::where('role', 'member')->where('status', 'active')->orderBy('name')->get();
        $payment_methods = PaymentMethod::orderBy('name')->pluck('name')->toArray();

        // Fallback default jika tabel payment_methods kosong
        if (empty($payment_methods)) {
            $payment_methods = ['Tunai', 'Transfer', 'QRIS', 'Kartu'];
        }

        return view('cashier.index', compact('products', 'customers', 'payment_methods'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'items'          => ['required', 'string'],
            'payment_method' => ['required', 'string'],
            'total'          => ['required', 'numeric', 'min:0'],
        ]);

        $items = json_decode($request->items, true);

        if (empty($items)) {
            return back()->with('error', 'Tidak ada item dalam pesanan.');
        }

        DB::beginTransaction();
        try {
            $order = Order::create([
                'user_id'        => $request->customer_id ?: null,
                'cashier_id'     => Auth::id(),
                'payment_method' => $request->payment_method,
                'cash_received'  => $request->cash_received ?? 0,
                'subtotal'       => $request->total,
                'discount'       => 0,
                'tax'            => 0,
                'total'          => $request->total,
                'status'         => 'done',
                'order_number'   => 'ORD-' . now()->format('Ymd') . '-' . str_pad(Order::whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT),
            ]);

            foreach ($items as $item) {
                OrderItem::create([
                    'order_id'   => $order->id,
                    'name'       => $item['name'],
                    'qty'        => $item['qty'],
                    'unit_price' => $item['price'],
                    'total'      => $item['qty'] * $item['price'],
                ]);
            }

            DB::commit();

            return redirect()->route('invoice.show', $order->id)
                ->with('success', 'Pesanan berhasil diproses!');

        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses pesanan: ' . $e->getMessage());
        }
    }
}