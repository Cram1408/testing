<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalRevenue = Order::where('payment_status', 'paid')->sum('total_amount');
        $totalOrders = Order::count();
        $pendingOrders = Order::whereIn('order_status', ['pending', 'processing'])->count();
        $totalProducts = Product::count();
        $totalCustomers = User::where('role', 'customer')->count();

        // Low stock threshold: <= 20 units
        $lowStockVariants = ProductVariant::with('product')
            ->where('stock_quantity', '<=', 25)
            ->orderBy('stock_quantity', 'asc')
            ->take(6)
            ->get();

        $recentOrders = Order::with('items')
            ->latest()
            ->take(6)
            ->get();

        $categories = Category::withCount('products')->get();

        return view('admin.dashboard', compact(
            'totalRevenue',
            'totalOrders',
            'pendingOrders',
            'totalProducts',
            'totalCustomers',
            'lowStockVariants',
            'recentOrders',
            'categories'
        ));
    }
}
