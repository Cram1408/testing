@extends('layouts.app')

@section('title', 'Order #' . $order->order_number . ' - Swad Desi')

@section('content')
<div class="bg-amber-50/50 py-6 border-b border-amber-200/40">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-500 mb-1">
                <a href="{{ route('customer.dashboard') }}" class="hover:text-amber-800">My Account</a>
                <span>/</span>
                <span>Orders</span>
            </div>
            <h1 class="font-serif-heading text-2xl font-bold text-slate-900">Order #{{ $order->order_number }}</h1>
        </div>
        <a href="{{ route('orders.track.index', ['order_number' => $order->order_number]) }}" class="px-4 py-2 rounded-xl bg-amber-700 hover:bg-amber-800 text-white font-bold text-xs transition-colors flex items-center gap-2">
            <i class="fa-solid fa-map-location-dot"></i>
            <span>Track Live</span>
        </a>
    </div>
</div>

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-6">
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-amber-100 shadow-sm space-y-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 border-b border-slate-100 pb-4">
            <div>
                <p class="text-xs text-slate-500">Date Placed: {{ $order->created_at->format('M d, Y • h:i A') }}</p>
                <p class="text-xs text-slate-500 mt-0.5">Payment Method: <strong class="uppercase text-slate-800">{{ $order->payment_method }}</strong></p>
            </div>
            <div class="flex items-center gap-2">
                <span class="px-3 py-1 rounded-full text-xs font-bold uppercase border {{ $order->status_badge }}">
                    {{ ucfirst($order->order_status) }}
                </span>
                <span class="px-3 py-1 rounded-full text-xs font-bold uppercase border {{ $order->payment_badge }}">
                    {{ ucfirst($order->payment_status) }}
                </span>
            </div>
        </div>

        <!-- Items Table -->
        <div class="divide-y divide-slate-100">
            @foreach($order->items as $item)
                <div class="py-4 first:pt-0 flex items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <img src="{{ asset($item->image ?? 'images/products/mango_pickle.png') }}" alt="{{ $item->product_name }}" class="w-14 h-14 rounded-xl object-cover border border-amber-100 bg-amber-50">
                        <div>
                            <h4 class="font-bold text-sm text-slate-900">{{ $item->product_name }}</h4>
                            <span class="text-xs font-semibold text-amber-800">{{ $item->variant_weight }}</span>
                            <span class="text-xs text-slate-400">× {{ $item->quantity }}</span>
                        </div>
                    </div>
                    <span class="text-sm font-extrabold text-slate-900">₹{{ number_format($item->total_price, 2) }}</span>
                </div>
            @endforeach
        </div>

        <!-- Totals Breakdown -->
        <div class="pt-4 border-t border-slate-200 max-w-xs ml-auto space-y-2 text-xs text-slate-600 font-medium">
            <div class="flex justify-between">
                <span>Subtotal:</span>
                <span class="font-bold text-slate-900">₹{{ number_format($order->subtotal, 2) }}</span>
            </div>
            <div class="flex justify-between">
                <span>Shipping:</span>
                <span class="font-bold {{ $order->shipping_fee == 0 ? 'text-emerald-700' : 'text-slate-900' }}">
                    {{ $order->shipping_fee == 0 ? 'FREE' : '₹' . number_format($order->shipping_fee, 2) }}
                </span>
            </div>
            <div class="flex justify-between text-base font-bold text-slate-900 pt-2 border-t border-slate-200">
                <span>Total:</span>
                <span class="text-amber-900 font-extrabold">₹{{ number_format($order->total_amount, 2) }}</span>
            </div>
        </div>

        <!-- Delivery Destination -->
        <div class="pt-6 border-t border-slate-100 grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
            <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200">
                <span class="text-[10px] uppercase font-bold text-slate-400 block mb-1">Delivered To</span>
                <p class="font-bold text-slate-900">{{ $order->customer_name }}</p>
                <p class="text-slate-600">{{ $order->shipping_address }}</p>
                <p class="text-slate-600">{{ $order->city }}, {{ $order->state }} - {{ $order->postal_code }}</p>
                <p class="text-slate-500 mt-1"><i class="fa-solid fa-phone text-[10px] mr-1"></i> {{ $order->customer_phone }}</p>
            </div>
            <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200">
                <span class="text-[10px] uppercase font-bold text-slate-400 block mb-1">Fulfillment Notes</span>
                @if($order->tracking_number)
                    <p class="font-mono text-amber-900 font-bold text-sm">AWB: {{ $order->tracking_number }}</p>
                    <p class="text-slate-500 mt-1">Dispatched via {{ $order->courier_name ?? 'Courier Partner' }}</p>
                @else
                    <p class="text-slate-500">Your package is being hand-packed with care.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
