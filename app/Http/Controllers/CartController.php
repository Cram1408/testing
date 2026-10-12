<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    protected CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function index()
    {
        $items = $this->cartService->getItems();
        $subtotal = $this->cartService->getSubtotal();
        $shipping = $this->cartService->getShippingFee();
        $total = $this->cartService->getTotal();
        $freeShippingRemaining = $this->cartService->getFreeShippingRemaining();
        $freeShippingThreshold = CartService::FREE_SHIPPING_THRESHOLD;

        return view('cart.index', compact('items', 'subtotal', 'shipping', 'total', 'freeShippingRemaining', 'freeShippingThreshold'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'variant_id' => 'required|exists:product_variants,id',
            'quantity' => 'nullable|integer|min:1',
        ]);

        $quantity = $request->input('quantity', 1);
        $result = $this->cartService->add((int) $request->variant_id, (int) $quantity);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => $result['success'],
                'message' => $result['message'],
                'cart_count' => $this->cartService->getCount(),
                'cart_subtotal' => $this->cartService->getSubtotal(),
                'cart_items' => array_values($this->cartService->getItems()),
            ]);
        }

        if (!$result['success']) {
            return back()->with('error', $result['message']);
        }

        return back()->with('success', $result['message']);
    }

    public function update(Request $request)
    {
        $request->validate([
            'variant_id' => 'required|integer',
            'quantity' => 'required|integer|min:0',
        ]);

        $result = $this->cartService->update((int) $request->variant_id, (int) $request->quantity);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => $result['success'],
                'message' => $result['message'],
                'cart_count' => $this->cartService->getCount(),
                'cart_subtotal' => $this->cartService->getSubtotal(),
                'cart_total' => $this->cartService->getTotal(),
                'shipping_fee' => $this->cartService->getShippingFee(),
                'free_remaining' => $this->cartService->getFreeShippingRemaining(),
                'cart_items' => array_values($this->cartService->getItems()),
            ]);
        }

        return redirect()->route('cart.index')->with('success', $result['message']);
    }

    public function remove($variantId, Request $request)
    {
        $result = $this->cartService->remove((int) $variantId);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $result['message'],
                'cart_count' => $this->cartService->getCount(),
                'cart_subtotal' => $this->cartService->getSubtotal(),
                'cart_total' => $this->cartService->getTotal(),
                'cart_items' => array_values($this->cartService->getItems()),
            ]);
        }

        return redirect()->route('cart.index')->with('success', $result['message']);
    }

    public function clear()
    {
        $this->cartService->clear();
        return redirect()->route('cart.index')->with('success', 'Your cart has been cleared.');
    }

    public function miniCart()
    {
        return response()->json([
            'items' => array_values($this->cartService->getItems()),
            'count' => $this->cartService->getCount(),
            'subtotal' => $this->cartService->getSubtotal(),
            'shipping' => $this->cartService->getShippingFee(),
            'total' => $this->cartService->getTotal(),
            'free_remaining' => $this->cartService->getFreeShippingRemaining(),
            'free_threshold' => CartService::FREE_SHIPPING_THRESHOLD,
        ]);
    }
}
