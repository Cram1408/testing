@extends('layouts.app')

@section('title', 'Track Your Order - AchaarG')

@section('content')
<div class="bg-amber-50/50 py-8 border-b border-amber-200/40">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <span class="text-xs uppercase font-extrabold tracking-widest text-amber-700">Live Shipment Status</span>
        <h1 class="font-serif-heading text-3xl sm:text-4xl font-extrabold text-slate-900 mt-1">Track Your Fresh Delivery</h1>
        <p class="text-xs sm:text-sm text-slate-600 mt-1 max-w-md mx-auto">Enter your Order Number and phone or email to track fulfillment and courier dispatch.</p>

        <!-- Search Form -->
        <form action="{{ route('orders.track.post') }}" method="POST" class="mt-6 max-w-xl mx-auto flex flex-col sm:flex-row gap-2 bg-white p-2 rounded-2xl shadow-md border border-amber-200">
            @csrf
            <input type="text" name="order_number" required value="{{ old('order_number', $orderNumber ?? '') }}" placeholder="Order # (e.g. ORD-202610-1001)" 
                class="flex-1 px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-amber-500">
            <input type="text" name="contact" required value="{{ old('contact') }}" placeholder="Email or Phone Number" 
                class="flex-1 px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-amber-500">
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-amber-700 hover:bg-amber-800 text-white font-bold text-xs shadow-md transition-all flex items-center justify-center gap-1.5 shrink-0">
                <i class="fa-solid fa-magnifying-glass text-xs"></i>
                <span>Track</span>
            </button>
        </form>
    </div>
