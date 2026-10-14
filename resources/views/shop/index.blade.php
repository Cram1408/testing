@extends('layouts.app')

@section('title', 'Browse Authentic Pickles & Mukhwas - AchaarG')

@section('content')
<div class="bg-gradient-to-b from-[#FFFBEB]/40 to-[#FDFBF7] py-8 border-b border-amber-200/40">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumbs -->
        <nav class="flex text-xs text-slate-500 mb-4 gap-2 items-center">
            <a href="{{ route('home') }}" class="hover:text-amber-800">Home</a>
            <span>/</span>
            <a href="{{ route('shop.index') }}" class="text-amber-900 font-semibold">Store Catalog</a>
            @if($currentCategory)
                <span>/</span>
                <span class="text-slate-800 font-bold">{{ $currentCategory->name }}</span>
            @endif
        </nav>

        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <h1 class="font-serif-heading text-3xl sm:text-4xl font-extrabold text-slate-900">
                    {{ $currentCategory ? $currentCategory->name : 'All Handcrafted Pickles & Mukhwas' }}
                </h1>
                <p class="text-xs sm:text-sm text-slate-600 mt-1 max-w-2xl">
                    {{ $currentCategory ? $currentCategory->description : 'Explore small-batch pickles made with raw ingredients and cold-pressed oil, alongside aromatic digestive mouth fresheners.' }}
                </p>
            </div>
            <div class="text-xs font-semibold text-slate-500">
                Showing <strong class="text-slate-900">{{ $products->total() }}</strong> total varieties
            </div>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        
        <!-- Filter Sidebar -->
        <aside class="space-y-6">
            <div class="bg-white p-6 rounded-3xl border border-amber-100 shadow-sm space-y-6">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h3 class="font-bold text-sm text-slate-900 flex items-center gap-2">
                        <i class="fa-solid fa-sliders text-amber-700"></i>
                        <span>Filters</span>
                    </h3>
                    @if(request()->hasAny(['category', 'q', 'spice', 'sort']))
                        <a href="{{ route('shop.index') }}" class="text-[11px] font-bold text-rose-600 hover:underline">Reset All</a>
                    @endif
                </div>

                <!-- Categories -->
                <div>
                    <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">Categories</h4>
                    <ul class="space-y-1.5 text-xs">
                        <li>
                            <a href="{{ route('shop.index', array_merge(request()->except('category', 'page'))) }}" 
                               class="flex items-center justify-between px-3 py-2 rounded-xl transition-all {{ !request('category') ? 'bg-amber-100/70 text-amber-900 font-bold' : 'text-slate-600 hover:bg-slate-50' }}">
                                <span>All Categories</span>
                                <span class="text-[10px] bg-slate-100 text-slate-500 px-2 py-0.5 rounded-full font-bold">{{ \App\Models\Product::count() }}</span>
                            </a>
                        </li>
                        @foreach($categories as $cat)
                            <li>
                                <a href="{{ route('shop.index', array_merge(request()->except('page'), ['category' => $cat->slug])) }}" 
                                   class="flex items-center justify-between px-3 py-2 rounded-xl transition-all {{ request('category') === $cat->slug ? 'bg-amber-100/70 text-amber-900 font-bold' : 'text-slate-600 hover:bg-slate-50' }}">
                                    <span>{{ $cat->name }}</span>
                                    <span class="text-[10px] bg-slate-100 text-slate-500 px-2 py-0.5 rounded-full font-bold">{{ $cat->products_count }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Spice Level -->
                <div>
                    <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">Spice Profile</h4>
                    <div class="flex flex-wrap gap-1.5">
                        @foreach(['Mild', 'Medium', 'Spicy', 'Extra Spicy', 'Sweet & Tangy'] as $spice)
                            <a href="{{ route('shop.index', array_merge(request()->except('page'), ['spice' => request('spice') === $spice ? null : $spice])) }}"
                               class="px-2.5 py-1 rounded-lg text-xs font-semibold border transition-all {{ request('spice') === $spice ? 'bg-amber-800 text-white border-amber-800' : 'bg-slate-50 text-slate-700 border-slate-200 hover:border-amber-400' }}">
                                {{ $spice }}
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- Sort Control -->
                <div>
                    <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Sort By</h4>
                    <form method="GET" action="{{ route('shop.index') }}">
                        @foreach(request()->except('sort', 'page') as $k => $v)
                            <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                        @endforeach
                        <select name="sort" onchange="this.form.submit()" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-amber-500">
                            <option value="popular" {{ request('sort') === 'popular' ? 'selected' : '' }}>Popular & Best Sellers</option>
                            <option value="price_low" {{ request('sort') === 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="price_high" {{ request('sort') === 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                            <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>Newest Arrivals</option>
                        </select>
                    </form>
                </div>

                <!-- Free Shipping Banner -->
                <div class="p-4 rounded-2xl bg-gradient-to-br from-amber-50 to-amber-100/60 border border-amber-200 text-center">
                    <i class="fa-solid fa-truck-fast text-2xl text-amber-700 mb-2"></i>
                    <h5 class="text-xs font-bold text-amber-950">Free Express Delivery</h5>
                    <p class="text-[11px] text-amber-800/80 mt-1">On all domestic orders over ₹499 across India.</p>
                </div>
            </div>
        </aside>

        <!-- Product Grid -->
        <main class="lg:col-span-3">
            @if($products->isEmpty())
                <div class="bg-white rounded-3xl p-12 text-center border border-amber-100 shadow-sm">
                    <div class="w-16 h-16 rounded-2xl bg-amber-50 text-amber-700 mx-auto flex items-center justify-center text-3xl mb-4">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </div>
                    <h3 class="font-serif-heading text-xl font-bold text-slate-900">No items match your criteria</h3>
                    <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">Try clearing selected filters or search for another delicious pickle recipe.</p>
                    <a href="{{ route('shop.index') }}" class="mt-5 inline-block px-6 py-2.5 bg-amber-700 hover:bg-amber-800 text-white text-xs font-bold rounded-xl transition-colors">
                        Reset Filters
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($products as $product)
                        @php
                            $defaultVar = $product->variants->where('is_default', true)->first() ?: $product->variants->first();
                        @endphp
                        <div class="bg-white rounded-3xl p-5 border border-amber-100 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col justify-between relative group" id="shopCard-{{ $product->id }}">
                            
                            <!-- Badges -->
                            <div class="absolute top-8 left-8 z-10 flex flex-col gap-1.5">
                                @if($product->spice_level)
                                    <span class="px-2.5 py-0.5 rounded-full bg-amber-900/90 text-amber-100 text-[10px] font-bold shadow-xs">
                                        <i class="fa-solid fa-pepper-hot text-amber-400 mr-1"></i> {{ $product->spice_level }}
                                    </span>
                                @endif
                                @if($product->is_bestseller)
                                    <span class="px-2.5 py-0.5 rounded-full bg-emerald-800 text-white text-[10px] font-bold shadow-xs">
                                        Bestseller
                                    </span>
                                @endif
                            </div>

                            <!-- Image -->
                            <a href="{{ route('shop.show', $product->slug) }}" class="block w-full h-52 rounded-2xl overflow-hidden bg-amber-50 mb-4 relative">
                                <img src="{{ asset($product->image ?? 'images/products/mango_pickle.png') }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            </a>

                            <!-- Title & Tagline -->
                            <div>
                                <span class="text-[10px] font-bold uppercase tracking-wider text-amber-700">{{ $product->category->name }}</span>
                                <h3 class="font-serif-heading font-bold text-base text-slate-900 line-clamp-1 mt-0.5 group-hover:text-amber-800 transition-colors">
                                    <a href="{{ route('shop.show', $product->slug) }}">{{ $product->name }}</a>
                                </h3>
                                <p class="text-xs text-slate-500 line-clamp-2 mt-1 leading-relaxed">{{ $product->short_description }}</p>

                                <!-- Variant Pills -->
                                <div class="mt-4 pt-3 border-t border-slate-100">
                                    <label class="block text-[10px] uppercase font-bold text-slate-400 mb-1.5">Select Weight:</label>
                                    <div class="flex flex-wrap gap-1.5">
                                        @foreach($product->variants as $variant)
                                            <button type="button" 
                                                onclick="selectShopVariant({{ $product->id }}, {{ $variant->id }}, {{ $variant->effective_price }}, {{ $variant->price }})"
                                                class="shop-var-btn-{{ $product->id }} px-2.5 py-1 text-[11px] font-bold rounded-lg border transition-all {{ $variant->id === $defaultVar->id ? 'bg-amber-700 text-white border-amber-700 shadow-xs' : 'bg-slate-50 text-slate-700 border-slate-200 hover:border-amber-400' }}"
                                                data-variant-id="{{ $variant->id }}">
                                                {{ $variant->weight_value }}
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <!-- Price & Add Button -->
                            <div class="mt-5 pt-3 border-t border-slate-100 flex items-center justify-between gap-2">
                                <div>
                                    <div class="flex items-baseline gap-1.5">
                                        <span class="text-lg font-extrabold text-amber-900">
                                            ₹<span id="shopPrice-{{ $product->id }}">{{ number_format($defaultVar->effective_price, 2) }}</span>
                                        </span>
                                        @if($defaultVar->sale_price && $defaultVar->sale_price < $defaultVar->price)
                                            <span class="text-xs text-slate-400 line-through">
                                                ₹<span id="shopStrike-{{ $product->id }}">{{ number_format($defaultVar->price, 2) }}</span>
                                            </span>
                                        @endif
                                    </div>
                                    <span class="text-[10px] text-slate-400 block">Taxes included</span>
                                </div>

                                <button type="button" 
                                    onclick="addToCart(selectedShopVariant[{{ $product->id }}] || {{ $defaultVar->id }})" 
                                    class="px-4 py-2.5 rounded-xl bg-gradient-to-r from-amber-700 to-amber-800 hover:from-amber-800 hover:to-amber-900 text-white font-bold text-xs shadow-md shadow-amber-800/20 active:scale-95 transition-all flex items-center gap-1.5">
                                    <i class="fa-solid fa-cart-plus"></i>
                                    <span>Add</span>
                                </button>
                            </div>

                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-10">
                    {{ $products->links() }}
                </div>
            @endif
        </main>

    </div>
</div>

@push('scripts')
<script>
    const selectedShopVariant = {};

    function selectShopVariant(productId, variantId, effPrice, origPrice) {
        selectedShopVariant[productId] = variantId;

        const card = document.getElementById('shopCard-' + productId);
        if (card) {
            const btns = card.querySelectorAll('.shop-var-btn-' + productId);
            btns.forEach(btn => {
                if (btn.getAttribute('data-variant-id') == variantId) {
                    btn.className = `shop-var-btn-${productId} px-2.5 py-1 text-[11px] font-bold rounded-lg border bg-amber-700 text-white border-amber-700 shadow-xs transition-all`;
                } else {
                    btn.className = `shop-var-btn-${productId} px-2.5 py-1 text-[11px] font-bold rounded-lg border bg-slate-50 text-slate-700 border-slate-200 hover:border-amber-400 transition-all`;
                }
            });
        }

        const priceDisplay = document.getElementById('shopPrice-' + productId);
        if (priceDisplay) priceDisplay.textContent = Number(effPrice).toFixed(2);

        const strikeDisplay = document.getElementById('shopStrike-' + productId);
        if (strikeDisplay) {
            strikeDisplay.textContent = Number(origPrice).toFixed(2);
        }
    }
</script>
@endpush
@endsection
