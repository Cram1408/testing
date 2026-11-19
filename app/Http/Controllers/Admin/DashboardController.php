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
        $deliveredOrders = Order::where('order_status', 'delivered')->count();
        $cancelledOrders = Order::where('order_status', 'cancelled')->count();
        $totalProducts = Product::count();
        $totalCustomers = User::where('role', 'customer')->count();
        $totalAdmins = User::whereIn('role', ['admin', 'super_admin'])->count();

        // Today & Monthly Pulse
        $todayRevenue = Order::where('payment_status', 'paid')->whereDate('created_at', today())->sum('total_amount');
        $todayOrders = Order::whereDate('created_at', today())->count();
        $monthRevenue = Order::where('payment_status', 'paid')->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->sum('total_amount');

        // Low stock threshold: <= 25 units
        $lowStockVariants = ProductVariant::with('product')
            ->where('stock_quantity', '<=', 25)
            ->orderBy('stock_quantity', 'asc')
            ->take(6)
            ->get();

        $recentOrders = Order::with('items')
            ->latest()
            ->take(8)
            ->get();

        $categories = Category::withCount('products')->get();

        return view('admin.dashboard', compact(
            'totalRevenue',
            'totalOrders',
            'pendingOrders',
            'deliveredOrders',
            'cancelledOrders',
            'totalProducts',
            'totalCustomers',
            'totalAdmins',
            'todayRevenue',
            'todayOrders',
            'monthRevenue',
            'lowStockVariants',
            'recentOrders',
            'categories'
        ));
    }
}
