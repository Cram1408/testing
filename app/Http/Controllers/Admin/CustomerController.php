<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'customer')->withCount('orders')->withSum('orders', 'total_amount');

        if ($request->filled('q')) {
            $search = $request->get('q');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%");
            });
        }

        $customers = $query->latest()->paginate(12)->withQueryString();

        return view('admin.customers.index', compact('customers'));
    }

    public function show(User $customer)
    {
        $orders = $customer->orders()->with('items.product')->latest()->paginate(8);
        $totalSpent = $customer->orders()->where('payment_status', 'paid')->sum('total_amount');
        $isDeactivated = $customer->is_deactivated;

        return view('admin.customers.show', compact('customer', 'orders', 'totalSpent', 'isDeactivated'));
    }

    public function toggleStatus(User $customer)
    {
        if ($customer->is_deactivated) {
            \App\Services\AdminRoleService::activate($customer->id);
            $msg = "Customer {$customer->name} has been reactivated.";
        } else {
            \App\Services\AdminRoleService::deactivate($customer->id, 'Deactivated by administrator.');
            $msg = "Customer {$customer->name} has been deactivated.";
        }

        return back()->with('success', $msg);
    }
}
