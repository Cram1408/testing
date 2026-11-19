@extends('layouts.admin')

@section('title', 'Customer #' . $customer->id . ' - Admin')
@section('header_title', 'Customer Profile')
@section('header_subtitle', 'Customer details, shipping addresses, account status, and order history')

@section('content')
<div class="space-y-6 max-w-6xl mx-auto">
    {{-- Back Link & Quick Actions --}}
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.customers.index') }}" class="text-xs font-bold text-amber-800 hover:text-amber-950 flex items-center gap-1.5">
            <i class="fa-solid fa-arrow-left text-[10px]"></i>
            <span>Back to Customers</span>
        </a>
        <div class="flex items-center gap-3">
            <form action="{{ route('admin.customers.toggle', $customer->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to change the status of this customer?');">
                @csrf
                @if($isDeactivated)
                    <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-xs transition-colors flex items-center gap-1.5">
                        <i class="fa-solid fa-circle-check"></i>
                        <span>Reactivate Account</span>
                    </button>
                @else
                    <button type="submit" class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs rounded-xl shadow-xs transition-colors flex items-center gap-1.5">
                        <i class="fa-solid fa-ban"></i>
                        <span>Deactivate Account</span>
                    </button>
                @endif
            </form>
        </div>
    </div>

    {{-- Top Overview Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        {{-- Profile Card --}}
        <div class="bg-white rounded-3xl p-6 border border-amber-200/80 shadow-sm space-y-4">
            <div class="flex items-center gap-4 border-b border-amber-100 pb-4">
                <div class="w-16 h-16 rounded-2xl bg-amber-100 text-amber-900 font-extrabold flex items-center justify-center text-xl shrink-0 overflow-hidden border border-amber-300">
                    <img src="{{ $customer->avatar_url }}" alt="{{ $customer->name }}" class="w-full h-full object-cover">
                </div>
                <div class="min-w-0">
                    <h3 class="font-serif-heading font-bold text-amber-950 text-base truncate">{{ $customer->name }}</h3>
                    <p class="text-xs text-amber-800/70 truncate">{{ $customer->email }}</p>
                    <div class="mt-1">
                        @if($isDeactivated)
                            <span class="px-2.5 py-0.5 rounded-full bg-rose-100 text-rose-700 border border-rose-200 font-bold text-[10px]">Account Deactivated</span>
                        @else
                            <span class="px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-800 border border-emerald-200 font-bold text-[10px]">Active Customer</span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="space-y-2 text-xs">
                <div>
                    <span class="text-amber-800/60 text-[10px] uppercase font-bold block">Contact Phone:</span>
                    <span class="font-semibold text-slate-800">{{ $customer->phone ?? 'Not provided' }}</span>
                </div>
                <div>
                    <span class="text-amber-800/60 text-[10px] uppercase font-bold block">Registered On:</span>
                    <span class="font-semibold text-slate-800">{{ $customer->created_at->format('M d, Y • h:i A') }}</span>
                </div>
            </div>
        </div>

        {{-- Shipping Address Card --}}
        <div class="bg-white rounded-3xl p-6 border border-amber-200/80 shadow-sm space-y-3">
            <h4 class="font-serif-heading font-bold text-xs uppercase tracking-wider text-amber-950 border-b border-amber-100 pb-2 flex items-center gap-1.5">
                <i class="fa-solid fa-map-location-dot text-amber-700"></i>
                <span>Default Delivery Address</span>
            </h4>
            @if($customer->address || $customer->city)
                <div class="text-xs text-slate-700 space-y-1">
                    <p class="font-bold text-slate-900">{{ $customer->name }}</p>
                    <p class="text-slate-600">{{ $customer->address ?? 'No street address saved' }}</p>
                    <p class="text-slate-600">{{ $customer->city ?? '' }}{{ $customer->state ? ', ' . $customer->state : '' }} {{ $customer->postal_code ? '- ' . $customer->postal_code : '' }}</p>
                    <p class="text-slate-500 pt-1">Phone: {{ $customer->phone ?? 'N/A' }}</p>
                </div>
            @else
                <p class="text-xs text-slate-400 italic py-4">No default address on file.</p>
            @endif
        </div>

        {{-- Purchase Statistics Card --}}
        <div class="bg-white rounded-3xl p-6 border border-amber-200/80 shadow-sm space-y-4">
            <h4 class="font-serif-heading font-bold text-xs uppercase tracking-wider text-amber-950 border-b border-amber-100 pb-2 flex items-center gap-1.5">
                <i class="fa-solid fa-chart-simple text-amber-700"></i>
                <span>Lifetime Value</span>
            </h4>
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-xs text-amber-800/70">Total Orders Placed:</span>
                    <span class="text-base font-bold text-amber-950">{{ $customer->orders()->count() }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-xs text-amber-800/70">Total Money Spent:</span>
                    <span class="text-lg font-extrabold text-amber-950 font-mono">₹{{ number_format($totalSpent, 2) }}</span>
                </div>
                <div class="flex items-center justify-between pt-2 border-t border-amber-100 text-xs text-slate-500">
                    <span>Average Order Value:</span>
                    <span class="font-bold text-amber-950 font-mono">
                        @php
                            $orderCount = $customer->orders()->count();
                            $aov = $orderCount > 0 ? ($totalSpent / $orderCount) : 0;
                        @endphp
                        ₹{{ number_format($aov, 2) }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- Order History Table --}}
    <div class="bg-white rounded-3xl border border-amber-200/80 shadow-sm p-6 sm:p-8 space-y-6">
        <div class="flex items-center justify-between border-b border-amber-100 pb-4">
            <h3 class="font-serif-heading font-bold text-lg text-amber-950">Customer Orders History</h3>
            <span class="text-xs text-amber-800/70 font-semibold">Showing {{ $orders->count() }} orders</span>
        </div>

        @if($orders->isEmpty())
            <div class="py-12 text-center text-slate-400 text-xs">
                No orders placed by this customer yet.
            </div>
        @else
            <div class="divide-y divide-amber-100/60">
                @foreach($orders as $order)
                    <div class="py-4 first:pt-0 flex flex-col sm:flex-row sm:items-center justify-between gap-4 hover:bg-amber-50/20 px-2 rounded-xl transition-colors">
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="font-mono font-bold text-amber-950 text-xs">#{{ $order->order_number }}</span>
                                <span class="text-amber-800/60 text-xs">• {{ $order->created_at->format('M d, Y • h:i A') }}</span>
                            </div>
                            <p class="text-xs text-slate-500 mt-1">
                                {{ $order->items->count() }} items: {{ $order->items->pluck('product_name')->join(', ') }}
                            </p>
                        </div>

                        <div class="flex items-center gap-3">
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase border {{ $order->status_badge }}">
                                {{ ucfirst($order->order_status) }}
                            </span>
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase border {{ $order->payment_badge }}">
                                {{ ucfirst($order->payment_status) }}
                            </span>
                            <span class="font-mono font-extrabold text-sm text-amber-950">₹{{ number_format($order->total_amount, 2) }}</span>
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="px-3 py-1.5 rounded-xl bg-amber-50 hover:bg-amber-100 text-amber-900 border border-amber-200/80 text-xs font-bold transition-colors">
                                View
                            </a>
                            <a href="{{ route('admin.orders.invoice', $order->id) }}" target="_blank" class="px-2.5 py-1.5 rounded-xl bg-amber-50 hover:bg-amber-100 text-amber-900 border border-amber-200/80 text-xs font-bold transition-colors">
                                <i class="fa-solid fa-file-invoice"></i>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="pt-4 border-t border-amber-100">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

