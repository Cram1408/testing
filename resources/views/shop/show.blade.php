@extends('layouts.app')

@section('title', $product->name . ' - AchaarG')
@section('meta_description', $product->short_description)

@section('content')
@php
    $defaultVariant = $product->variants->where('is_default', true)->first() ?: $product->variants->first();
@endphp

<!-- Breadcrumbs -->
<div class="bg-amber-50/50 py-3.5 border-b border-amber-200/40 text-xs">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center gap-2 text-slate-500">
        <a href="{{ route('home') }}" class="hover:text-amber-800">Home</a>
        <span>/</span>
        <a href="{{ route('shop.index') }}" class="hover:text-amber-800">Store</a>
        <span>/</span>
        <a href="{{ route('shop.category', $product->category->slug) }}" class="hover:text-amber-800">{{ $product->category->name }}</a>
        <span>/</span>
        <span class="text-slate-900 font-bold truncate max-w-xs">{{ $product->name }}</span>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">

        <!-- Product Image Preview -->
        <div class="lg:col-span-5 space-y-4">
            <div class="relative bg-white rounded-3xl p-4 border border-amber-100 shadow-md overflow-hidden group">
                <img id="mainProductImg" src="{{ asset($product->image ?? 'images/products/mango_pickle.png') }}" alt="{{ $product->name }}" class="w-full h-[420px] object-cover rounded-2xl group-hover:scale-105 transition-transform duration-500">
                
                @if($product->spice_level)
                    <div class="absolute top-8 left-8">
                        <span class="px-3 py-1 rounded-full bg-amber-900/90 text-amber-100 text-xs font-bold shadow-md">
                            <i class="fa-solid fa-pepper-hot text-amber-400 mr-1"></i> {{ $product->spice_level }}
                        </span>
                    </div>
                @endif
            </div>

            <!-- Trust Badges Under Image -->
            <div class="grid grid-cols-3 gap-3 text-center text-slate-600">
                <div class="p-3 bg-white rounded-2xl border border-amber-100/80 shadow-2xs">
                    <i class="fa-solid fa-sun text-amber-700 text-base mb-1"></i>
                    <p class="text-[10px] font-bold text-slate-800">Sun-Matured</p>
                    <p class="text-[9px] text-slate-400">Authentic Taste</p>
                </div>
                <div class="p-3 bg-white rounded-2xl border border-amber-100/80 shadow-2xs">
                    <i class="fa-solid fa-oil-can text-amber-700 text-base mb-1"></i>
                    <p class="text-[10px] font-bold text-slate-800">Kachi Ghani</p>
                    <p class="text-[9px] text-slate-400">Mustard Oil</p>
                </div>
                <div class="p-3 bg-white rounded-2xl border border-amber-100/80 shadow-2xs">
                    <i class="fa-solid fa-shield-halved text-amber-700 text-base mb-1"></i>
                    <p class="text-[10px] font-bold text-slate-800">Glass Jar</p>
                    <p class="text-[9px] text-slate-400">Airtight Seal</p>
                </div>
            </div>
        </div>

        <!-- Product Actions & Variant Picker -->
        <div class="lg:col-span-7 space-y-6">
            <div>
                <div class="flex items-center gap-2 mb-2">
                    <span class="px-2.5 py-0.5 rounded-full bg-amber-100 text-amber-900 text-xs font-bold">{{ $product->category->name }}</span>
                    <div class="flex text-amber-500 text-xs gap-1 ml-2">
                        <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                    </div>
                    <span class="text-xs text-slate-400">(4.9/5 from 420+ reviews)</span>
                </div>

                <h1 class="font-serif-heading text-3xl sm:text-4xl font-extrabold text-slate-900 leading-tight">
                    {{ $product->name }}
                </h1>
                @if($product->tagline)
                    <p class="text-sm font-semibold text-amber-800 mt-1">{{ $product->tagline }}</p>
                @endif
                <p class="text-xs sm:text-sm text-slate-600 mt-3 leading-relaxed">{{ $product->short_description }}</p>
            </div>

            <!-- Price & Discount -->
            <div class="p-4 rounded-2xl bg-amber-50/70 border border-amber-200/70 flex items-baseline gap-3">
                <span class="text-3xl font-extrabold text-amber-900">
                    ₹<span id="detailEffPrice">{{ number_format($defaultVariant->effective_price, 2) }}</span>
                </span>
                @if($defaultVariant->sale_price && $defaultVariant->sale_price < $defaultVariant->price)
                    <span class="text-sm text-slate-400 line-through">
                        ₹<span id="detailOrigPrice">{{ number_format($defaultVariant->price, 2) }}</span>
                    </span>
                    <span id="detailDiscountBadge" class="px-2 py-0.5 rounded-md bg-emerald-100 text-emerald-800 text-xs font-extrabold">
                        Save {{ $defaultVariant->discount_percent }}% OFF
                    </span>
                @endif
                <span class="text-xs text-slate-400 ml-auto font-medium">Inclusive of all taxes</span>
            </div>

            <!-- Interactive Weight Variant Selector -->
            <div>
                <div class="flex justify-between items-center mb-2">
                    <label class="text-xs font-bold uppercase tracking-wider text-slate-700">Choose Weight Size:</label>
                    <span class="text-xs text-slate-500 font-medium">SKU: <span id="detailSku" class="font-mono text-slate-700 font-bold">{{ $defaultVariant->sku }}</span></span>
                </div>
                <div class="grid grid-cols-3 gap-3">
                    @foreach($product->variants as $v)
                        <button type="button" 
                            onclick="selectDetailVariant({{ $v->id }}, {{ $v->effective_price }}, {{ $v->price }}, {{ $v->discount_percent }}, '{{ $v->sku }}', {{ $v->stock_quantity }})"
                            id="detailVarBtn-{{ $v->id }}"
                            class="detail-variant-btn p-3 rounded-2xl border text-center transition-all {{ $v->id === $defaultVariant->id ? 'border-amber-700 bg-amber-100/60 ring-2 ring-amber-700/20 shadow-xs' : 'border-slate-200 bg-white hover:border-amber-400' }}">
                            <p class="text-xs font-extrabold text-slate-900">{{ $v->weight_value }}</p>
                            <p class="text-xs font-bold text-amber-800 mt-0.5">₹{{ number_format($v->effective_price, 2) }}</p>
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- Live Stock Urgency Indicator -->
            <div class="flex items-center gap-2 text-xs font-semibold">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                <span id="detailStockMsg" class="text-slate-700">
                    @if($defaultVariant->stock_quantity > 0)
                        In Stock • Ready to dispatch within 24 hours
                    @else
                        Out of Stock
                    @endif
                </span>
            </div>

            <!-- Quantity & Action Buttons -->
            <div class="space-y-3 pt-2">
                <div class="flex items-center gap-4">
                    <!-- Qty Selector -->
                    <div class="flex items-center border border-slate-300 rounded-xl overflow-hidden bg-white shrink-0">
                        <button type="button" onclick="adjustDetailQty(-1)" class="w-10 h-11 flex items-center justify-center text-slate-600 hover:bg-slate-100 transition-colors font-bold text-sm">-</button>
                        <input type="number" id="detailQtyInput" value="1" min="1" max="{{ $defaultVariant->stock_quantity }}" class="w-12 h-11 text-center font-bold text-xs text-slate-900 focus:outline-none border-x border-slate-200" readonly>
                        <button type="button" onclick="adjustDetailQty(1)" class="w-10 h-11 flex items-center justify-center text-slate-600 hover:bg-slate-100 transition-colors font-bold text-sm">+</button>
                    </div>

                    <!-- Add to Cart -->
                    <button type="button" onclick="addCurrentDetailToCart()" class="flex-1 h-11 rounded-xl bg-gradient-to-r from-amber-700 to-amber-900 hover:from-amber-800 hover:to-amber-950 text-white font-bold text-xs shadow-lg shadow-amber-900/20 active:scale-95 transition-all flex items-center justify-center gap-2">
                        <i class="fa-solid fa-cart-shopping text-sm"></i>
                        <span>Add to Basket</span>
                    </button>

                    <!-- Buy Now Direct Checkout -->
                    <button type="button" onclick="buyNowCurrentDetail()" class="h-11 px-6 rounded-xl bg-amber-500 hover:bg-amber-600 text-slate-950 font-extrabold text-xs shadow-md active:scale-95 transition-all flex items-center justify-center gap-2">
                        <i class="fa-solid fa-bolt text-xs"></i>
                        <span>Buy Now</span>
                    </button>
                </div>
            </div>

            <!-- Delivery & Return info -->
            <div class="border-t border-slate-200 pt-4 space-y-2 text-xs text-slate-600">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-truck text-amber-700 w-4"></i>
                    <span>Free shipping on all orders over ₹499 across India</span>
                </div>
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-shield-heart text-amber-700 w-4"></i>
                    <span>100% Homemade using genuine cold-pressed mustard oil & natural spices</span>
                </div>
            </div>
        </div>

    </div>

    <!-- Product Details Tabs -->
    <div class="mt-16 bg-white rounded-3xl p-8 border border-amber-100 shadow-sm">
        <div class="border-b border-slate-100 flex gap-8 text-sm font-bold text-slate-500 overflow-x-auto">
            <button onclick="switchTab('description')" id="tabBtn-description" class="tab-btn pb-3 text-amber-800 border-b-2 border-amber-700">Detailed Description</button>
            <button onclick="switchTab('ingredients')" id="tabBtn-ingredients" class="tab-btn pb-3 hover:text-slate-800">Pure Ingredients</button>
            <button onclick="switchTab('storage')" id="tabBtn-storage" class="tab-btn pb-3 hover:text-slate-800">Storage & Shelf Life</button>
        </div>

        <div class="pt-6">
            <div id="tabContent-description" class="tab-pane space-y-4 text-xs sm:text-sm text-slate-700 leading-relaxed">
                <p>{{ $product->description }}</p>
                <div class="p-4 rounded-2xl bg-amber-50/60 border border-amber-200/50 mt-4">
                    <h4 class="font-bold text-xs text-amber-950 uppercase tracking-wider mb-1">Serving Recommendation</h4>
                    <p class="text-xs text-slate-600">Pair this authentic delicacy with hot parathas, comforting dal khichdi, poori chole, or curd rice for an instant flavor explosion.</p>
                </div>
            </div>

            <div id="tabContent-ingredients" class="tab-pane hidden space-y-4 text-xs sm:text-sm text-slate-700 leading-relaxed">
                <h4 class="font-bold text-xs uppercase text-slate-400">Pure Natural Ingredients:</h4>
                <p class="bg-slate-50 p-4 rounded-xl border border-slate-200 font-medium text-slate-800">{{ $product->ingredients }}</p>
                <p class="text-xs text-emerald-800 font-semibold"><i class="fa-solid fa-check-circle mr-1"></i> No artificial colors, no acidity regulators, no chemical stabilizers.</p>
            </div>

            <div id="tabContent-storage" class="tab-pane hidden space-y-4 text-xs sm:text-sm text-slate-700 leading-relaxed">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-200">
                        <span class="text-xs font-bold uppercase text-slate-400 block">Best Before</span>
                        <span class="text-sm font-bold text-slate-900 mt-1 block">{{ $product->shelf_life }} from manufacture date</span>
                    </div>
                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-200">
                        <span class="text-xs font-bold uppercase text-slate-400 block">Care Instructions</span>
                        <span class="text-sm font-bold text-slate-900 mt-1 block">{{ $product->storage_instructions }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    @if($relatedProducts->isNotEmpty())
        <div class="mt-16">
            <div class="text-center max-w-xl mx-auto mb-8">
                <span class="text-xs uppercase font-extrabold tracking-widest text-amber-700">More From This Category</span>
                <h2 class="font-serif-heading text-2xl sm:text-3xl font-extrabold text-slate-900 mt-1">You May Also Relish</h2>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($relatedProducts as $rel)
                    @php
                        $relDef = $rel->variants->where('is_default', true)->first() ?: $rel->variants->first();
                    @endphp
                    <div class="bg-white rounded-3xl p-5 border border-amber-100 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col justify-between">
                        <a href="{{ route('shop.show', $rel->slug) }}" class="block w-full h-44 rounded-2xl overflow-hidden bg-amber-50 mb-3">
                            <img src="{{ asset($rel->image ?? 'images/products/mango_pickle.png') }}" alt="{{ $rel->name }}" class="w-full h-full object-cover hover:scale-105 transition-transform duration-500">
                        </a>
                        <div>
                            <span class="text-[10px] font-bold uppercase text-amber-700">{{ $rel->category->name }}</span>
                            <h4 class="font-serif-heading font-bold text-sm text-slate-900 line-clamp-1 mt-0.5">
                                <a href="{{ route('shop.show', $rel->slug) }}">{{ $rel->name }}</a>
                            </h4>
                        </div>
                        <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between">
                            <span class="text-sm font-extrabold text-amber-900">₹{{ number_format($relDef->effective_price, 2) }}</span>
                            <a href="{{ route('shop.show', $rel->slug) }}" class="px-3 py-1.5 rounded-lg bg-amber-100 hover:bg-amber-200 text-amber-900 text-xs font-bold transition-colors">
                                View Size
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

</div>

@push('scripts')
<script>
    let activeVariantId = {{ $defaultVariant->id }};

    function selectDetailVariant(variantId, effPrice, origPrice, discountPct, sku, stock) {
        activeVariantId = variantId;

        // Button state
        document.querySelectorAll('.detail-variant-btn').forEach(btn => {
            btn.className = 'detail-variant-btn p-3 rounded-2xl border text-center transition-all border-slate-200 bg-white hover:border-amber-400';
        });
        const currentBtn = document.getElementById('detailVarBtn-' + variantId);
        if (currentBtn) {
            currentBtn.className = 'detail-variant-btn p-3 rounded-2xl border text-center transition-all border-amber-700 bg-amber-100/60 ring-2 ring-amber-700/20 shadow-xs';
        }

        // Live prices
        document.getElementById('detailEffPrice').textContent = Number(effPrice).toFixed(2);
        const origEl = document.getElementById('detailOrigPrice');
        if (origEl) origEl.textContent = Number(origPrice).toFixed(2);

        const discountBadge = document.getElementById('detailDiscountBadge');
        if (discountBadge) {
            if (discountPct > 0) {
                discountBadge.textContent = `Save ${discountPct}% OFF`;
                discountBadge.style.display = 'inline-block';
            } else {
                discountBadge.style.display = 'none';
            }
        }

        document.getElementById('detailSku').textContent = sku;

        const stockMsg = document.getElementById('detailStockMsg');
        if (stockMsg) {
            stockMsg.textContent = stock > 0 ? `In Stock (${stock} jars available)` : 'Out of Stock';
        }
    }

    function adjustDetailQty(delta) {
        const input = document.getElementById('detailQtyInput');
        let current = parseInt(input.value) || 1;
        current = Math.max(1, current + delta);
        input.value = current;
    }

    function addCurrentDetailToCart() {
        const qty = parseInt(document.getElementById('detailQtyInput').value) || 1;
        addToCart(activeVariantId, qty);
    }

    function buyNowCurrentDetail() {
        const qty = parseInt(document.getElementById('detailQtyInput').value) || 1;
        fetch("{{ route('cart.add') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                "Accept": "application/json"
            },
            body: JSON.stringify({ variant_id: activeVariantId, quantity: qty })
        })
        .then(res => res.json())
        .then(data => {
            window.location.href = "{{ route('checkout.index') }}";
        })
        .catch(err => {
            console.error(err);
            window.location.href = "{{ route('checkout.index') }}";
        });
    }

    function switchTab(tabName) {
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.className = 'tab-btn pb-3 hover:text-slate-800 text-slate-500';
        });
        document.getElementById('tabBtn-' + tabName).className = 'tab-btn pb-3 text-amber-800 border-b-2 border-amber-700 font-bold';

        document.querySelectorAll('.tab-pane').forEach(pane => {
            pane.classList.add('hidden');
        });
        document.getElementById('tabContent-' + tabName).classList.remove('hidden');
    }
</script>
@endpush
@endsection
