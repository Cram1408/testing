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
            'order_status' => 'required|in:pending,processing,shipped,delivered,cancelled',
            'payment_status' => 'required|in:pending,paid,failed',
            'tracking_number' => 'nullable|string|max:100',
            'courier_name' => 'nullable|string|max:100',
        ]);

        $order->update($validated);

        return back()->with('success', "Order #{$order->order_number} status updated to " . ucfirst($order->order_status) . ".");
    }

    public function invoice(Order $order)
    {
        $order->load(['items.product', 'items.variant', 'user']);
        return view('admin.orders.invoice', compact('order'));
    }
}
