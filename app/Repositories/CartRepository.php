<?php

namespace App\Repositories;

use App\Models\Cart;

class CartRepository
{
    public function findByUserId(string $userId)
    {
        return Cart::with(['items.productBrand.product', 'items.productBrand.brand', 'items.service'])
            ->where('user_id', $userId)
            ->first();
    }

    public function firstOrCreate(string $userId)
    {
        return Cart::firstOrCreate(['user_id' => $userId]);
    }

    public function clearCart(string $cartId)
    {
        $cart = Cart::find($cartId);
        if ($cart) {
            $cart->items()->delete();
            $cart->delete();
        }
    }
}
