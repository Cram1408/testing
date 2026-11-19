@extends('layouts.admin')

@section('title', 'Website Settings - Super Admin')
@section('header_title', 'Website Settings')
@section('header_subtitle', 'Configure store details, top announcement banner, delivery policies, payment modes, and coupons')

@section('content')
<div class="space-y-8 max-w-5xl mx-auto">
    <div class="flex items-center justify-between">
        <div>
            <div class="flex items-center gap-2">
                <span class="px-2.5 py-1 rounded-full bg-amber-500/10 text-amber-900 font-extrabold text-[10px] uppercase tracking-wider border border-amber-200">
                    <i class="fa-solid fa-crown mr-1 text-amber-700"></i> Super Admin Control
                </span>
                <span class="text-xs text-amber-900/60 font-medium">Live storefront parameters</span>
            </div>
            <h2 class="font-serif-heading text-xl font-bold text-amber-950 mt-1">Storefront & System Configuration</h2>
        </div>
    </div>

    <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-8">
        @csrf

        {{-- 1. General Brand & Store Identity --}}
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-amber-200/80 shadow-sm space-y-6">
            <div class="border-b border-amber-100 pb-3">
                <h3 class="font-serif-heading font-bold text-base text-amber-950 flex items-center gap-2">
                    <i class="fa-solid fa-store text-amber-700"></i>
                    <span>Store Identity & Compliance Details</span>
                </h3>
                <p class="text-xs text-amber-900/60">Appears across site headers, contact pages, customer invoices, and email receipts</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-amber-900 uppercase tracking-wider mb-1.5">Store Name *</label>
                    <input type="text" name="store_name" required value="{{ old('store_name', $settings['store_name']) }}"
                        class="w-full bg-amber-50/40 border border-amber-200/80 rounded-xl p-3 text-xs font-semibold text-slate-900 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-600">
                </div>

                <div>
                    <label class="block text-xs font-bold text-amber-900 uppercase tracking-wider mb-1.5">Store Tagline</label>
                    <input type="text" name="store_tagline" value="{{ old('store_tagline', $settings['store_tagline']) }}"
                        class="w-full bg-amber-50/40 border border-amber-200/80 rounded-xl p-3 text-xs font-semibold text-slate-900 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-600">
                </div>

                <div>
                    <label class="block text-xs font-bold text-amber-900 uppercase tracking-wider mb-1.5">Customer Support Email *</label>
                    <input type="email" name="support_email" required value="{{ old('support_email', $settings['support_email']) }}"
                        class="w-full bg-amber-50/40 border border-amber-200/80 rounded-xl p-3 text-xs font-semibold text-slate-900 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-600">
                </div>

                <div>
                    <label class="block text-xs font-bold text-amber-900 uppercase tracking-wider mb-1.5">Customer Support Phone *</label>
                    <input type="text" name="support_phone" required value="{{ old('support_phone', $settings['support_phone']) }}"
                        class="w-full bg-amber-50/40 border border-amber-200/80 rounded-xl p-3 text-xs font-semibold text-slate-900 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-600">
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-amber-900 uppercase tracking-wider mb-1.5">Registered Office Address *</label>
                    <textarea name="store_address" rows="2" required class="w-full bg-amber-50/40 border border-amber-200/80 rounded-xl p-3 text-xs font-semibold text-slate-900 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-600">{{ old('store_address', $settings['store_address']) }}</textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-amber-900 uppercase tracking-wider mb-1.5">GSTIN Registration Number</label>
                    <input type="text" name="gstin_number" value="{{ old('gstin_number', $settings['gstin_number']) }}" placeholder="08AABCA1234F1Z8"
                        class="w-full bg-amber-50/40 border border-amber-200/80 rounded-xl p-3 text-xs font-mono font-bold text-slate-900 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-600">
                </div>

                <div>
                    <label class="block text-xs font-bold text-amber-900 uppercase tracking-wider mb-1.5">FSSAI Food License Number</label>
                    <input type="text" name="fssai_number" value="{{ old('fssai_number', $settings['fssai_number']) }}" placeholder="12224026000341"
                        class="w-full bg-amber-50/40 border border-amber-200/80 rounded-xl p-3 text-xs font-mono font-bold text-slate-900 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-600">
                </div>
            </div>
        </div>

        {{-- 2. Delivery & Shipping Policies --}}
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-amber-200/80 shadow-sm space-y-6">
            <div class="border-b border-amber-100 pb-3">
                <h3 class="font-serif-heading font-bold text-base text-amber-950 flex items-center gap-2">
                    <i class="fa-solid fa-truck-fast text-amber-700"></i>
                    <span>Delivery & Shipping Policies</span>
                </h3>
                <p class="text-xs text-amber-900/60">Automates cart calculations and checkout fees across the entire website</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-amber-900 uppercase tracking-wider mb-1.5">Free Delivery Min Order (₹) *</label>
                    <input type="number" name="free_shipping_min" step="1" min="0" required value="{{ old('free_shipping_min', $settings['free_shipping_min']) }}"
                        class="w-full bg-amber-50/40 border border-amber-200/80 rounded-xl p-3 text-xs font-mono font-bold text-slate-900 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-600">
                    <p class="text-[10px] text-amber-800/60 mt-1 font-medium">Orders at or above this amount receive free shipping</p>
                </div>

                <div>
                    <label class="block text-xs font-bold text-amber-900 uppercase tracking-wider mb-1.5">Standard Shipping Fee (₹) *</label>
                    <input type="number" name="standard_shipping_fee" step="1" min="0" required value="{{ old('standard_shipping_fee', $settings['standard_shipping_fee']) }}"
                        class="w-full bg-amber-50/40 border border-amber-200/80 rounded-xl p-3 text-xs font-mono font-bold text-slate-900 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-600">
                    <p class="text-[10px] text-amber-800/60 mt-1 font-medium">Applied to orders below the free delivery minimum</p>
                </div>

                <div>
                    <label class="block text-xs font-bold text-amber-900 uppercase tracking-wider mb-1.5">Delivery Time Estimate</label>
                    <input type="text" name="delivery_estimate" value="{{ old('delivery_estimate', $settings['delivery_estimate']) }}" placeholder="3 - 5 business days"
                        class="w-full bg-amber-50/40 border border-amber-200/80 rounded-xl p-3 text-xs font-semibold text-slate-900 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-600">
                </div>
            </div>
        </div>

        {{-- 3. Top Announcement Bar --}}
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-amber-200/80 shadow-sm space-y-6">
            <div class="border-b border-amber-100 pb-3 flex items-center justify-between">
                <div>
                    <h3 class="font-serif-heading font-bold text-base text-amber-950 flex items-center gap-2">
                        <i class="fa-solid fa-bullhorn text-amber-700"></i>
                        <span>Announcement Bar (Top Header)</span>
                    </h3>
                    <p class="text-xs text-amber-900/60">Displayed at the top of every page on the live website</p>
                </div>
                <label class="flex items-center gap-2 text-xs font-bold text-amber-950 cursor-pointer">
                    <input type="checkbox" name="announcement_active" value="1" {{ !empty($settings['announcement_active']) ? 'checked' : '' }} class="rounded text-amber-600 focus:ring-amber-500">
                    <span>Show Announcement</span>
                </label>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-amber-900 uppercase tracking-wider mb-1.5">Pill Badge Text</label>
                    <input type="text" name="announcement_badge" value="{{ old('announcement_badge', $settings['announcement_badge']) }}" placeholder="Dadi's Secret"
                        class="w-full bg-amber-50/40 border border-amber-200/80 rounded-xl p-3 text-xs font-semibold text-slate-900 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-600">
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-amber-900 uppercase tracking-wider mb-1.5">Announcement Message</label>
                    <input type="text" name="announcement_text" value="{{ old('announcement_text', $settings['announcement_text']) }}" placeholder="Sun-Dried Pickles in Kachi Ghani Mustard Oil & Royal Digestive Mukhwas"
                        class="w-full bg-amber-50/40 border border-amber-200/80 rounded-xl p-3 text-xs font-semibold text-slate-900 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-600">
                </div>
            </div>
        </div>

        {{-- 4. Payment Gateways & Options --}}
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-amber-200/80 shadow-sm space-y-6">
            <div class="border-b border-amber-100 pb-3">
                <h3 class="font-serif-heading font-bold text-base text-amber-950 flex items-center gap-2">
                    <i class="fa-solid fa-credit-card text-amber-700"></i>
                    <span>Payment Methods at Checkout</span>
                </h3>
                <p class="text-xs text-amber-900/60">Toggle which payment channels customers can use</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <label class="p-4 bg-amber-50/30 border border-amber-200/80 rounded-2xl flex items-center gap-3 cursor-pointer hover:bg-amber-50/70 transition-colors">
                    <input type="checkbox" name="enable_cod" value="1" {{ !empty($settings['enable_cod']) ? 'checked' : '' }} class="rounded text-amber-600 focus:ring-amber-500">
                    <div>
                        <span class="font-bold text-xs text-amber-950 block">Cash on Delivery (COD)</span>
                        <span class="text-[11px] text-amber-900/60 font-medium">Allow customers to pay in cash upon doorstep delivery</span>
                    </div>
                </label>

                <label class="p-4 bg-amber-50/30 border border-amber-200/80 rounded-2xl flex items-center gap-3 cursor-pointer hover:bg-amber-50/70 transition-colors">
                    <input type="checkbox" name="enable_online_upi" value="1" {{ !empty($settings['enable_online_upi']) ? 'checked' : '' }} class="rounded text-amber-600 focus:ring-amber-500">
                    <div>
                        <span class="font-bold text-xs text-amber-950 block">Online UPI & Cards</span>
                        <span class="text-[11px] text-amber-900/60 font-medium">Accept Google Pay, PhonePe, Paytm, and Cards</span>
                    </div>
                </label>
            </div>
        </div>

        {{-- 5. Promotional Coupons & Discounts --}}
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-amber-200/80 shadow-sm space-y-6">
            <div class="border-b border-amber-100 pb-3 flex items-center justify-between">
                <div>
                    <h3 class="font-serif-heading font-bold text-base text-amber-950 flex items-center gap-2">
                        <i class="fa-solid fa-ticket text-amber-700"></i>
                        <span>Active Coupons & Discounts</span>
                    </h3>
                    <p class="text-xs text-amber-900/60">Manage promotional codes available for customer orders</p>
                </div>
            </div>

            {{-- Existing Coupons List --}}
            <div class="space-y-3">
                @if(empty($settings['coupons']))
                    <p class="text-xs text-amber-800/60 italic">No coupons currently configured.</p>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @foreach($settings['coupons'] as $cpn)
                            <div class="p-4 bg-amber-50/50 border border-amber-200 rounded-2xl flex items-center justify-between gap-3">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="font-mono font-extrabold text-xs text-amber-950 bg-white px-2 py-0.5 rounded border border-amber-300">
                                            {{ $cpn['code'] }}
                                        </span>
                                        <span class="text-[10px] font-bold uppercase px-1.5 py-0.5 rounded bg-amber-100 text-amber-900 border border-amber-200">
                                            {{ $cpn['discount_type'] === 'percentage' ? $cpn['value'] . '% OFF' : '₹' . $cpn['value'] . ' OFF' }}
                                        </span>
                                    </div>
                                    <p class="text-[11px] text-amber-900/80 font-medium mt-1">{{ $cpn['description'] ?? 'Promotional discount' }}</p>
                                    @if(!empty($cpn['min_order']) && $cpn['min_order'] > 0)
                                        <p class="text-[10px] text-amber-800/60 font-medium">Min. cart amount: ₹{{ $cpn['min_order'] }}</p>
                                    @endif
                                </div>

                                <a href="{{ route('admin.settings.coupon.remove', $cpn['code']) }}" onclick="return confirm('Remove coupon {{ $cpn['code'] }}?');" class="p-2 text-amber-400 hover:text-rose-600 transition-colors" title="Delete Coupon">
                                    <i class="fa-solid fa-trash-can text-xs"></i>
                                </a>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Create New Coupon Sub-Form --}}
            <div class="p-4 bg-amber-50/40 border border-amber-200/80 rounded-2xl space-y-3">
                <span class="text-xs font-bold text-amber-900 uppercase tracking-wider block">Add New Coupon Code</span>
                <div class="grid grid-cols-1 sm:grid-cols-4 gap-3">
                    <div>
                        <input type="text" name="new_coupon_code" placeholder="e.g. FESTIVE20" class="w-full bg-white border border-amber-200/80 rounded-xl p-2.5 text-xs font-mono font-bold uppercase text-slate-900 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-600">
                    </div>
                    <div>
                        <select name="new_coupon_type" class="w-full bg-white border border-amber-200/80 rounded-xl p-2.5 text-xs font-bold text-slate-900 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-600">
                            <option value="percentage">Percentage Discount (%)</option>
                            <option value="flat">Flat Amount Discount (₹)</option>
                        </select>
                    </div>
                    <div>
                        <input type="number" name="new_coupon_val" min="1" step="1" placeholder="Value (e.g. 15)" class="w-full bg-white border border-amber-200/80 rounded-xl p-2.5 text-xs font-mono font-bold text-slate-900 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-600">
                    </div>
                    <div>
                        <input type="number" name="new_coupon_min" min="0" step="1" placeholder="Min. Order (₹)" class="w-full bg-white border border-amber-200/80 rounded-xl p-2.5 text-xs font-mono text-slate-900 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-600">
                    </div>
                </div>
            </div>
        </div>

        {{-- Submit Button --}}
        <div class="flex items-center justify-end gap-4 pt-2">
            <button type="submit" class="px-8 py-3.5 rounded-xl bg-gradient-to-r from-amber-700 to-amber-900 hover:from-amber-800 hover:to-amber-950 text-white font-bold text-xs shadow-md shadow-amber-900/20 hover:shadow-lg transition-all flex items-center gap-2">
                <i class="fa-solid fa-floppy-disk text-xs"></i>
                <span>Save All Website Settings</span>
            </button>
        </div>
    </form>
</div>
@endsection
