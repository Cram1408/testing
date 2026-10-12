<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderTrackingController extends Controller
{
    public function index(Request $request)
    {
        $order = null;
        $orderNumber = $request->query('order_number');

        if ($orderNumber) {
            $order = Order::where('order_number', trim($orderNumber))
                ->with(['items.product', 'items.variant'])
                ->first();
        }

        return view('orders.track', compact('order', 'orderNumber'));
    }

    public function track(Request $request)
    {
        $request->validate([
            'order_number' => 'required|string',
            'contact' => 'required|string',
        ]);

        $orderNumber = trim($request->order_number);
        $contact = trim($request->contact);

        $order = Order::where('order_number', $orderNumber)
            ->where(function ($q) use ($contact) {
                $q->where('customer_email', $contact)
                  ->orWhere('customer_phone', $contact);
            })
            ->with(['items.product', 'items.variant'])
            ->first();

        if (!$order) {
            return back()->withInput()->with('error', 'No order found matching order number and contact details provided. Please verify and try again.');
        }

        return view('orders.track', compact('order', 'orderNumber'));
    }
}
