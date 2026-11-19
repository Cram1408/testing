@extends('layouts.admin')

@section('title', 'Admin Dashboard')
@section('header_title', 'Store Overview')
@section('header_subtitle', 'Live revenue, inventory alerts, and order processing queue')

@section('content')
<div class="space-y-8">
    {{-- Super Admin Welcome & Performance Pulse --}}
    <div class="bg-white rounded-3xl p-6 border border-amber-200/80 shadow-sm flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-amber-600 to-amber-800 text-white flex items-center justify-center text-2xl shadow-md shadow-amber-800/20 shrink-0 border border-amber-400/40">
                <i class="fa-solid fa-gauge-high"></i>
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h2 class="font-serif-heading text-xl font-bold text-amber-950">Welcome, {{ auth()->user()->name }}</h2>
                    @if(auth()->user()->isSuperAdmin())
                        <span class="px-2.5 py-0.5 rounded-full bg-amber-100 text-amber-900 font-extrabold text-[10px] uppercase tracking-wider border border-amber-300">
                            <i class="fa-solid fa-crown text-[9px] mr-1 text-amber-700"></i> Super Admin
                        </span>
                    @else
                        <span class="px-2.5 py-0.5 rounded-full bg-amber-50 text-amber-900 font-bold text-[10px] border border-amber-200">
                            {{ auth()->user()->role_title }}
                        </span>
                    @endif
                </div>
                <p class="text-xs text-amber-800/70 mt-1">Here is your live e-commerce performance overview for today.</p>
            </div>
        </div>

        {{-- Today & This Month Pulse Badges --}}
        <div class="flex flex-wrap items-center gap-3">
            <div class="px-4 py-2 bg-amber-50/80 rounded-2xl border border-amber-200 text-xs text-amber-900 shadow-xs">
                <span class="text-[10px] text-amber-700 uppercase font-bold block">Today's Sales</span>
                <span class="font-mono font-extrabold text-sm text-amber-950">₹{{ number_format($todayRevenue, 2) }}</span>
                <span class="text-[10px] text-amber-700/80 font-medium ml-1">({{ $todayOrders }} orders)</span>
            </div>

            <div class="px-4 py-2 bg-amber-50/40 rounded-2xl border border-amber-200/70 text-xs text-slate-800 shadow-xs">
                <span class="text-[10px] text-amber-800/60 uppercase font-bold block">This Month ({{ now()->format('M') }})</span>
                <span class="font-mono font-extrabold text-sm text-amber-950">₹{{ number_format($monthRevenue, 2) }}</span>
            </div>

            @if(auth()->user()->isSuperAdmin())
                <a href="{{ route('admin.reports.index') }}" class="px-3.5 py-2.5 bg-gradient-to-r from-amber-700 to-amber-900 hover:from-amber-800 hover:to-amber-950 text-white rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 shadow-sm shadow-amber-900/20">
                    <i class="fa-solid fa-chart-line text-xs"></i>
                    <span>Full Reports</span>
                </a>
            @endif
        </div>
    </div>
    
    <!-- KPI Metrics Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Revenue Card -->
        <div class="bg-white p-6 rounded-3xl border border-amber-200/70 shadow-sm hover:border-amber-300 transition-all flex items-center justify-between">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-amber-800/60">Total Sales</span>
                <h3 class="text-2xl font-extrabold text-amber-950 mt-1">₹{{ number_format($totalRevenue, 2) }}</h3>
                <span class="text-[11px] text-emerald-700 font-bold mt-1 inline-block"><i class="fa-solid fa-arrow-trend-up mr-1"></i> Paid Orders</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-amber-100/70 text-amber-800 border border-amber-200 flex items-center justify-center text-xl shadow-xs">
                <i class="fa-solid fa-indian-rupee-sign"></i>
            </div>
        </div>

        <!-- Orders Card -->
        <div class="bg-white p-6 rounded-3xl border border-amber-200/70 shadow-sm hover:border-amber-300 transition-all flex items-center justify-between">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-amber-800/60">Total Orders</span>
                <h3 class="text-2xl font-extrabold text-amber-950 mt-1">{{ $totalOrders }}</h3>
                <span class="text-[11px] text-amber-700 font-bold mt-1 inline-block">{{ $pendingOrders }} In Fulfillment</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-spice-100 text-spice-700 border border-orange-200 flex items-center justify-center text-xl shadow-xs">
                <i class="fa-solid fa-receipt"></i>
            </div>
        </div>

        <!-- Products Card -->
        <div class="bg-white p-6 rounded-3xl border border-amber-200/70 shadow-sm hover:border-amber-300 transition-all flex items-center justify-between">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-amber-800/60">Active Products</span>
                <h3 class="text-2xl font-extrabold text-amber-950 mt-1">{{ $totalProducts }}</h3>
                <span class="text-[11px] text-amber-700 font-semibold mt-1 inline-block">{{ count($categories) }} Categories</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-700 border border-emerald-200 flex items-center justify-center text-xl shadow-xs">
                <i class="fa-solid fa-boxes-stacked"></i>
            </div>
        </div>

        <!-- Customers Card -->
        <div class="bg-white p-6 rounded-3xl border border-amber-200/70 shadow-sm hover:border-amber-300 transition-all flex items-center justify-between">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-amber-800/60">Registered Buyers</span>
                <h3 class="text-2xl font-extrabold text-amber-950 mt-1">{{ $totalCustomers }}</h3>
                <span class="text-[11px] text-amber-800 font-semibold mt-1 inline-block">Loyal Customers</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-900 border border-amber-200 flex items-center justify-center text-xl shadow-xs">
                <i class="fa-solid fa-users"></i>
            </div>
        </div>
    </div>

    <!-- Quick Management Action Bar -->
    <div class="bg-gradient-to-r from-amber-700 via-amber-800 to-amber-900 rounded-3xl p-6 text-white shadow-xl flex flex-col md:flex-row items-center justify-between gap-4 border border-amber-600/40">
        <div class="space-y-1 text-center md:text-left">
            <h3 class="font-serif-heading font-bold text-lg text-white">Quick Store Operations</h3>
            <p class="text-xs text-amber-200/80">Add fresh batches of pickles, adjust glass jar inventory, or fulfill orders.</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <a href="{{ route('admin.products.create') }}" class="px-4 py-2.5 rounded-xl bg-white text-amber-950 font-bold text-xs shadow-md hover:bg-amber-50 transition-colors flex items-center gap-1.5">
                <i class="fa-solid fa-plus text-amber-700"></i>
                <span>Add Product</span>
            </a>
            <a href="{{ route('admin.categories.create') }}" class="px-4 py-2.5 rounded-xl bg-amber-600 hover:bg-amber-500 text-white font-bold text-xs shadow-md transition-colors flex items-center gap-1.5 border border-amber-400/30">
                <i class="fa-solid fa-folder-plus"></i>
                <span>Add Category</span>
            </a>
            <a href="{{ route('admin.inventory.index') }}" class="px-4 py-2.5 rounded-xl bg-[#24130A] hover:bg-[#1A0C06] text-amber-200 font-bold text-xs border border-amber-600/60 transition-colors flex items-center gap-1.5 shadow-sm">
                <i class="fa-solid fa-warehouse text-amber-400"></i>
                <span>Quick Stock Adjuster</span>
            </a>
        </div>
    </div>

    <!-- Grid: Low Stock Alert & Recent Orders -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- Low Stock Alert (5 cols) -->
        <div class="lg:col-span-5 bg-white rounded-3xl p-6 border border-amber-200/80 shadow-sm space-y-4">
            <div class="flex items-center justify-between border-b border-amber-100 pb-3">
                <h3 class="font-bold text-sm text-amber-950 flex items-center gap-2">
                    <i class="fa-solid fa-triangle-exclamation text-amber-600"></i>
                    <span>Low Stock Attention</span>
                </h3>
                <a href="{{ route('admin.inventory.index') }}" class="text-[11px] font-bold text-amber-700 hover:text-amber-900 hover:underline">View All Inventory</a>
            </div>

            @if($lowStockVariants->isEmpty())
                <div class="py-8 text-center text-xs text-slate-400">
                    <i class="fa-solid fa-circle-check text-emerald-500 text-xl mb-1 block"></i>
                    All product variants have healthy stock levels!
                </div>
            @else
                <div class="divide-y divide-amber-100">
                    @foreach($lowStockVariants as $var)
                        <div class="py-3 first:pt-0 flex items-center justify-between gap-3">
                            <div class="min-w-0">
                                <h4 class="text-xs font-bold text-slate-900 truncate">{{ $var->product->name }}</h4>
                                <span class="text-[11px] font-semibold text-amber-800/70">{{ $var->weight_value }} • SKU: {{ $var->sku }}</span>
                            </div>
                            <div class="flex items-center gap-2 shrink-0">
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-extrabold {{ $var->stock_quantity <= 10 ? 'bg-rose-100 text-rose-800 border border-rose-200' : 'bg-amber-100 text-amber-800 border border-amber-200' }}">
                                    {{ $var->stock_quantity }} left
                                </span>
                                <form action="{{ route('admin.inventory.update', $var->id) }}" method="POST" class="flex items-center gap-1">
                                    @csrf
                                    @method('PUT')
                                    <input type="number" name="stock_quantity" value="{{ $var->stock_quantity + 20 }}" class="w-14 p-1 text-center text-xs font-bold bg-amber-50/50 border border-amber-200 rounded-lg text-slate-900 focus:ring-1 focus:ring-amber-500">
                                    <button type="submit" class="p-1 px-2 text-[10px] bg-amber-700 hover:bg-amber-800 text-white font-bold rounded-lg transition-colors" title="Restock +20">
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
        <div class="lg:col-span-7 bg-white rounded-3xl p-6 border border-amber-200/80 shadow-sm space-y-4">
            <div class="flex items-center justify-between border-b border-amber-100 pb-3">
                <h3 class="font-bold text-sm text-amber-950 flex items-center gap-2">
                    <i class="fa-solid fa-clock-rotate-left text-amber-700"></i>
                    <span>Recent Customer Orders</span>
                </h3>
                <a href="{{ route('admin.orders.index') }}" class="text-[11px] font-bold text-amber-700 hover:text-amber-900 hover:underline">View All Orders</a>
            </div>

            @if($recentOrders->isEmpty())
                <div class="py-8 text-center text-xs text-slate-400">
                    No orders placed yet.
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="text-amber-900/60 border-b border-amber-100 uppercase text-[10px] font-bold tracking-wider">
                                <th class="pb-2.5">Order</th>
                                <th class="pb-2.5">Customer</th>
                                <th class="pb-2.5">Status</th>
                                <th class="pb-2.5">Total</th>
                                <th class="pb-2.5 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-amber-100/60">
                            @foreach($recentOrders as $order)
                                <tr class="hover:bg-amber-50/40 transition-colors">
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
                                    <td class="py-3 font-extrabold text-amber-950">₹{{ number_format($order->total_amount, 2) }}</td>
                                    <td class="py-3 text-right">
                                        <a href="{{ route('admin.orders.show', $order->id) }}" class="p-1.5 px-3 rounded-xl bg-amber-50 hover:bg-amber-100 text-amber-900 border border-amber-200/80 font-bold text-xs inline-flex items-center gap-1 transition-colors">
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
