@extends('layouts.app')

@section('title', 'My Orders - AchaarG')

@section('content')
{{-- Top Banner --}}
<div class="bg-amber-50/50 py-6 border-b border-amber-200/40">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="font-serif-heading text-2xl sm:text-3xl font-extrabold text-slate-900">My Orders</h1>
            <p class="text-xs sm:text-sm text-slate-600 mt-1">Track your deliveries and view official tax invoices for your handmade pickle orders.</p>
        </div>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="px-4 py-2 rounded-xl bg-white hover:bg-rose-50 text-rose-600 border border-slate-200 text-xs font-bold transition-colors flex items-center gap-1.5 shadow-xs">
                <i class="fa-solid fa-arrow-right-from-bracket"></i>
                <span>Sign Out</span>
            </button>
        </form>
    </div>
</div>

<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
    {{-- Navigation Tabs --}}
    <div class="flex items-center gap-2 border-b border-slate-200 pb-3">
        <a href="{{ route('customer.profile') }}" 
           class="px-4 py-2 rounded-xl bg-white hover:bg-amber-50 text-slate-700 border border-slate-200 text-xs font-semibold hover:text-amber-800 flex items-center gap-2 transition-all">
            <i class="fa-solid fa-user text-slate-400"></i>
            <span>My Profile</span>
        </a>
        <a href="{{ route('customer.orders') }}" 
           class="px-4 py-2 rounded-xl bg-amber-700 text-white text-xs font-bold shadow-sm flex items-center gap-2 transition-all">
            <i class="fa-solid fa-box-open"></i>
            <span>My Orders</span>
            @if(isset($ordersCount) && $ordersCount > 0)
                <span class="px-1.5 py-0.5 rounded-full bg-amber-800 text-amber-100 text-[10px] font-bold">{{ $ordersCount }}</span>
            @endif
        </a>
    </div>

    {{-- Orders List Only Container --}}
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-amber-100 shadow-sm space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-slate-100 pb-4">
            <div>
                <h2 class="font-serif-heading font-bold text-lg text-slate-900">Order History</h2>
                <p class="text-xs text-slate-500 mt-0.5">Showing all past and active orders placed under your account.</p>
            </div>
            @if($orders->isNotEmpty())
                <span class="text-xs text-slate-500 font-medium">Total Orders: <strong class="text-slate-900">{{ $orders->total() }}</strong></span>
            @endif
        </div>

        @if($orders->isEmpty())
            <div class="py-16 text-center max-w-md mx-auto space-y-3">
                <div class="w-16 h-16 rounded-2xl bg-amber-50 text-amber-600 mx-auto flex items-center justify-center text-2xl shadow-inner">
                    <i class="fa-solid fa-box-open"></i>
                </div>
                <h3 class="font-bold text-slate-800 text-base">No orders yet</h3>
                <p class="text-xs text-slate-400">Ready for traditional flavours? Browse our freshly sun-cured pickles and digestives!</p>
                <a href="{{ route('shop.index') }}" class="mt-4 inline-flex items-center gap-2 px-6 py-2.5 bg-amber-700 hover:bg-amber-800 text-white text-xs font-bold rounded-xl shadow-md transition-colors">
                    <i class="fa-solid fa-cart-shopping text-xs"></i>
                    <span>Explore Products</span>
                </a>
            </div>
        @else
            <div class="space-y-4">
                @foreach($orders as $order)
                    <div class="p-5 rounded-2xl border border-slate-200 hover:border-amber-300 transition-all bg-slate-50/40 space-y-3">
                        {{-- Card Header --}}
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-slate-200/60 pb-3">
                            <div class="flex items-center gap-2">
                                <span class="font-mono font-bold text-amber-950 text-xs">#{{ $order->order_number }}</span>
                                <span class="text-slate-400 text-xs">• {{ $order->created_at->format('M d, Y') }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase border {{ $order->status_badge }}">
                                    {{ ucfirst($order->order_status) }}
                                </span>
                                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase border {{ $order->payment_badge }}">
                                    {{ ucfirst($order->payment_status) }}
                                </span>
                            </div>
                        </div>

                        {{-- Card Content --}}
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div class="text-xs text-slate-600 space-y-1">
                                <p class="font-semibold text-slate-800">{{ $order->items->count() }} {{ Str::plural('Item', $order->items->count()) }}:</p>
                                <p class="text-slate-500 truncate max-w-xl">
                                    {{ $order->items->pluck('product_name')->join(', ') }}
                                </p>
                            </div>
                            <div class="flex items-center justify-between sm:justify-end gap-3 shrink-0">
                                <div class="text-right">
                                    <span class="text-[10px] text-slate-400 uppercase font-bold block">Total Amount</span>
                                    <span class="font-extrabold text-sm text-slate-900 font-mono">₹{{ number_format($order->total_amount, 2) }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    {{-- Tax Invoice Shortcut --}}
                                    <a href="{{ route('customer.orders.invoice', $order->order_number) }}" target="_blank" title="View & Download Invoice" 
                                       class="px-3 py-2 rounded-xl bg-white hover:bg-slate-100 text-slate-700 font-bold text-xs transition-colors flex items-center gap-1.5 border border-slate-200 shadow-2xs">
                                        <i class="fa-solid fa-file-invoice text-amber-800"></i>
                                        <span>Invoice</span>
                                    </a>
                                    {{-- Order Details --}}
                                    <a href="{{ route('customer.orders.show', $order->order_number) }}" 
                                       class="px-3.5 py-2 rounded-xl bg-amber-100 hover:bg-amber-200 text-amber-900 font-bold text-xs transition-colors flex items-center gap-1.5 shadow-2xs">
                                        <span>Details</span>
                                        <i class="fa-solid fa-chevron-right text-[10px]"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="pt-4 border-t border-slate-100">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

