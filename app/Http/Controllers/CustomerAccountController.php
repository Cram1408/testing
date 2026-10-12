<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerAccountController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $orders = Order::where('user_id', $user->id)
            ->orWhere('customer_email', $user->email)
            ->with(['items.product', 'items.variant'])
            ->latest()
            ->paginate(10);

        return view('customer.dashboard', compact('user', 'orders'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:10',
        ]);

        $user->update($validated);

        return back()->with('success', 'Profile details updated successfully.');
    }

    public function orderDetail($orderNumber)
    {
        $user = Auth::user();
        $order = Order::where('order_number', $orderNumber)
            ->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->orWhere('customer_email', $user->email);
            })
            ->with(['items.product', 'items.variant'])
            ->firstOrFail();

        return view('customer.order-detail', compact('order'));
    }
}
