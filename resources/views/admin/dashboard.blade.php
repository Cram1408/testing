@extends('layouts.admin')

@section('title', 'Admin Dashboard')
@section('header_title', 'Store Overview')
@section('header_subtitle', 'Live revenue, inventory alerts, and order processing queue')

@section('content')
<div class="space-y-8">
    
    <!-- KPI Metrics Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Revenue Card -->
        <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Sales</span>
                <h3 class="text-2xl font-extrabold text-slate-900 mt-1">₹{{ number_format($totalRevenue, 2) }}</h3>
                <span class="text-[11px] text-emerald-600 font-bold mt-1 inline-block"><i class="fa-solid fa-arrow-trend-up mr-1"></i> Paid Orders</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-700 flex items-center justify-center text-xl">
                <i class="fa-solid fa-indian-rupee-sign"></i>
            </div>
        </div>

        <!-- Orders Card -->
        <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Orders</span>
                <h3 class="text-2xl font-extrabold text-slate-900 mt-1">{{ $totalOrders }}</h3>
                <span class="text-[11px] text-amber-600 font-bold mt-1 inline-block">{{ $pendingOrders }} In Fulfillment</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-receipt"></i>
            </div>
        </div>

        <!-- Products Card -->
        <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Active Products</span>
                <h3 class="text-2xl font-extrabold text-slate-900 mt-1">{{ $totalProducts }}</h3>
                <span class="text-[11px] text-slate-500 font-semibold mt-1 inline-block">{{ count($categories) }} Categories</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-boxes-stacked"></i>
            </div>
        </div>

        <!-- Customers Card -->
        <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Registered Buyers</span>
                <h3 class="text-2xl font-extrabold text-slate-900 mt-1">{{ $totalCustomers }}</h3>
                <span class="text-[11px] text-slate-500 font-semibold mt-1 inline-block">Loyal Customers</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-users"></i>
            </div>
        </div>
    </div>

    <!-- Quick Management Action Bar -->
    <div class="bg-gradient-to-r from-amber-700 via-amber-800 to-amber-900 rounded-3xl p-6 text-white shadow-xl flex flex-col md:flex-row items-center justify-between gap-4">
        <div class="space-y-1 text-center md:text-left">
            <h3 class="font-bold text-lg">Quick Store Operations</h3>
            <p class="text-xs text-amber-200/80">Add fresh batches of pickles, adjust glass jar inventory, or fulfill orders.</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <a href="{{ route('admin.products.create') }}" class="px-4 py-2.5 rounded-xl bg-white text-amber-950 font-bold text-xs shadow-md hover:bg-amber-50 transition-colors flex items-center gap-1.5">
                <i class="fa-solid fa-plus text-amber-700"></i>
                <span>Add Product</span>
            </a>
            <a href="{{ route('admin.categories.create') }}" class="px-4 py-2.5 rounded-xl bg-amber-600 hover:bg-amber-500 text-white font-bold text-xs shadow-md transition-colors flex items-center gap-1.5">
                <i class="fa-solid fa-folder-plus"></i>
                <span>Add Category</span>
            </a>
            <a href="{{ route('admin.inventory.index') }}" class="px-4 py-2.5 rounded-xl bg-amber-950/70 hover:bg-amber-950 text-amber-200 font-bold text-xs border border-amber-600 transition-colors flex items-center gap-1.5">
                <i class="fa-solid fa-warehouse"></i>
                <span>Quick Stock Adjuster</span>
            </a>
        </div>
    </div>

    <!-- Grid: Low Stock Alert & Recent Orders -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- Low Stock Alert (4 cols) -->
        <div class="lg:col-span-5 bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="font-bold text-sm text-slate-900 flex items-center gap-2">
                    <i class="fa-solid fa-triangle-exclamation text-amber-500"></i>
                    <span>Low Stock Attention</span>
                </h3>
                <a href="{{ route('admin.inventory.index') }}" class="text-[11px] font-bold text-amber-700 hover:underline">View All Inventory</a>
            </div>

            @if($lowStockVariants->isEmpty())
                <div class="py-8 text-center text-xs text-slate-400">
                    <i class="fa-solid fa-circle-check text-emerald-500 text-xl mb-1 block"></i>
                    All product variants have healthy stock levels!
                </div>
            @else
                <div class="divide-y divide-slate-100">
                    @foreach($lowStockVariants as $var)
                        <div class="py-3 first:pt-0 flex items-center justify-between gap-3">
                            <div class="min-w-0">
                                <h4 class="text-xs font-bold text-slate-900 truncate">{{ $var->product->name }}</h4>
                                <span class="text-[11px] font-semibold text-slate-500">{{ $var->weight_value }} • SKU: {{ $var->sku }}</span>
                            </div>
                            <div class="flex items-center gap-2 shrink-0">
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-extrabold {{ $var->stock_quantity <= 10 ? 'bg-rose-100 text-rose-800' : 'bg-amber-100 text-amber-800' }}">
                                    {{ $var->stock_quantity }} left
                                </span>
                                <form action="{{ route('admin.inventory.update', $var->id) }}" method="POST" class="flex items-center gap-1">
                                    @csrf
                                    @method('PUT')
                                    <input type="number" name="stock_quantity" value="{{ $var->stock_quantity + 20 }}" class="w-14 p-1 text-center text-xs font-bold bg-slate-100 border border-slate-300 rounded-lg">
                                    <button type="submit" class="p-1 px-2 text-[10px] bg-slate-800 hover:bg-slate-900 text-white font-bold rounded-lg" title="Restock +20">
                                        <i class="fa-solid fa-plus"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Recent Orders (7 cols) -->
        <div class="lg:col-span-7 bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="font-bold text-sm text-slate-900 flex items-center gap-2">
                    <i class="fa-solid fa-clock-rotate-left text-amber-700"></i>
                    <span>Recent Customer Orders</span>
                </h3>
                <a href="{{ route('admin.orders.index') }}" class="text-[11px] font-bold text-amber-700 hover:underline">View All Orders</a>
            </div>

            @if($recentOrders->isEmpty())
                <div class="py-8 text-center text-xs text-slate-400">
                    No orders placed yet.
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="text-slate-400 border-b border-slate-100 uppercase text-[10px] font-bold tracking-wider">
                                <th class="pb-2.5">Order</th>
                                <th class="pb-2.5">Customer</th>
                                <th class="pb-2.5">Status</th>
                                <th class="pb-2.5">Total</th>
                                <th class="pb-2.5 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($recentOrders as $order)
                                <tr>
                                    <td class="py-3 font-mono font-bold text-amber-900">#{{ $order->order_number }}</td>
                                    <td class="py-3">
                                        <p class="font-bold text-slate-800">{{ $order->customer_name }}</p>
                                        <p class="text-[10px] text-slate-400">{{ $order->created_at->diffForHumans() }}</p>
                                    </td>
                                    <td class="py-3">
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase border {{ $order->status_badge }}">
                                            {{ ucfirst($order->order_status) }}
                                        </span>
                                    </td>
                                    <td class="py-3 font-extrabold text-slate-900">₹{{ number_format($order->total_amount, 2) }}</td>
                                    <td class="py-3 text-right">
                                        <a href="{{ route('admin.orders.show', $order->id) }}" class="p-1.5 px-3 rounded-lg bg-amber-50 hover:bg-amber-100 text-amber-900 font-bold text-xs inline-flex items-center gap-1 transition-colors">
                                            <span>Manage</span>
                                            <i class="fa-solid fa-chevron-right text-[10px]"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

    </div>

</div>
@endsection
