@extends('layouts.admin')

@section('title', 'Orders & Fulfillment - Admin')
@section('header_title', 'Order Fulfillment Queue')
@section('header_subtitle', 'Process customer pickle and mukhwas orders, dispatch tracking & status updates')

@section('content')
<div class="space-y-6">

    <!-- Status Filter Pills -->
    <div class="flex flex-wrap items-center gap-2">
        <a href="{{ route('admin.orders.index', array_merge(request()->except('status', 'page'), ['status' => 'all'])) }}"
           class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all {{ (!request('status') || request('status') === 'all') ? 'bg-amber-600 text-white shadow-xs' : 'bg-white text-slate-700 border border-slate-200 hover:bg-slate-50' }}">
            All Orders ({{ $statusCounts['all'] }})
        </a>
        <a href="{{ route('admin.orders.index', array_merge(request()->except('page'), ['status' => 'pending'])) }}"
           class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all {{ request('status') === 'pending' ? 'bg-amber-600 text-white shadow-xs' : 'bg-white text-slate-700 border border-slate-200 hover:bg-slate-50' }}">
            Pending ({{ $statusCounts['pending'] }})
        </a>
        <a href="{{ route('admin.orders.index', array_merge(request()->except('page'), ['status' => 'processing'])) }}"
           class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all {{ request('status') === 'processing' ? 'bg-amber-600 text-white shadow-xs' : 'bg-white text-slate-700 border border-slate-200 hover:bg-slate-50' }}">
            In Kitchen / Packing ({{ $statusCounts['processing'] }})
        </a>
        <a href="{{ route('admin.orders.index', array_merge(request()->except('page'), ['status' => 'shipped'])) }}"
           class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all {{ request('status') === 'shipped' ? 'bg-amber-600 text-white shadow-xs' : 'bg-white text-slate-700 border border-slate-200 hover:bg-slate-50' }}">
            Shipped ({{ $statusCounts['shipped'] }})
        </a>
        <a href="{{ route('admin.orders.index', array_merge(request()->except('page'), ['status' => 'delivered'])) }}"
           class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all {{ request('status') === 'delivered' ? 'bg-amber-600 text-white shadow-xs' : 'bg-white text-slate-700 border border-slate-200 hover:bg-slate-50' }}">
            Delivered ({{ $statusCounts['delivered'] }})
        </a>
        <a href="{{ route('admin.orders.index', array_merge(request()->except('page'), ['status' => 'cancelled'])) }}"
           class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all {{ request('status') === 'cancelled' ? 'bg-amber-600 text-white shadow-xs' : 'bg-white text-slate-700 border border-slate-200 hover:bg-slate-50' }}">
            Cancelled ({{ $statusCounts['cancelled'] }})
        </a>
    </div>

    <!-- Search Form -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <form method="GET" action="{{ route('admin.orders.index') }}" class="flex items-center gap-2">
            @if(request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
            @endif
            <div class="relative">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Search by Order #, customer, phone..." class="bg-white border border-slate-200 rounded-xl py-2 pl-9 pr-4 text-xs font-semibold focus:ring-2 focus:ring-amber-500 w-64">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-3 text-slate-400 text-xs"></i>
            </div>
            <button type="submit" class="px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white font-bold text-xs rounded-xl transition-colors">Search</button>
            @if(request()->hasAny(['status', 'q']))
                <a href="{{ route('admin.orders.index') }}" class="text-xs font-bold text-rose-600 hover:underline ml-2">Reset</a>
            @endif
        </form>
    </div>

    <!-- Orders Table -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 border-b border-slate-200 text-slate-400 uppercase text-[10px] font-bold tracking-wider">
                    <tr>
                        <th class="p-4 pl-6">Order ID</th>
                        <th class="p-4">Customer Details</th>
                        <th class="p-4">Items</th>
                        <th class="p-4">Payment</th>
                        <th class="p-4">Fulfillment Status</th>
                        <th class="p-4">Total</th>
                        <th class="p-4 pr-6 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($orders as $order)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="p-4 pl-6">
                                <span class="font-mono font-bold text-amber-950 text-sm">#{{ $order->order_number }}</span>
                                <span class="block text-[10px] text-slate-400 mt-0.5">{{ $order->created_at->format('M d, Y • h:i A') }}</span>
                            </td>
                            <td class="p-4">
                                <h4 class="font-bold text-slate-900 text-xs">{{ $order->customer_name }}</h4>
                                <p class="text-[11px] text-slate-500">{{ $order->customer_phone }}</p>
                                <p class="text-[10px] text-slate-400 truncate max-w-[180px]">{{ $order->city }}, {{ $order->state }}</p>
                            </td>
                            <td class="p-4">
                                <span class="px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-700 font-bold text-[10px]">
                                    {{ $order->items->count() }} {{ Str::plural('Item', $order->items->count()) }}
                                </span>
                            </td>
                            <td class="p-4">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase border {{ $order->payment_badge }}">
                                    {{ ucfirst($order->payment_status) }}
                                </span>
                                <span class="block text-[10px] text-slate-400 uppercase mt-1">{{ $order->payment_method }}</span>
                            </td>
                            <td class="p-4">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase border {{ $order->status_badge }}">
                                    {{ ucfirst($order->order_status) }}
                                </span>
                                @if($order->tracking_number)
                                    <span class="block text-[10px] text-slate-500 font-mono mt-1 font-semibold truncate max-w-[120px]">
                                        AWB: {{ $order->tracking_number }}
                                    </span>
                                @endif
                            </td>
                            <td class="p-4 font-extrabold text-slate-900 text-sm">
                                ₹{{ number_format($order->total_amount, 2) }}
                            </td>
                            <td class="p-4 pr-6 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="px-3 py-1.5 rounded-xl bg-amber-50 hover:bg-amber-100 text-amber-900 font-bold text-xs transition-colors flex items-center gap-1">
                                        <span>Fulfill</span>
                                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                                    </a>
                                    <a href="{{ route('admin.orders.invoice', $order->id) }}" target="_blank" class="p-1.5 px-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 transition-colors" title="Invoice Slip">
                                        <i class="fa-solid fa-print text-xs"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-12 text-center text-slate-400">No orders found matching the filter.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-100">
            {{ $orders->links() }}
        </div>
    </div>

</div>
@endsection