</div>

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    @if(isset($order) && $order)
        @php
            $statuses = ['pending', 'processing', 'shipped', 'delivered'];
            $currentIndex = array_search($order->order_status, $statuses);
            if ($currentIndex === false) $currentIndex = 0;
            $isCancelled = $order->order_status === 'cancelled';
        @endphp

        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-amber-100 shadow-xl space-y-8">
            
            <!-- Header Status Summary -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 pb-6">
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Shipment Details</span>
                    <h2 class="font-serif-heading text-2xl font-bold text-slate-900 mt-0.5">Order #{{ $order->order_number }}</h2>
                    <p class="text-xs text-slate-500 mt-1">Placed on {{ $order->created_at->format('M d, Y • h:i A') }}</p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="px-3 py-1 rounded-full text-xs font-extrabold uppercase border {{ $order->status_badge }}">
                        {{ ucfirst($order->order_status) }}
                    </span>
                    <span class="px-3 py-1 rounded-full text-xs font-bold uppercase border {{ $order->payment_badge }}">
                        {{ ucfirst($order->payment_status) }}
                    </span>
                </div>
            </div>

            <!-- Stepper Progress Bar -->
            @if(!$isCancelled)
                <div class="py-4">
                    <div class="relative flex items-center justify-between">
                        <!-- Background track -->
                        <div class="absolute left-0 top-1/2 -translate-y-1/2 w-full h-1.5 bg-slate-200 z-0"></div>
                        <!-- Active track -->
                        <div class="absolute left-0 top-1/2 -translate-y-1/2 h-1.5 bg-gradient-to-r from-amber-600 to-emerald-600 z-0 transition-all duration-500" 
                             style="width: {{ ($currentIndex / 3) * 100 }}%"></div>

                        <!-- Step 1: Placed -->
                        <div class="relative z-10 text-center">
                            <div class="w-10 h-10 mx-auto rounded-full flex items-center justify-center font-bold text-xs transition-colors {{ $currentIndex >= 0 ? 'bg-amber-700 text-white shadow-md' : 'bg-slate-200 text-slate-500' }}">
                                <i class="fa-solid fa-file-invoice"></i>
                            </div>
                            <span class="text-[11px] font-bold text-slate-800 mt-2 block">Placed</span>
                        </div>

                        <!-- Step 2: Processing -->
                        <div class="relative z-10 text-center">
                            <div class="w-10 h-10 mx-auto rounded-full flex items-center justify-center font-bold text-xs transition-colors {{ $currentIndex >= 1 ? 'bg-amber-700 text-white shadow-md' : 'bg-slate-200 text-slate-500' }}">
                                <i class="fa-solid fa-boxes-packing"></i>
                            </div>
                            <span class="text-[11px] font-bold text-slate-800 mt-2 block">Packed</span>
                        </div>

                        <!-- Step 3: Shipped -->
                        <div class="relative z-10 text-center">
                            <div class="w-10 h-10 mx-auto rounded-full flex items-center justify-center font-bold text-xs transition-colors {{ $currentIndex >= 2 ? 'bg-amber-700 text-white shadow-md' : 'bg-slate-200 text-slate-500' }}">
                                <i class="fa-solid fa-truck-fast"></i>
                            </div>
                            <span class="text-[11px] font-bold text-slate-800 mt-2 block">Dispatched</span>
                        </div>

                        <!-- Step 4: Delivered -->
                        <div class="relative z-10 text-center">
                            <div class="w-10 h-10 mx-auto rounded-full flex items-center justify-center font-bold text-xs transition-colors {{ $currentIndex >= 3 ? 'bg-emerald-600 text-white shadow-md' : 'bg-slate-200 text-slate-500' }}">
                                <i class="fa-solid fa-house-chimney-check"></i>
                            </div>
                            <span class="text-[11px] font-bold text-slate-800 mt-2 block">Delivered</span>
                        </div>
                    </div>
                </div>
            @else
                <div class="p-4 bg-rose-50 rounded-2xl border border-rose-200 text-rose-800 text-xs text-center font-bold">
                    <i class="fa-solid fa-ban mr-1"></i> This order has been cancelled.
                </div>
            @endif

            <!-- Courier & Tracking Number Box -->
            @if($order->tracking_number)
                <div class="p-4 rounded-2xl bg-amber-50/70 border border-amber-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-amber-200/80 text-amber-900 flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-barcode text-lg"></i>
                        </div>
                        <div>
                            <span class="text-slate-500 text-[10px] uppercase font-bold">Courier Partner & AWB</span>
                            <p class="font-bold text-slate-900 font-mono text-sm">{{ $order->tracking_number }} ({{ $order->courier_name ?? 'Express Courier' }})</p>
                        </div>
                    </div>
                    <span class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 text-[10px] font-bold">In Transit</span>
                </div>
            @endif

            <!-- Ordered Items List -->
            <div>
                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">Items in this Delivery</h4>
                <div class="divide-y divide-slate-100 border border-slate-100 rounded-2xl overflow-hidden p-4">
                    @foreach($order->items as $item)
                        <div class="py-3 first:pt-0 last:pb-0 flex items-center justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <img src="{{ asset($item->image ?? 'images/products/mango_pickle.png') }}" alt="{{ $item->product_name }}" class="w-12 h-12 rounded-xl object-cover border border-amber-100 bg-amber-50">
                                <div>
                                    <h5 class="text-xs font-bold text-slate-900">{{ $item->product_name }}</h5>
                                    <span class="text-[11px] font-semibold text-amber-800">{{ $item->variant_weight }} × {{ $item->quantity }}</span>
                                </div>
                            </div>
                            <span class="text-xs font-extrabold text-slate-900">₹{{ number_format($item->total_price, 2) }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Shipping Address -->
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 text-xs">
                <span class="text-[10px] font-bold uppercase text-slate-400 block mb-1">Destination Address</span>
                <p class="font-bold text-slate-900">{{ $order->customer_name }}</p>
                <p class="text-slate-600">{{ $order->shipping_address }}, {{ $order->city }}, {{ $order->state }} - {{ $order->postal_code }}</p>
                <p class="text-slate-500 mt-1"><i class="fa-solid fa-phone text-[10px] mr-1"></i> {{ $order->customer_phone }}</p>
            </div>

        </div>
    @elseif(!empty($orderNumber))
        <div class="bg-white rounded-3xl p-12 text-center border border-amber-100 shadow-sm max-w-lg mx-auto">
            <i class="fa-solid fa-circle-question text-3xl text-amber-700 mb-3"></i>
            <h3 class="font-serif-heading text-lg font-bold text-slate-900">No order found</h3>
            <p class="text-xs text-slate-500 mt-1">Please enter your order number and contact email/phone to check status.</p>
        </div>
    @endif
</div>
@endsection
