@extends('layouts.admin')

@section('title', 'Reports & Business Analytics - Admin')
@section('header_title', 'Reports & Analytics')
@section('header_subtitle', 'Sales reports, revenue velocity, order performance and top-selling traditional pickles')

@section('content')
<div class="space-y-8 max-w-7xl mx-auto">
    {{-- Period Filter Toolbar --}}
    <div class="bg-white rounded-3xl p-6 border border-amber-200/80 shadow-sm space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <span class="text-[10px] uppercase font-bold text-amber-800/60 block">Report Time Period</span>
                <h3 class="font-serif-heading font-bold text-lg text-amber-950">{{ $periodLabel }}</h3>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('admin.reports.index', ['period' => 'today']) }}"
                   class="px-3.5 py-2 rounded-xl text-xs font-bold transition-all {{ $period === 'today' ? 'bg-gradient-to-r from-amber-700 to-amber-900 text-white shadow-xs' : 'bg-amber-50/60 hover:bg-amber-100 text-amber-900 border border-amber-200/80' }}">
                   Today
                </a>
                <a href="{{ route('admin.reports.index', ['period' => 'yesterday']) }}"
                   class="px-3.5 py-2 rounded-xl text-xs font-bold transition-all {{ $period === 'yesterday' ? 'bg-gradient-to-r from-amber-700 to-amber-900 text-white shadow-xs' : 'bg-amber-50/60 hover:bg-amber-100 text-amber-900 border border-amber-200/80' }}">
                   Yesterday
                </a>
                <a href="{{ route('admin.reports.index', ['period' => 'week']) }}"
                   class="px-3.5 py-2 rounded-xl text-xs font-bold transition-all {{ $period === 'week' ? 'bg-gradient-to-r from-amber-700 to-amber-900 text-white shadow-xs' : 'bg-amber-50/60 hover:bg-amber-100 text-amber-900 border border-amber-200/80' }}">
                   Last 7 Days
                </a>
                <a href="{{ route('admin.reports.index', ['period' => 'month']) }}"
                   class="px-3.5 py-2 rounded-xl text-xs font-bold transition-all {{ $period === 'month' ? 'bg-gradient-to-r from-amber-700 to-amber-900 text-white shadow-xs' : 'bg-amber-50/60 hover:bg-amber-100 text-amber-900 border border-amber-200/80' }}">
                   This Month
                </a>
                <a href="{{ route('admin.reports.index', ['period' => 'year']) }}"
                   class="px-3.5 py-2 rounded-xl text-xs font-bold transition-all {{ $period === 'year' ? 'bg-gradient-to-r from-amber-700 to-amber-900 text-white shadow-xs' : 'bg-amber-50/60 hover:bg-amber-100 text-amber-900 border border-amber-200/80' }}">
                   Year to Date
                </a>
                <button type="button" onclick="window.print()" class="px-3.5 py-2 rounded-xl bg-gradient-to-r from-amber-700 to-amber-900 hover:from-amber-800 hover:to-amber-950 text-white text-xs font-bold transition-all flex items-center gap-1.5 shadow-xs">
                    <i class="fa-solid fa-print"></i>
                    <span>Print Report</span>
                </button>
            </div>
        </div>

        {{-- Custom Date Form --}}
        <form method="GET" action="{{ route('admin.reports.index') }}" class="pt-3 border-t border-amber-100 flex flex-wrap items-center gap-3 text-xs">
            <input type="hidden" name="period" value="custom">
            <span class="font-bold text-amber-950">Custom Date Range:</span>
            <div class="flex items-center gap-2">
                <input type="date" name="start_date" value="{{ $startDate->format('Y-m-d') }}" class="bg-amber-50/40 border border-amber-200/80 rounded-xl px-3 py-1.5 text-xs font-semibold text-slate-800">
                <span class="text-amber-800/60">to</span>
                <input type="date" name="end_date" value="{{ $endDate->format('Y-m-d') }}" class="bg-amber-50/40 border border-amber-200/80 rounded-xl px-3 py-1.5 text-xs font-semibold text-slate-800">
            </div>
            <button type="submit" class="px-3.5 py-1.5 bg-gradient-to-r from-amber-700 to-amber-900 hover:from-amber-800 text-white rounded-xl font-bold transition-colors shadow-xs">
                Apply Range
            </button>
        </form>
    </div>

    {{-- 5 High-Impact Metric Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
        {{-- Total Revenue --}}
        <div class="bg-white rounded-3xl p-5 border border-amber-200/80 shadow-xs space-y-2">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-bold uppercase tracking-wider text-amber-800/60">Total Revenue</span>
                <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-200 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-indian-rupee-sign"></i>
                </div>
            </div>
            <p class="text-2xl font-extrabold text-amber-950 font-mono">₹{{ number_format($totalRevenue, 2) }}</p>
            <p class="text-[10px] text-emerald-700 font-semibold">From paid orders in period</p>
        </div>

        {{-- Total Orders --}}
        <div class="bg-white rounded-3xl p-5 border border-amber-200/80 shadow-xs space-y-2">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-bold uppercase tracking-wider text-amber-800/60">Total Orders</span>
                <div class="w-8 h-8 rounded-xl bg-amber-100 text-amber-800 border border-amber-300 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-receipt"></i>
                </div>
            </div>
            <p class="text-2xl font-extrabold text-amber-950">{{ $totalOrders }}</p>
            <p class="text-[10px] text-amber-800/70">{{ $deliveredOrders }} successfully delivered</p>
        </div>

        {{-- Average Order Value --}}
        <div class="bg-white rounded-3xl p-5 border border-amber-200/80 shadow-xs space-y-2">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-bold uppercase tracking-wider text-amber-800/60">Average Order Value</span>
                <div class="w-8 h-8 rounded-xl bg-spice-100 text-spice-700 border border-orange-200 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-calculator"></i>
                </div>
            </div>
            <p class="text-2xl font-extrabold text-amber-950 font-mono">₹{{ number_format($aov, 2) }}</p>
            <p class="text-[10px] text-amber-800/70">Per paying transaction</p>
        </div>

        {{-- Pending Fulfillment --}}
        <div class="bg-white rounded-3xl p-5 border border-amber-200/80 shadow-xs space-y-2">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-bold uppercase tracking-wider text-amber-800/60">Pending Orders</span>
                <div class="w-8 h-8 rounded-xl bg-amber-100 text-amber-900 border border-amber-300 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                </div>
            </div>
            <p class="text-2xl font-extrabold text-amber-950">{{ $pendingOrders }}</p>
            <p class="text-[10px] text-amber-700 font-semibold">Requires kitchen / dispatch</p>
        </div>

        {{-- Returns & Cancellations --}}
        <div class="bg-white rounded-3xl p-5 border border-amber-200/80 shadow-xs space-y-2">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-bold uppercase tracking-wider text-amber-800/60">Cancelled / Returned</span>
                <div class="w-8 h-8 rounded-xl bg-rose-50 text-rose-700 border border-rose-200 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-rotate-left"></i>
                </div>
            </div>
            <p class="text-2xl font-extrabold text-rose-700">{{ $cancelledOrders + $returnedOrders }}</p>
            <p class="text-[10px] text-rose-600 font-semibold">₹{{ number_format($refundedAmount, 2) }} refunded</p>
        </div>
    </div>

    {{-- 2-Column: Best-Selling Products & Payment Mode Share --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        {{-- Best-Selling Products (8 cols) --}}
        <div class="lg:col-span-8 bg-white rounded-3xl border border-amber-200/80 shadow-sm p-6 sm:p-8 space-y-6">
            <div class="flex items-center justify-between border-b border-amber-100 pb-4">
                <div>
                    <h3 class="font-serif-heading font-bold text-lg text-amber-950">Best-Selling Products</h3>
                    <p class="text-xs text-amber-800/70">Top-performing sun-cured pickles and royal mukhwas by units ordered</p>
                </div>
                <span class="text-xs font-bold text-amber-800">Ranked by Volume</span>
            </div>

            @if($topProducts->isEmpty())
                <div class="py-12 text-center text-slate-400 text-xs">
                    No product sales recorded in this time period.
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-[#FFFDF9] border-b border-amber-200/60 text-amber-900/60 uppercase text-[10px] font-bold tracking-wider">
                            <tr>
                                <th class="p-3 text-center w-12">#</th>
                                <th class="p-3">Product Name</th>
                                <th class="p-3 text-center">Units Sold</th>
                                <th class="p-3 text-right">Revenue Generated</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-amber-100/60">
                            @foreach($topProducts as $idx => $prod)
                                <tr class="hover:bg-amber-50/30 transition-colors">
                                    <td class="p-3 text-center font-bold text-slate-400">
                                        @if($idx === 0)
                                            <span class="w-6 h-6 rounded-full bg-amber-500 text-amber-950 font-extrabold inline-flex items-center justify-center text-[10px] shadow-xs">1</span>
                                        @elseif($idx === 1)
                                            <span class="w-6 h-6 rounded-full bg-amber-200 text-amber-950 font-extrabold inline-flex items-center justify-center text-[10px]">2</span>
                                        @elseif($idx === 2)
                                            <span class="w-6 h-6 rounded-full bg-amber-100 text-amber-900 border border-amber-300 font-extrabold inline-flex items-center justify-center text-[10px]">3</span>
                                        @else
                                            {{ $idx + 1 }}
                                        @endif
                                    </td>
                                    <td class="p-3 font-bold text-slate-900">
                                        {{ $prod->product_name }}
                                    </td>
                                    <td class="p-3 text-center font-bold text-amber-900">
                                        {{ $prod->total_qty }} jars
                                    </td>
                                    <td class="p-3 text-right font-mono font-extrabold text-amber-950">
                                        ₹{{ number_format($prod->total_revenue, 2) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        {{-- Payment Methods Share (4 cols) --}}
        <div class="lg:col-span-4 bg-white rounded-3xl border border-amber-200/80 shadow-sm p-6 space-y-6">
            <div class="border-b border-amber-100 pb-4">
                <h3 class="font-serif-heading font-bold text-base text-amber-950">Payment Breakdown</h3>
                <p class="text-xs text-amber-800/70">Method distribution across orders</p>
            </div>

            @if($paymentDistribution->isEmpty())
                <div class="py-12 text-center text-slate-400 text-xs">No orders in period.</div>
            @else
                <div class="space-y-4">
                    @foreach($paymentDistribution as $pay)
                        @php
                            $percentage = $totalOrders > 0 ? round(($pay->count / $totalOrders) * 100, 1) : 0;
                            $label = match($pay->payment_method) {
                                'cod' => 'Cash On Delivery (COD)',
                                'online_upi' => 'UPI & Instant QR',
                                'card' => 'Credit / Debit Cards',
                                default => strtoupper($pay->payment_method),
                            };
                        @endphp
                        <div class="p-4 bg-amber-50/40 border border-amber-200/70 rounded-2xl space-y-2">
                            <div class="flex items-center justify-between text-xs">
                                <span class="font-bold text-slate-800">{{ $label }}</span>
                                <span class="font-mono font-extrabold text-amber-950">{{ $percentage }}%</span>
                            </div>
                            <div class="w-full bg-amber-100 rounded-full h-2 overflow-hidden">
                                <div class="bg-gradient-to-r from-amber-600 to-amber-800 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                            </div>
                            <div class="flex justify-between text-[11px] text-amber-800/70 pt-1">
                                <span>{{ $pay->count }} Orders</span>
                                <span class="font-mono font-bold text-amber-950">₹{{ number_format($pay->total, 2) }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- Daily Sales Breakdown Table --}}
    <div class="bg-white rounded-3xl border border-amber-200/80 shadow-sm p-6 sm:p-8 space-y-6">
        <div class="flex items-center justify-between border-b border-amber-100 pb-4">
            <div>
                <h3 class="font-serif-heading font-bold text-lg text-amber-950">Timeline Performance Breakdown</h3>
                <p class="text-xs text-amber-800/70">Daily sales velocity, order count, and delivery fee receipts</p>
            </div>
            <span class="text-xs text-amber-800/60 font-semibold">{{ $dailyBreakdown->count() }} active days</span>
        </div>

        @if($dailyBreakdown->isEmpty())
            <div class="py-12 text-center text-slate-400 text-xs">No daily transactions to display.</div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-[#FFFDF9] border-b border-amber-200/60 text-amber-900/60 uppercase text-[10px] font-bold tracking-wider">
                        <tr>
                            <th class="p-3 pl-6">Date</th>
                            <th class="p-3 text-center">Orders Placed</th>
                            <th class="p-3 text-right">Shipping Collected</th>
                            <th class="p-3 pr-6 text-right">Gross Sales Volume</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-amber-100/60">
                        @foreach($dailyBreakdown as $row)
                            <tr class="hover:bg-amber-50/30 transition-colors">
                                <td class="p-3 pl-6 font-bold text-slate-900">
                                    {{ \Carbon\Carbon::parse($row->order_date)->format('D, M d, Y') }}
                                </td>
                                <td class="p-3 text-center font-bold text-slate-800">
                                    {{ $row->orders_count }}
                                </td>
                                <td class="p-3 text-right font-mono text-slate-600">
                                    ₹{{ number_format($row->total_shipping, 2) }}
                                </td>
                                <td class="p-3 pr-6 text-right font-mono font-extrabold text-amber-950 text-sm">
                                    ₹{{ number_format($row->total_sales, 2) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection

