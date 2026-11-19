<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CustomerAccountController extends Controller
{
    public function dashboard()
    {
        return redirect()->route('customer.profile');
    }

    public function profile()
    {
        $user = Auth::user();
        $ordersCount = Order::where('user_id', $user->id)
            ->orWhere('customer_email', $user->email)
            ->count();

        return view('customer.profile', compact('user', 'ordersCount'));
    }

    public function orders()
    {
        $user = Auth::user();
        $orders = Order::where('user_id', $user->id)
            ->orWhere('customer_email', $user->email)
            ->with(['items.product', 'items.variant'])
            ->latest()
            ->paginate(10);

        $ordersCount = $orders->total();

        return view('customer.orders', compact('user', 'orders', 'ordersCount'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',
                'regex:/^[a-zA-Z\s]+$/',
            ],
            'phone' => [
                'nullable',
                'digits:10',
                'regex:/^[6-9]\d{9}$/',
            ],
            'address' => [
                'nullable',
                'string',
                'max:255',
            ],
            'city' => [
                'nullable',
                'string',
                'max:100',
                'regex:/^[a-zA-Z\s]+$/',
            ],
            'state' => [
                'nullable',
                'string',
                'max:100',
                'regex:/^[a-zA-Z\s]+$/',
            ],
            'postal_code' => [
                'nullable',
                'digits:6',
                'regex:/^\d{6}$/',
            ],
            'avatar' => [
                'nullable',
                'image',
                'mimes:jpeg,png,jpg,webp',
                'max:2048',
            ],
            'remove_avatar' => [
                'nullable',
                'boolean',
            ],
        ], [
            'name.required' => 'Full name is required.',
            'name.regex' => 'Full name may only contain alphabetic characters and spaces. Numbers and special characters are not allowed.',
            'phone.digits' => 'Phone number must contain exactly 10 digits.',
            'phone.regex' => 'Phone number must be a valid 10-digit number without alphabets or special characters.',
            'city.regex' => 'City may only contain alphabetic characters and spaces. Numbers and special characters are not allowed.',
            'state.regex' => 'State may only contain alphabetic characters and spaces. Numbers and special characters are not allowed.',
            'postal_code.digits' => 'PIN code must contain exactly 6 digits.',
            'postal_code.regex' => 'PIN code must contain exactly 6 digits without any alphabets or special characters.',
            'avatar.image' => 'The profile picture must be an image file.',
            'avatar.mimes' => 'The profile picture must be a JPG, PNG, or WEBP image.',
            'avatar.max' => 'The profile picture size must not exceed 2MB.',
        ]);

        // Handle profile picture update / replacement
        if ($request->hasFile('avatar')) {
            // Delete old avatar from storage if it exists to prevent orphaned files
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
            $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
        } elseif ($request->boolean('remove_avatar')) {
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
            $validated['avatar'] = null;
        }

        unset($validated['remove_avatar']);
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

    public function invoice($orderNumber)
    {
        $user = Auth::user();
        $order = Order::where('order_number', $orderNumber)
            ->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->orWhere('customer_email', $user->email);
            })
            ->with(['items.product', 'items.variant'])
            ->firstOrFail();

        return view('orders.invoice', compact('order'));
    }
}
