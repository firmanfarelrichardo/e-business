<?php

namespace App\Services;

use App\Repositories\CartRepository;
use App\Repositories\CartItemRepository;
use App\Repositories\ProductBrandRepository;
use Exception;
use Illuminate\Support\Facades\DB;

class CartService
{
    protected CartRepository $cartRepository;
    protected CartItemRepository $cartItemRepository;
    protected ProductBrandRepository $productBrandRepository;

    public function __construct(
        CartRepository $cartRepository,
        CartItemRepository $cartItemRepository,
        ProductBrandRepository $productBrandRepository
    ) {
        $this->cartRepository = $cartRepository;
        $this->cartItemRepository = $cartItemRepository;
        $this->productBrandRepository = $productBrandRepository;
    }

    public function getCart(string $userId)
    {
        return $this->cartRepository->findByUserId($userId);
    }

    public function addToCart(string $userId, array $data)
    {
        return DB::transaction(function () use ($userId, $data) {
            $cart = $this->cartRepository->firstOrCreate($userId);

            // Validate stock if adding a product
            if (isset($data['product_brand_id'])) {
                $productBrand = $this->productBrandRepository->findById($data['product_brand_id']);
                
                // Calculate the intended total quantity in cart for this item
                $existingItem = \App\Models\CartItem::where('cart_id', $cart->id)
                    ->where('product_brand_id', $data['product_brand_id'])
                    ->first();
                
                $intendedQuantity = $data['quantity'];
                if ($existingItem) {
                    $intendedQuantity += $existingItem->quantity;
                }

                if ($productBrand->current_stock < $intendedQuantity) {
                    throw new Exception("Stok tidak mencukupi. Sisa stok: " . $productBrand->current_stock);
                }
            }

            return $this->cartItemRepository->addOrUpdateItem($cart->id, $data);
        });
    }

    public function updateItemQuantity(string $itemId, int $quantity)
    {
        return DB::transaction(function () use ($itemId, $quantity) {
            $item = $this->cartItemRepository->findById($itemId);
            
            if (!$item) {
                throw new Exception("Item keranjang tidak ditemukan.");
            }

            if ($quantity < 1) {
                $this->cartItemRepository->deleteItem($itemId);
                return null;
            }

            // Validate stock if it's a product
            if ($item->product_brand_id) {
                $productBrand = $item->productBrand;
                if ($productBrand->current_stock < $quantity) {
                    throw new Exception("Stok tidak mencukupi. Sisa stok: " . $productBrand->current_stock);
                }
            }

            return $this->cartItemRepository->updateQuantity($itemId, $quantity);
        });
    }

    public function removeItem(string $itemId)
    {
        return $this->cartItemRepository->deleteItem($itemId);
    }
}
