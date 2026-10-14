@extends('layouts.app')

@section('title', 'Secure Checkout - AchaarG')

@section('content')
<div class="bg-amber-50/50 py-6 border-b border-amber-200/40">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="font-serif-heading text-3xl font-extrabold text-slate-900">Secure Checkout</h1>
        <p class="text-xs sm:text-sm text-slate-600 mt-1">Provide your delivery address and choose your payment method.</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <form action="{{ route('checkout.process') }}" method="POST" id="checkoutForm">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- Left Column: Shipping & Payment Details -->
            <div class="lg:col-span-7 space-y-6">

                <!-- Step 1: Customer Contact & Shipping Address -->
                <div class="bg-white rounded-3xl p-6 sm:p-8 border border-amber-100 shadow-sm space-y-6">
                    <div class="flex items-center gap-3 border-b border-slate-100 pb-4">
                        <div class="w-8 h-8 rounded-xl bg-amber-700 text-white flex items-center justify-center font-bold text-xs">
                            1
                        </div>
                        <h3 class="font-serif-heading font-bold text-lg text-slate-900">Delivery Address</h3>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Full Customer Name *</label>
                            <input type="text" name="customer_name" required 
                                value="{{ old('customer_name', $user->name ?? '') }}" 
                                placeholder="e.g. Aarav Sharma" 
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs font-medium text-slate-900 focus:outline-none focus:ring-2 focus:ring-amber-500">
                            @error('customer_name') <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Email Address *</label>
                            <input type="email" name="customer_email" required 
                                value="{{ old('customer_email', $user->email ?? '') }}" 
                                placeholder="e.g. aarav@example.com" 
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs font-medium text-slate-900 focus:outline-none focus:ring-2 focus:ring-amber-500">
                            @error('customer_email') <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Mobile Phone *</label>
                            <input type="tel" name="customer_phone" required 
                                value="{{ old('customer_phone', $user->phone ?? '') }}" 
                                placeholder="e.g. +91 98765 43210" 
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs font-medium text-slate-900 focus:outline-none focus:ring-2 focus:ring-amber-500">
                            @error('customer_phone') <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Flat / House / Street Address *</label>
                            <input type="text" name="shipping_address" required 
                                value="{{ old('shipping_address', $user->address ?? '') }}" 
                                placeholder="e.g. Flat 402, Royal Palms, Link Road" 
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs font-medium text-slate-900 focus:outline-none focus:ring-2 focus:ring-amber-500">
                            @error('shipping_address') <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">City *</label>
                            <input type="text" name="city" required 
                                value="{{ old('city', $user->city ?? '') }}" 
                                placeholder="e.g. Mumbai" 
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs font-medium text-slate-900 focus:outline-none focus:ring-2 focus:ring-amber-500">
                            @error('city') <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">State *</label>
                            <input type="text" name="state" required 
                                value="{{ old('state', $user->state ?? '') }}" 
                                placeholder="e.g. Maharashtra" 
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs font-medium text-slate-900 focus:outline-none focus:ring-2 focus:ring-amber-500">
                            @error('state') <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">PIN Code / Postal Code *</label>
                            <input type="text" name="postal_code" required 
                                value="{{ old('postal_code', $user->postal_code ?? '') }}" 
                                placeholder="e.g. 400053" 
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs font-medium text-slate-900 focus:outline-none focus:ring-2 focus:ring-amber-500">
                            @error('postal_code') <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Delivery Notes (Optional)</label>
                            <textarea name="notes" rows="2" placeholder="e.g. Please leave with security or call before arriving" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs font-medium text-slate-900 focus:outline-none focus:ring-2 focus:ring-amber-500">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Step 2: Payment Method Selection -->
                <div class="bg-white rounded-3xl p-6 sm:p-8 border border-amber-100 shadow-sm space-y-6">
                    <div class="flex items-center gap-3 border-b border-slate-100 pb-4">
                        <div class="w-8 h-8 rounded-xl bg-amber-700 text-white flex items-center justify-center font-bold text-xs">
                            2
                        </div>
                        <h3 class="font-serif-heading font-bold text-lg text-slate-900">Payment Option</h3>
                    </div>

                    <div class="space-y-3">
                        <!-- UPI / Online Gateway -->
                        <label class="flex items-start gap-3 p-4 rounded-2xl border cursor-pointer transition-all hover:border-amber-400 bg-amber-50/40 border-amber-300">
                            <input type="radio" name="payment_method" value="online_upi" checked class="mt-1 text-amber-700 focus:ring-amber-500">
                            <div class="flex-1">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-bold text-slate-900 flex items-center gap-1.5">
                                        <i class="fa-solid fa-qrcode text-amber-700 text-sm"></i>
                                        <span>Instant UPI / QR Code (Recommended)</span>
                                    </span>
                                    <span class="text-[10px] font-extrabold text-emerald-700 bg-emerald-100 px-2 py-0.5 rounded-full">Zero Extra Fee</span>
                                </div>
                                <p class="text-[11px] text-slate-500 mt-1">Google Pay, PhonePe, Paytm, BHIM or any UPI App.</p>
                            </div>
                        </label>

                        <!-- Cards / NetBanking -->
                        <label class="flex items-start gap-3 p-4 rounded-2xl border cursor-pointer transition-all hover:border-amber-400 border-slate-200 bg-white">
                            <input type="radio" name="payment_method" value="card" class="mt-1 text-amber-700 focus:ring-amber-500">
                            <div class="flex-1">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-bold text-slate-900 flex items-center gap-1.5">
                                        <i class="fa-solid fa-credit-card text-amber-700 text-sm"></i>
                                        <span>Credit / Debit Card / Net Banking</span>
                                    </span>
                                    <span class="text-[10px] text-slate-400">Visa, Mastercard, RuPay</span>
                                </div>
                                <p class="text-[11px] text-slate-500 mt-1">Instant confirmation with 256-bit encrypted security.</p>
                            </div>
                        </label>

                        <!-- Cash on Delivery -->
                        <label class="flex items-start gap-3 p-4 rounded-2xl border cursor-pointer transition-all hover:border-amber-400 border-slate-200 bg-white">
                            <input type="radio" name="payment_method" value="cod" class="mt-1 text-amber-700 focus:ring-amber-500">
                            <div class="flex-1">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-bold text-slate-900 flex items-center gap-1.5">
                                        <i class="fa-solid fa-hand-holding-dollar text-amber-700 text-sm"></i>
                                        <span>Cash on Delivery (COD)</span>
                                    </span>
                                    <span class="text-[10px] text-amber-800 font-bold bg-amber-100 px-2 py-0.5 rounded-full">Pay upon delivery</span>
                                </div>
                                <p class="text-[11px] text-slate-500 mt-1">Hand over cash or scan delivery partner's QR at your door.</p>
                            </div>
                        </label>
                    </div>
                </div>

            </div>

            <!-- Right Column: Order Review & Confirmation -->
            <div class="lg:col-span-5 bg-white rounded-3xl p-6 sm:p-8 border border-amber-100 shadow-sm space-y-6">
                <h3 class="font-serif-heading font-bold text-lg text-slate-900 border-b border-slate-100 pb-3">Your Basket Review</h3>

                <!-- Items list -->
                <div class="space-y-4 max-h-80 overflow-y-auto divide-y divide-slate-100">
                    @foreach($items as $item)
                        <div class="pt-3 first:pt-0 flex items-center gap-3">
                            <img src="{{ asset($item['image']) }}" alt="{{ $item['name'] }}" class="w-14 h-14 rounded-xl object-cover border border-amber-100 bg-amber-50 shrink-0">
                            <div class="flex-1 min-w-0">
                                <h4 class="text-xs font-bold text-slate-900 truncate">{{ $item['name'] }}</h4>
                                <p class="text-[11px] text-amber-800 font-semibold">{{ $item['weight'] }} × {{ $item['quantity'] }}</p>
                            </div>
                            <span class="text-xs font-extrabold text-slate-900">₹{{ number_format($item['total'], 2) }}</span>
                        </div>
                    @endforeach
                </div>

                <!-- Price Breakdown -->
                <div class="pt-4 border-t border-slate-200 space-y-2.5 text-xs text-slate-600 font-medium">
                    <div class="flex justify-between">
                        <span>Items Subtotal</span>
                        <span class="font-bold text-slate-900">₹{{ number_format($subtotal, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Shipping Fee</span>
                        <span class="font-bold {{ $shipping == 0 ? 'text-emerald-700' : 'text-slate-900' }}">
                            {{ $shipping == 0 ? 'FREE' : '₹' . number_format($shipping, 2) }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span>GST / Taxes</span>
                        <span class="font-bold text-emerald-700">Included</span>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-200 flex justify-between items-baseline">
                    <span class="font-bold text-sm text-slate-900">Total Payable</span>
                    <span class="font-extrabold text-2xl text-amber-900">₹{{ number_format($total, 2) }}</span>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full py-4 rounded-2xl bg-gradient-to-r from-amber-700 to-amber-900 hover:from-amber-800 hover:to-amber-950 text-white font-bold text-sm shadow-xl shadow-amber-900/20 active:scale-95 transition-all flex items-center justify-center gap-2">
                    <i class="fa-solid fa-lock text-xs"></i>
                    <span>Place Order & Pay ₹{{ number_format($total, 2) }}</span>
                </button>

                <div class="p-3 bg-amber-50 rounded-xl border border-amber-200 text-center text-[10px] text-slate-500">
                    <p class="font-bold text-amber-950 mb-0.5"><i class="fa-solid fa-shield-check text-amber-700 mr-1"></i> 100% Risk-Free Guarantee</p>
                    <p>Tamper-proof glass packaging. Replacement guaranteed in case of transit breakage.</p>
                </div>
            </div>

        </div>
    </form>
</div>
@endsection
