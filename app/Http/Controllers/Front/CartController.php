<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Services\CartService;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Exception;

class CartController extends Controller
{
    protected CartService $cartService;
    protected OrderService $orderService;

    public function __construct(CartService $cartService, OrderService $orderService)
    {
        $this->cartService = $cartService;
        $this->orderService = $orderService;
    }

    public function index(Request $request)
    {
        $user = auth()->user();
        $cart = $this->cartService->getCart($user->id);
        
        $totalPrice = 0;
        if ($cart) {
            foreach ($cart->items as $item) {
                if ($item->productBrand) {
                    $totalPrice += $item->productBrand->selling_price * $item->quantity;
                } elseif ($item->service) {
                    $totalPrice += $item->service->piece_price * $item->quantity;
                }
            }
        }

        return view('keranjang.index', compact('cart', 'totalPrice'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_brand_id' => 'nullable|uuid|exists:product_brands,id',
            'service_id'       => 'nullable|uuid|exists:services,id',
            'quantity'         => 'required|integer|min:1',
            'note'             => 'nullable|string'
        ]);

        if (!$request->product_brand_id && !$request->service_id) {
            return redirect()->back()->with('error', 'Item tidak valid.');
        }

        try {
            $user = auth()->user();
            $this->cartService->addToCart($user->id, $request->only('product_brand_id', 'service_id', 'quantity', 'note'));
            
            return redirect()->route('keranjang.index')->with('success', 'Item berhasil ditambahkan ke keranjang.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function updateQuantity(Request $request, string $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:0'
        ]);

        try {
            $this->cartService->updateItemQuantity($id, $request->quantity);
            return redirect()->route('keranjang.index')->with('success', 'Kuantitas berhasil diperbarui.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function remove(string $id)
    {
        try {
            $this->cartService->removeItem($id);
            return redirect()->route('keranjang.index')->with('success', 'Item berhasil dihapus.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function checkout()
    {
        try {
            $user = auth()->user();
            $order = $this->orderService->checkoutCart($user);
            
            return redirect()->route('invoice', $order->id)->with('success', 'Pesanan berhasil dibuat!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
