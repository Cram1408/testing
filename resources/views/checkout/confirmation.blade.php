@extends('layouts.app')

@section('title', 'Order Confirmed! #' . $order->order_number . ' - Swad Desi')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Success Badge Header -->
    <div class="bg-white rounded-3xl p-8 sm:p-10 border border-amber-100 shadow-xl text-center space-y-4">
        <div class="w-20 h-20 rounded-full bg-emerald-100 text-emerald-600 mx-auto flex items-center justify-center text-3xl shadow-inner animate-bounce">
            <i class="fa-solid fa-check"></i>
        </div>

        <span class="inline-block px-3.5 py-1 rounded-full bg-emerald-100 text-emerald-800 text-xs font-extrabold uppercase tracking-wider">
            Order Confirmed & Placed Successfully
        </span>

        <h1 class="font-serif-heading text-3xl sm:text-4xl font-extrabold text-slate-900">
            Thank You, {{ $order->customer_name }}!
        </h1>

        <p class="text-xs sm:text-sm text-slate-600 max-w-lg mx-auto">
            We have received your order. Our kitchen team is preparing your handcrafted pickles and packaging your fresh mukhwas.
        </p>

        <!-- Order Quick Info Box -->
        <div class="bg-amber-50/70 rounded-2xl p-4 border border-amber-200/60 max-w-lg mx-auto grid grid-cols-2 gap-4 text-left text-xs">
            <div>
                <span class="text-slate-400 font-semibold uppercase text-[10px] block">Order Number</span>
                <span class="font-mono font-bold text-amber-950 text-sm">{{ $order->order_number }}</span>
            </div>
            <div>
                <span class="text-slate-400 font-semibold uppercase text-[10px] block">Payment Method</span>
                <span class="font-bold text-slate-800 uppercase">{{ $order->payment_method }}</span>
                <span class="ml-1 text-[10px] font-bold px-1.5 py-0.2 rounded {{ $order->payment_status === 'paid' ? 'bg-emerald-200 text-emerald-800' : 'bg-amber-200 text-amber-800' }}">{{ strtoupper($order->payment_status) }}</span>
            </div>
        </div>

        <!-- Action CTAs -->
        <div class="pt-2 flex flex-col sm:flex-row items-center justify-center gap-3">
            <a href="{{ route('orders.track.index', ['order_number' => $order->order_number]) }}" class="w-full sm:w-auto px-6 py-3 rounded-xl bg-amber-700 hover:bg-amber-800 text-white font-bold text-xs shadow-md transition-all flex items-center justify-center gap-2">
                <i class="fa-solid fa-map-location-dot"></i>
                <span>Track Live Delivery Status</span>
            </a>
            <button onclick="window.print()" class="w-full sm:w-auto px-6 py-3 rounded-xl bg-white hover:bg-slate-50 text-slate-700 border border-slate-300 font-bold text-xs transition-all flex items-center justify-center gap-2">
                <i class="fa-solid fa-print"></i>
                <span>Print Invoice</span>
            </button>
        </div>
    </div>

    <!-- Order Items & Shipping Breakdown Card -->
    <div class="mt-8 bg-white rounded-3xl p-6 sm:p-8 border border-amber-100 shadow-sm space-y-6">
        <h3 class="font-serif-heading font-bold text-lg text-slate-900 border-b border-slate-100 pb-3">Items Summary</h3>

        <div class="divide-y divide-slate-100">
            @foreach($order->items as $item)
                <div class="py-4 first:pt-0 flex items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <img src="{{ asset($item->image ?? 'images/products/mango_pickle.png') }}" alt="{{ $item->product_name }}" class="w-14 h-14 rounded-xl object-cover border border-amber-100 bg-amber-50">
                        <div>
                            <h4 class="font-bold text-xs sm:text-sm text-slate-900">{{ $item->product_name }}</h4>
                            <span class="text-xs font-semibold text-amber-800">{{ $item->variant_weight }}</span>
                            <span class="text-xs text-slate-400">× {{ $item->quantity }}</span>
                        </div>
                    </div>
                    <span class="text-xs sm:text-sm font-extrabold text-slate-900">₹{{ number_format($item->total_price, 2) }}</span>
                </div>
            @endforeach
        </div>

        <!-- Totals Table -->
        <div class="pt-4 border-t border-slate-200 space-y-2 text-xs text-slate-600 font-medium max-w-xs ml-auto">
            <div class="flex justify-between">
                <span>Subtotal:</span>
                <span class="font-bold text-slate-900">₹{{ number_format($order->subtotal, 2) }}</span>
            </div>
            <div class="flex justify-between">
                <span>Shipping Fee:</span>
                <span class="font-bold {{ $order->shipping_fee == 0 ? 'text-emerald-700' : 'text-slate-900' }}">
                    {{ $order->shipping_fee == 0 ? 'FREE' : '₹' . number_format($order->shipping_fee, 2) }}
                </span>
            </div>
            <div class="flex justify-between text-sm font-bold text-slate-900 pt-2 border-t border-slate-200">
                <span>Total Paid / Payable:</span>
                <span class="text-amber-900 font-extrabold text-base">₹{{ number_format($order->total_amount, 2) }}</span>
            </div>
        </div>

        <!-- Delivery Details -->
        <div class="pt-6 border-t border-slate-100 grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
            <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200">
                <span class="text-[10px] uppercase font-bold text-slate-400 block mb-1">Shipping Address</span>
                <p class="font-bold text-slate-800">{{ $order->customer_name }}</p>
                <p class="text-slate-600">{{ $order->shipping_address }}</p>
                <p class="text-slate-600">{{ $order->city }}, {{ $order->state }} - {{ $order->postal_code }}</p>
                <p class="text-slate-500 mt-1"><i class="fa-solid fa-phone text-[10px] mr-1"></i> {{ $order->customer_phone }}</p>
            </div>
            <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200">
                <span class="text-[10px] uppercase font-bold text-slate-400 block mb-1">Shipping & Transit</span>
                <p class="font-bold text-slate-800">Standard Express Courier</p>
                <p class="text-slate-600 mt-1">Delivery expected within 2 to 4 business days.</p>
                @if($order->tracking_number)
                    <p class="text-amber-800 font-bold mt-2 font-mono">Tracking: {{ $order->tracking_number }} ({{ $order->courier_name ?? 'Courier' }})</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
