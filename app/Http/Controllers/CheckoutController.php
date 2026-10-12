<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductVariant;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    protected CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function index()
    {
        $items = $this->cartService->getItems();
        if (empty($items)) {
            return redirect()->route('shop.index')->with('info', 'Your cart is empty. Please add items before checking out.');
        }

        $subtotal = $this->cartService->getSubtotal();
        $shipping = $this->cartService->getShippingFee();
        $total = $this->cartService->getTotal();
        $user = auth()->user();

        return view('checkout.index', compact('items', 'subtotal', 'shipping', 'total', 'user'));
    }

    public function process(Request $request)
    {
        $items = $this->cartService->getItems();
        if (empty($items)) {
            return redirect()->route('shop.index')->with('error', 'Your cart is empty.');
        }

        $validated = $request->validate([
            'customer_name' => 'required|string|max:100',
            'customer_email' => 'required|email|max:100',
            'customer_phone' => 'required|string|max:20',
            'shipping_address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'postal_code' => 'required|string|max:10',
            'payment_method' => 'required|in:cod,online_upi,card',
            'notes' => 'nullable|string|max:500',
        ]);

        $subtotal = $this->cartService->getSubtotal();
        $shipping = $this->cartService->getShippingFee();
        $total = $this->cartService->getTotal();

        // Database transaction to guarantee consistency and stock deduction
        try {
            $order = DB::transaction(function () use ($validated, $items, $subtotal, $shipping, $total) {
                // Generate unique order number
                $orderNumber = 'ORD-' . date('Ym') . '-' . strtoupper(Str::random(5));

                $paymentStatus = ($validated['payment_method'] === 'cod') ? 'pending' : 'paid';

                $order = Order::create([
                    'order_number' => $orderNumber,
                    'user_id' => auth()->id(),
                    'customer_name' => $validated['customer_name'],
                    'customer_email' => $validated['customer_email'],
                    'customer_phone' => $validated['customer_phone'],
                    'shipping_address' => $validated['shipping_address'],
                    'city' => $validated['city'],
                    'state' => $validated['state'],
                    'postal_code' => $validated['postal_code'],
                    'subtotal' => $subtotal,
                    'shipping_fee' => $shipping,
                    'discount_amount' => 0.00,
                    'total_amount' => $total,
                    'payment_method' => $validated['payment_method'],
                    'payment_status' => $paymentStatus,
                    'order_status' => 'pending',
                    'notes' => $validated['notes'] ?? null,
                ]);

                foreach ($items as $item) {
                    $variant = ProductVariant::lockForUpdate()->find($item['variant_id']);
                    if (!$variant || $variant->stock_quantity < $item['quantity']) {
                        throw new \Exception("Sorry, {$item['name']} ({$item['weight']}) has insufficient stock available.");
                    }

                    // Decrement stock
                    $variant->decrement('stock_quantity', $item['quantity']);

                    // Create order item
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $item['product_id'],
                        'product_variant_id' => $item['variant_id'],
                        'product_name' => $item['name'],
                        'variant_weight' => $item['weight'],
                        'unit_price' => $item['price'],
                        'quantity' => $item['quantity'],
                        'total_price' => $item['total'],
                        'image' => $item['image'],
                    ]);
                }

                return $order;
            });

            // Clear cart upon successful order placement
            $this->cartService->clear();

            return redirect()->route('checkout.confirmation', $order->order_number)
                ->with('success', 'Thank you! Your order has been placed successfully.');

        } catch (\Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function confirmation($orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)->with(['items.product', 'items.variant'])->firstOrFail();
        return view('checkout.confirmation', compact('order'));
    }
}
