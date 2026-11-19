<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('items');

        if ($request->filled('status') && $request->get('status') !== 'all') {
            $query->where('order_status', $request->get('status'));
        }

        if ($request->filled('payment') && $request->get('payment') !== 'all') {
            $query->where('payment_status', $request->get('payment'));
        }

        if ($request->filled('q')) {
            $search = $request->get('q');
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_email', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%");
            });
        }

        $orders = $query->latest()->paginate(12)->withQueryString();

        $statusCounts = [
            'all' => Order::count(),
            'pending' => Order::where('order_status', 'pending')->count(),
            'processing' => Order::where('order_status', 'processing')->count(),
            'shipped' => Order::where('order_status', 'shipped')->count(),
            'delivered' => Order::where('order_status', 'delivered')->count(),
            'cancelled' => Order::where('order_status', 'cancelled')->count(),
            'returned' => Order::where('order_status', 'returned')->count(),
        ];

        return view('admin.orders.index', compact('orders', 'statusCounts'));
    }

    public function show(Order $order)
    {
        $order->load(['items.product', 'items.variant', 'user']);
        return view('admin.orders.show', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'order_status'    => 'required|in:pending,processing,shipped,delivered,cancelled,returned',
            'payment_status'  => 'required|in:pending,paid,failed,refunded',
            'tracking_number' => 'nullable|string|max:100',
            'courier_name'    => 'nullable|string|max:100',
            'notes'           => 'nullable|string|max:1000',
        ]);

        // If order status is being transitioned to cancelled/returned and restock requested
        $previousStatus = $order->order_status;
        $order->update($validated);

        if ($request->boolean('restock_items') && in_array($validated['order_status'], ['cancelled', 'returned']) && !in_array($previousStatus, ['cancelled', 'returned'])) {
            foreach ($order->items as $item) {
                if ($item->variant) {
                    $item->variant->increment('stock_quantity', $item->quantity);
                }
            }
        }

        return back()->with('success', "Order #{$order->order_number} status updated successfully.");
    }

    public function invoice(Order $order)
    {
        $order->load(['items.product', 'items.variant', 'user']);
        return view('orders.invoice', compact('order'));
    }
}
