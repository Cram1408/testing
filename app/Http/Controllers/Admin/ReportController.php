<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->get('period', 'month');
        $now = Carbon::now();

        // Calculate start and end date based on selected period
        switch ($period) {
            case 'today':
                $startDate = $now->copy()->startOfDay();
                $endDate = $now->copy()->endOfDay();
                $periodLabel = 'Today (' . $now->format('d M, Y') . ')';
                break;
            case 'yesterday':
                $startDate = $now->copy()->subDay()->startOfDay();
                $endDate = $now->copy()->subDay()->endOfDay();
                $periodLabel = 'Yesterday (' . $now->copy()->subDay()->format('d M, Y') . ')';
                break;
            case 'week':
                $startDate = $now->copy()->subDays(6)->startOfDay();
                $endDate = $now->copy()->endOfDay();
                $periodLabel = 'Last 7 Days (' . $startDate->format('d M') . ' – ' . $endDate->format('d M, Y') . ')';
                break;
            case 'year':
                $startDate = $now->copy()->startOfYear();
                $endDate = $now->copy()->endOfYear();
                $periodLabel = 'Year to Date (' . $now->year . ')';
                break;
            case 'custom':
                $startDate = $request->filled('start_date') ? Carbon::parse($request->get('start_date'))->startOfDay() : $now->copy()->startOfMonth();
                $endDate = $request->filled('end_date') ? Carbon::parse($request->get('end_date'))->endOfDay() : $now->copy()->endOfDay();
                $periodLabel = $startDate->format('d M, Y') . ' to ' . $endDate->format('d M, Y');
                break;
            case 'month':
            default:
                $period = 'month';
                $startDate = $now->copy()->startOfMonth();
                $endDate = $now->copy()->endOfMonth();
                $periodLabel = 'This Month (' . $now->format('F Y') . ')';
                break;
        }

        // 1. Orders Query in range
        $ordersQuery = Order::whereBetween('created_at', [$startDate, $endDate]);

        $totalOrders = (clone $ordersQuery)->count();
        $totalRevenue = (clone $ordersQuery)->where('payment_status', 'paid')->sum('total_amount');
        $deliveredOrders = (clone $ordersQuery)->where('order_status', 'delivered')->count();
        $pendingOrders = (clone $ordersQuery)->whereIn('order_status', ['pending', 'processing'])->count();
        $cancelledOrders = (clone $ordersQuery)->where('order_status', 'cancelled')->count();
        $returnedOrders = (clone $ordersQuery)->where('order_status', 'returned')->count();
        $refundedAmount = (clone $ordersQuery)->where('payment_status', 'refunded')->sum('total_amount');

        $aov = $totalOrders > 0 ? ($totalRevenue / max(1, (clone $ordersQuery)->where('payment_status', 'paid')->count())) : 0;

        // 2. Best-Selling Products in this timeframe
        $orderIds = (clone $ordersQuery)->pluck('id');
        $topProducts = OrderItem::whereIn('order_id', $orderIds)
            ->select('product_name', 'product_id', DB::raw('SUM(quantity) as total_qty'), DB::raw('SUM(total_price) as total_revenue'))
            ->groupBy('product_name', 'product_id')
            ->orderByDesc('total_qty')
            ->take(10)
            ->get();

        // 3. Payment Method Distribution
        $paymentDistribution = (clone $ordersQuery)
            ->select('payment_method', DB::raw('COUNT(*) as count'), DB::raw('SUM(total_amount) as total'))
            ->groupBy('payment_method')
            ->get();

        // 4. Daily Sales Series Breakdown
        $dailyBreakdown = (clone $ordersQuery)
            ->select(
                DB::raw('DATE(created_at) as order_date'),
                DB::raw('COUNT(*) as orders_count'),
                DB::raw('SUM(total_amount) as total_sales'),
                DB::raw('SUM(shipping_fee) as total_shipping')
            )
            ->groupBy('order_date')
            ->orderBy('order_date', 'desc')
            ->get();

        return view('admin.reports.index', compact(
            'period',
            'periodLabel',
            'startDate',
            'endDate',
            'totalOrders',
            'totalRevenue',
            'deliveredOrders',
            'pendingOrders',
            'cancelledOrders',
            'returnedOrders',
            'refundedAmount',
            'aov',
            'topProducts',
            'paymentDistribution',
            'dailyBreakdown'
        ));
    }
}

