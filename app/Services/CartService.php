<?php

namespace App\Services;

use App\Models\ProductVariant;

class CartService
{
    const FREE_SHIPPING_THRESHOLD = 499;
    const STANDARD_SHIPPING_FEE = 49;

    /**
     * Get all items currently in cart with live variant & product info
     */
    public function getItems(): array
    {
        $rawCart = session()->get('cart', []);
        $items = [];

        foreach ($rawCart as $variantId => $item) {
            $variant = ProductVariant::with('product')->find($variantId);
            if ($variant && $variant->product && $variant->product->is_active) {
                $qty = min($item['quantity'], max(1, $variant->stock_quantity));
                $price = (float) $variant->effective_price;
                $items[$variantId] = [
                    'variant_id' => $variant->id,
                    'product_id' => $variant->product->id,
                    'name' => $variant->product->name,
                    'slug' => $variant->product->slug,
                    'weight' => $variant->weight_value,
                    'price' => $price,
                    'original_price' => (float) $variant->price,
                    'image' => $variant->product->image,
                    'quantity' => $qty,
                    'max_stock' => $variant->stock_quantity,
                    'total' => $price * $qty,
                ];
            }
        }

        return $items;
    }

    /**
     * Add item to cart
     */
    public function add(int $variantId, int $quantity = 1): array
    {
        $variant = ProductVariant::with('product')->findOrFail($variantId);

        if ($variant->stock_quantity <= 0) {
            return ['success' => false, 'message' => 'Sorry, this item is currently out of stock.'];
        }

        $cart = session()->get('cart', []);

        $currentQty = isset($cart[$variantId]) ? $cart[$variantId]['quantity'] : 0;
        $newQty = min($currentQty + $quantity, $variant->stock_quantity);

        $cart[$variantId] = [
            'variant_id' => $variant->id,
            'quantity' => $newQty,
        ];

        session()->put('cart', $cart);

        return [
            'success' => true,
            'message' => "{$variant->product->name} ({$variant->weight_value}) added to cart!",
            'count' => $this->getCount(),
            'subtotal' => $this->getSubtotal(),
        ];
    }

    /**
     * Update item quantity
     */
    public function update(int $variantId, int $quantity): array
    {
        $cart = session()->get('cart', []);

        if (!isset($cart[$variantId])) {
            return ['success' => false, 'message' => 'Item not found in cart.'];
        }

        if ($quantity <= 0) {
            return $this->remove($variantId);
        }

        $variant = ProductVariant::find($variantId);
        if (!$variant) {
            unset($cart[$variantId]);
            session()->put('cart', $cart);
            return ['success' => false, 'message' => 'Product variant not found.'];
        }

        $allowedQty = min($quantity, $variant->stock_quantity);
        $cart[$variantId]['quantity'] = $allowedQty;
        session()->put('cart', $cart);

        return [
            'success' => true,
            'message' => 'Cart updated.',
            'count' => $this->getCount(),
            'subtotal' => $this->getSubtotal(),
        ];
    }

    /**
     * Remove item from cart
     */
    public function remove(int $variantId): array
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$variantId])) {
            unset($cart[$variantId]);
            session()->put('cart', $cart);
        }

        return [
            'success' => true,
            'message' => 'Item removed from cart.',
            'count' => $this->getCount(),
            'subtotal' => $this->getSubtotal(),
        ];
    }

    /**
     * Clear all cart items
     */
    public function clear(): void
    {
        session()->forget('cart');
    }

    /**
     * Get subtotal of cart items
     */
    public function getSubtotal(): float
    {
        $items = $this->getItems();
        $subtotal = 0;
        foreach ($items as $item) {
            $subtotal += $item['total'];
        }
        return round($subtotal, 2);
    }

    /**
     * Get shipping fee
     */
    public function getShippingFee(): float
    {
        $subtotal = $this->getSubtotal();
        if ($subtotal <= 0 || $subtotal >= self::FREE_SHIPPING_THRESHOLD) {
            return 0.00;
        }
        return self::STANDARD_SHIPPING_FEE;
    }

    /**
     * Get grand total
     */
    public function getTotal(): float
    {
        return round($this->getSubtotal() + $this->getShippingFee(), 2);
    }

    /**
     * Get total quantity count
     */
    public function getCount(): int
    {
        $items = $this->getItems();
        $count = 0;
        foreach ($items as $item) {
            $count += $item['quantity'];
        }
        return $count;
    }

    /**
     * Amount remaining for free shipping
     */
    public function getFreeShippingRemaining(): float
    {
        $subtotal = $this->getSubtotal();
        return max(0, round(self::FREE_SHIPPING_THRESHOLD - $subtotal, 2));
    }
}
