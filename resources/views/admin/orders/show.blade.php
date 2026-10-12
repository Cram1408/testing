@extends('layouts.admin')

@section('title', 'Order #' . $order->order_number . ' - Admin')
@section('header_title', 'Order Fulfillment')
@section('header_subtitle', 'Manage shipment, tracking numbers and payment confirmation')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.orders.index') }}" class="text-xs font-bold text-slate-500 hover:text-slate-800">← Back to Orders</a>
        <a href="{{ route('admin.orders.invoice', $order->id) }}" target="_blank" class="px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white font-bold text-xs rounded-xl shadow-xs transition-colors flex items-center gap-2">
            <i class="fa-solid fa-print"></i>
            <span>View Printable Slip</span>
        </a>
    </div>

    <!-- Fulfillment Control Form Card -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-sm space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-100 pb-4">
            <div>
                <span class="text-[10px] uppercase font-bold text-slate-400">Fulfillment Settings</span>
                <h3 class="font-serif-heading font-bold text-xl text-slate-900 mt-0.5">Order #{{ $order->order_number }}</h3>
                <p class="text-xs text-slate-500 mt-1">Placed: {{ $order->created_at->format('M d, Y • h:i A') }}</p>
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

        <form action="{{ route('admin.orders.update', $order->id) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Fulfillment Status *</label>
                    <select name="order_status" required class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs font-bold text-slate-900 focus:ring-2 focus:ring-amber-500">
                        <option value="pending" {{ $order->order_status === 'pending' ? 'selected' : '' }}>Pending (Received)</option>
                        <option value="processing" {{ $order->order_status === 'processing' ? 'selected' : '' }}>Processing (In Kitchen / Packing)</option>
                        <option value="shipped" {{ $order->order_status === 'shipped' ? 'selected' : '' }}>Shipped (Dispatched to Courier)</option>
                        <option value="delivered" {{ $order->order_status === 'delivered' ? 'selected' : '' }}>Delivered Safely</option>
                        <option value="cancelled" {{ $order->order_status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Payment Status *</label>
                    <select name="payment_status" required class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs font-bold text-slate-900 focus:ring-2 focus:ring-amber-500">
                        <option value="pending" {{ $order->payment_status === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="paid" {{ $order->payment_status === 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="failed" {{ $order->payment_status === 'failed' ? 'selected' : '' }}>Failed</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Courier Service Partner</label>
                    <input type="text" name="courier_name" value="{{ old('courier_name', $order->courier_name) }}" placeholder="e.g. DTDC Express, Delhivery, BlueDart" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs focus:ring-2 focus:ring-amber-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Tracking Number / AWB</label>
                    <input type="text" name="tracking_number" value="{{ old('tracking_number', $order->tracking_number) }}" placeholder="e.g. DTDC98234120IN" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs font-mono font-bold focus:ring-2 focus:ring-amber-500">
                </div>
            </div>

            <div class="pt-3 border-t border-slate-100 flex items-center justify-end">
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-amber-600 hover:bg-amber-700 text-white font-bold text-xs shadow-md transition-all flex items-center gap-1.5">
                    <i class="fa-solid fa-floppy-disk text-xs"></i>
                    <span>Save Order Updates</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Items & Financial Breakdown -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-sm space-y-6">
        <h4 class="font-bold text-sm text-slate-900 border-b border-slate-100 pb-3 flex items-center gap-2">
            <i class="fa-solid fa-cart-flatbed text-amber-600"></i>
            <span>Ordered Items ({{ $order->items->count() }})</span>
        </h4>

        <div class="divide-y divide-slate-100">
            @foreach($order->items as $item)
                <div class="py-3 first:pt-0 flex items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <img src="{{ asset($item->image ?? 'images/products/mango_pickle.png') }}" class="w-12 h-12 rounded-xl object-cover border border-slate-200 bg-amber-50">
                        <div>
                            <h5 class="text-xs font-bold text-slate-900">{{ $item->product_name }}</h5>
                            <span class="text-[11px] font-semibold text-amber-800">{{ $item->variant_weight }}</span>
                            <span class="text-[11px] text-slate-400">× {{ $item->quantity }}</span>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="text-xs font-bold text-slate-900 block">₹{{ number_format($item->total_price, 2) }}</span>
                        <span class="text-[10px] text-slate-400">(₹{{ number_format($item->unit_price, 2) }} each)</span>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="pt-4 border-t border-slate-200 max-w-xs ml-auto space-y-2 text-xs text-slate-600 font-medium">
            <div class="flex justify-between">
                <span>Items Subtotal:</span>
                <span class="font-bold text-slate-900">₹{{ number_format($order->subtotal, 2) }}</span>
            </div>
            <div class="flex justify-between">
                <span>Shipping Fee:</span>
                <span class="font-bold {{ $order->shipping_fee == 0 ? 'text-emerald-700' : 'text-slate-900' }}">
                    {{ $order->shipping_fee == 0 ? 'FREE' : '₹' . number_format($order->shipping_fee, 2) }}
                </span>
            </div>
            <div class="flex justify-between text-base font-bold text-slate-900 pt-2 border-t border-slate-200">
                <span>Total Amount:</span>
                <span class="text-amber-900 font-extrabold">₹{{ number_format($order->total_amount, 2) }}</span>
            </div>
        </div>
    </div>

    <!-- Customer & Destination Details -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-sm space-y-4">
        <h4 class="font-bold text-sm text-slate-900 border-b border-slate-100 pb-3 flex items-center gap-2">
            <i class="fa-solid fa-location-dot text-amber-600"></i>
            <span>Customer Shipping Address</span>
        </h4>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs text-slate-700">
            <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200 space-y-1">
                <span class="text-[10px] uppercase font-bold text-slate-400 block mb-1">Customer Info</span>
                <p class="font-bold text-slate-900">{{ $order->customer_name }}</p>
                <p><i class="fa-solid fa-envelope text-[10px] mr-1 text-slate-400"></i> {{ $order->customer_email }}</p>
                <p><i class="fa-solid fa-phone text-[10px] mr-1 text-slate-400"></i> {{ $order->customer_phone }}</p>
            </div>
            <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200 space-y-1">
                <span class="text-[10px] uppercase font-bold text-slate-400 block mb-1">Destination Address</span>
                <p>{{ $order->shipping_address }}</p>
                <p>{{ $order->city }}, {{ $order->state }} - {{ $order->postal_code }}</p>
                @if($order->notes)
                    <p class="mt-2 text-amber-800 font-semibold italic"><i class="fa-solid fa-note-sticky mr-1"></i> "{{ $order->notes }}"</p>
                @endif
            </div>
        </div>
    </div>

</div>
@endsection
