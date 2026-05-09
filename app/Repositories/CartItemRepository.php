<?php

namespace App\Repositories;

use App\Models\CartItem;

class CartItemRepository
{
    public function addOrUpdateItem(string $cartId, array $data)
    {
        $query = CartItem::where('cart_id', $cartId);

        if (isset($data['product_brand_id'])) {
            $query->where('product_brand_id', $data['product_brand_id']);
        } elseif (isset($data['service_id'])) {
            $query->where('service_id', $data['service_id']);
        }

        $existingItem = $query->first();

        if ($existingItem) {
            // Update quantity if item already exists
            $existingItem->quantity += $data['quantity'];
            $existingItem->save();
            return $existingItem;
        }

        // Create new item
        $data['cart_id'] = $cartId;
        return CartItem::create($data);
    }

    public function findById(string $itemId)
    {
        return CartItem::with(['productBrand', 'service'])->find($itemId);
    }

    public function updateQuantity(string $itemId, int $quantity)
    {
        $item = CartItem::find($itemId);
        if ($item) {
            $item->quantity = $quantity;
            $item->save();
        }
        return $item;
    }

    public function deleteItem(string $itemId)
    {
        return CartItem::destroy($itemId);
    }
}
