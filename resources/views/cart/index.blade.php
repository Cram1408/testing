@extends('layouts.app')

@section('title', 'Your Shopping Basket - Swad Desi')

@section('content')
<div class="bg-amber-50/50 py-6 border-b border-amber-200/40">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="font-serif-heading text-3xl font-extrabold text-slate-900">Your Fresh Pickles & Mukhwas Basket</h1>
        <p class="text-xs sm:text-sm text-slate-600 mt-1">Review your selected glass jars and artisanal mouth fresheners.</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    @if(empty($items))
        <div class="bg-white rounded-3xl p-16 text-center border border-amber-100 shadow-sm max-w-xl mx-auto">
            <div class="w-20 h-20 rounded-3xl bg-amber-50 text-amber-600 mx-auto flex items-center justify-center text-3xl mb-4">
                <i class="fa-solid fa-basket-shopping"></i>
            </div>
            <h3 class="font-serif-heading text-2xl font-bold text-slate-900">Your basket is currently empty</h3>
            <p class="text-xs sm:text-sm text-slate-500 mt-2">Discover authentic heirloom mango pickles, spicy stuffed chillies, and refreshing Calcutta paan mukhwas.</p>
            <a href="{{ route('shop.index') }}" class="mt-6 inline-flex items-center gap-2 px-8 py-3.5 bg-amber-700 hover:bg-amber-800 text-white text-xs font-bold rounded-2xl shadow-lg shadow-amber-800/20 transition-all">
                <span>Start Shopping</span>
                <i class="fa-solid fa-arrow-right text-[10px]"></i>
            </a>
        </div>
    @else
        <!-- Free Shipping Banner -->
        <div class="bg-white rounded-2xl p-4 border border-amber-200 shadow-xs mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-800 flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-truck-fast text-lg"></i>
                </div>
                <div>
                    @if($freeShippingRemaining <= 0)
                        <h4 class="text-xs font-bold text-emerald-800">🎉 Congratulations! You have unlocked FREE Express Delivery</h4>
                        <p class="text-[11px] text-slate-500">Zero shipping fee will be charged at checkout.</p>
                    @else
                        <h4 class="text-xs font-bold text-slate-900">Add ₹{{ number_format($freeShippingRemaining, 2) }} more to get FREE Delivery</h4>
                        <p class="text-[11px] text-slate-500">Free shipping applies to all orders ₹{{ $freeShippingThreshold }} and above.</p>
                    @endif
                </div>
            </div>
            <div class="w-full sm:w-48 bg-slate-100 rounded-full h-2.5 overflow-hidden shrink-0">
                <div class="bg-gradient-to-r from-amber-500 to-emerald-600 h-2.5 rounded-full transition-all duration-300" 
                     style="width: {{ min(100, max(0, ($subtotal / $freeShippingThreshold) * 100)) }}%"></div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- Items Table -->
            <div class="lg:col-span-8 bg-white rounded-3xl p-6 border border-amber-100 shadow-sm overflow-hidden">
                <div class="flex justify-between items-center pb-4 border-b border-slate-100">
                    <h3 class="font-bold text-sm text-slate-900">Basket Items ({{ count($items) }})</h3>
                    <form action="{{ route('cart.clear') }}" method="POST" onsubmit="return confirm('Are you sure you want to clear your cart?');">
                        @csrf
                        <button type="submit" class="text-xs font-semibold text-rose-600 hover:text-rose-800 transition-colors flex items-center gap-1">
                            <i class="fa-solid fa-trash-can text-[10px]"></i> Clear Basket
                        </button>
                    </form>
                </div>

                <div class="divide-y divide-slate-100">
                    @foreach($items as $item)
                        <div class="py-5 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                            <div class="flex items-center gap-4 min-w-0">
                                <img src="{{ asset($item['image']) }}" alt="{{ $item['name'] }}" class="w-20 h-20 rounded-2xl object-cover border border-amber-100 bg-amber-50 shrink-0">
                                <div class="truncate">
                                    <h4 class="font-bold text-sm text-slate-900 truncate">
                                        <a href="{{ route('shop.show', $item['slug']) }}" class="hover:text-amber-800 transition-colors">{{ $item['name'] }}</a>
                                    </h4>
                                    <span class="inline-block mt-1 px-2.5 py-0.5 rounded-md bg-amber-100 text-amber-900 text-xs font-bold">
                                        {{ $item['weight'] }}
                                    </span>
                                    <p class="text-xs text-slate-400 mt-1">Price: ₹{{ number_format($item['price'], 2) }}</p>
                                </div>
                            </div>

                            <div class="flex items-center justify-between sm:justify-end gap-6 w-full sm:w-auto">
                                <!-- Qty Selector -->
                                <form action="{{ route('cart.update') }}" method="POST" class="flex items-center border border-slate-200 rounded-xl overflow-hidden bg-slate-50">
                                    @csrf
                                    <input type="hidden" name="variant_id" value="{{ $item['variant_id'] }}">
                                    <button type="submit" name="quantity" value="{{ $item['quantity'] - 1 }}" class="px-2.5 py-1 text-xs text-slate-600 hover:bg-slate-200 transition-colors font-bold">-</button>
                                    <span class="px-3 py-1 text-xs font-bold text-slate-900 bg-white">{{ $item['quantity'] }}</span>
                                    <button type="submit" name="quantity" value="{{ $item['quantity'] + 1 }}" class="px-2.5 py-1 text-xs text-slate-600 hover:bg-slate-200 transition-colors font-bold">+</button>
                                </form>

                                <!-- Total for Item -->
                                <span class="text-sm font-extrabold text-slate-900 min-w-[70px] text-right">
                                    ₹{{ number_format($item['total'], 2) }}
                                </span>

                                <!-- Delete -->
                                <form action="{{ route('cart.remove', $item['variant_id']) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-slate-300 hover:text-rose-600 transition-colors p-1.5" title="Remove Item">
                                        <i class="fa-solid fa-xmark text-sm"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="pt-6 border-t border-slate-100 flex justify-between items-center">
                    <a href="{{ route('shop.index') }}" class="text-xs font-bold text-amber-800 hover:text-amber-950 flex items-center gap-1.5 transition-colors">
                        <i class="fa-solid fa-arrow-left text-[10px]"></i>
                        <span>Continue Shopping</span>
                    </a>
                </div>
            </div>

            <!-- Order Summary Card -->
            <div class="lg:col-span-4 bg-white rounded-3xl p-6 border border-amber-100 shadow-sm space-y-4">
                <h3 class="font-bold text-base text-slate-900 border-b border-slate-100 pb-3">Order Summary</h3>

                <div class="space-y-2.5 text-xs">
                    <div class="flex justify-between text-slate-600 font-medium">
                        <span>Items Subtotal</span>
                        <span class="font-bold text-slate-900">₹{{ number_format($subtotal, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-slate-600 font-medium">
                        <span>Estimated Shipping</span>
                        <span class="font-bold {{ $shipping == 0 ? 'text-emerald-700' : 'text-slate-900' }}">
                            {{ $shipping == 0 ? 'FREE' : '₹' . number_format($shipping, 2) }}
                        </span>
                    </div>
                    <div class="flex justify-between text-slate-600 font-medium">
                        <span>Taxes (GST)</span>
                        <span class="font-bold text-emerald-700">Included</span>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-200 flex justify-between items-baseline">
                    <span class="font-bold text-sm text-slate-900">Grand Total</span>
                    <span class="font-extrabold text-2xl text-amber-900">₹{{ number_format($total, 2) }}</span>
                </div>

                <a href="{{ route('checkout.index') }}" class="w-full py-4 rounded-2xl bg-gradient-to-r from-amber-700 to-amber-900 hover:from-amber-800 hover:to-amber-950 text-white font-bold text-sm shadow-xl shadow-amber-900/20 active:scale-95 transition-all flex items-center justify-center gap-2">
                    <span>Proceed to Checkout</span>
                    <i class="fa-solid fa-arrow-right text-xs"></i>
                </a>

                <div class="p-3 bg-amber-50/60 rounded-xl border border-amber-100 text-[11px] text-amber-900 text-center space-y-1">
                    <p class="font-bold"><i class="fa-solid fa-shield-check mr-1"></i> Safe & Tamper-Proof Packaging</p>
                    <p class="text-slate-500">Double bubble wrapped glass jars with leak-proof inner seals.</p>
                </div>
            </div>

        </div>
    @endif
</div>
@endsection
