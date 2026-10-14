@extends('layouts.app')

@section('title', 'AchaarG - Authentic Homemade Pickles & Royal Mukhwas')

@section('content')
<!-- Hero Section -->
<section class="relative bg-gradient-to-b from-[#FFFBEB] via-[#FFFDF7] to-[#FDFBF7] overflow-hidden pt-6 pb-16 lg:py-20 border-b border-amber-200/40">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            
            <!-- Left Hero Content -->
            <div class="lg:col-span-7 space-y-6 text-center lg:text-left">
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-amber-100/80 border border-amber-300 text-amber-900 text-xs font-bold tracking-wide shadow-xs">
                    <span class="w-2 h-2 rounded-full bg-amber-600 animate-pulse"></span>
                    <span>100% Pure Homemade • Handcrafted in Small Batches</span>
                </div>

                <h1 class="font-serif-heading text-4xl sm:text-5xl lg:text-6xl font-extrabold text-slate-900 tracking-tight leading-[1.15]">
                    The Soul of Tradition in <span class="text-amber-800 underline decoration-amber-400 decoration-wavy underline-offset-8">Every Bite</span>
                </h1>

                <p class="text-base sm:text-lg text-slate-600 max-w-xl mx-auto lg:mx-0 leading-relaxed font-normal">
                    Experience sun-cured pickles aged slowly in earthenware barnis with pure cold-pressed mustard oil, and royal after-meal mukhwas crafted with natural spices.
                </p>

                <!-- Action CTAs -->
                <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4 pt-2">
                    <a href="{{ route('shop.category', 'traditional-pickles') }}" class="w-full sm:w-auto px-8 py-4 rounded-2xl bg-gradient-to-r from-amber-700 to-amber-900 hover:from-amber-800 hover:to-amber-950 text-white font-bold text-sm shadow-xl shadow-amber-900/20 hover:shadow-2xl hover:-translate-y-0.5 transition-all text-center flex items-center justify-center gap-2 group">
                        <span>Shop Pickles (Achar)</span>
                        <i class="fa-solid fa-arrow-right text-xs group-hover:translate-x-1 transition-transform"></i>
                    </a>
                    <a href="{{ route('shop.category', 'royal-mukhwas') }}" class="w-full sm:w-auto px-8 py-4 rounded-2xl bg-amber-50 hover:bg-amber-100 text-amber-900 border border-amber-300 font-bold text-sm shadow-xs transition-all text-center flex items-center justify-center gap-2">
                        <i class="fa-solid fa-leaf text-amber-700"></i>
                        <span>Explore Royal Mukhwas</span>
                    </a>
                </div>

                <!-- Trust Badges Bar -->
                <div class="pt-6 grid grid-cols-2 sm:grid-cols-4 gap-4 border-t border-amber-200/60 text-slate-700">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-amber-100 text-amber-800 flex items-center justify-center shrink-0 text-sm">
                            <i class="fa-solid fa-sun"></i>
                        </div>
                        <span class="text-xs font-semibold leading-tight">Sun-Matured 30+ Days</span>
                    </div>
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-amber-100 text-amber-800 flex items-center justify-center shrink-0 text-sm">
                            <i class="fa-solid fa-bottle-droplet"></i>
                        </div>
                        <span class="text-xs font-semibold leading-tight">Cold-Pressed Mustard Oil</span>
                    </div>
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-amber-100 text-amber-800 flex items-center justify-center shrink-0 text-sm">
                            <i class="fa-solid fa-seedling"></i>
                        </div>
                        <span class="text-xs font-semibold leading-tight">Zero Artificial Preservatives</span>
                    </div>
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-amber-100 text-amber-800 flex items-center justify-center shrink-0 text-sm">
                            <i class="fa-solid fa-box-check"></i>
                        </div>
                        <span class="text-xs font-semibold leading-tight">Glass Jar Safety Sealed</span>
                    </div>
                </div>
            </div>

            <!-- Right Hero Visual Banner -->
            <div class="lg:col-span-5 relative">
                <div class="relative mx-auto max-w-md lg:max-w-none">
                    <!-- Glow effect -->
                    <div class="absolute -inset-4 bg-gradient-to-r from-amber-400 to-amber-600 rounded-3xl opacity-20 blur-2xl"></div>

                    <!-- Image frame -->
                    <div class="relative rounded-3xl overflow-hidden shadow-2xl border-4 border-white bg-amber-50">
                        <img src="{{ asset('images/banners/hero_banner.png') }}" alt="Artisanal Indian Pickles and Mukhwas" class="w-full h-auto object-cover hover:scale-105 transition-transform duration-700">
                    </div>

                    <!-- Floating Quality Card -->
                    <div class="absolute -bottom-6 -left-6 bg-white/95 backdrop-blur-md p-4 rounded-2xl shadow-xl border border-amber-100 flex items-center gap-3">
                        <div class="w-12 h-12 rounded-xl bg-amber-600 text-white flex items-center justify-center text-xl shrink-0">
                            <i class="fa-solid fa-award"></i>
                        </div>
                        <div>
                            <div class="flex text-amber-500 text-xs gap-1">
                                <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                            </div>
                            <p class="text-xs font-bold text-slate-800 mt-0.5">4.9/5 from 10,000+ Families</p>
                            <p class="text-[10px] text-slate-500">Pure Indian Flavours Delivered</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- Category Showcase Grid -->
<section class="py-16 bg-[#FDFBF7]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end mb-10 gap-4">
            <div>
                <span class="text-xs uppercase font-extrabold tracking-widest text-amber-700">Explore Collections</span>
                <h2 class="font-serif-heading text-3xl font-extrabold text-slate-900 mt-1">Our Heritage Specialties</h2>
            </div>
            <a href="{{ route('shop.index') }}" class="text-xs font-bold text-amber-800 hover:text-amber-950 flex items-center gap-1.5 transition-colors group">
                <span>View Full Catalog</span>
                <i class="fa-solid fa-arrow-right text-[10px] group-hover:translate-x-1 transition-transform"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($categories as $category)
                <a href="{{ route('shop.category', $category->slug) }}" class="group relative rounded-3xl bg-white p-5 border border-amber-100 shadow-sm hover:shadow-xl transition-all duration-300 hover:-translate-y-1.5 flex flex-col justify-between overflow-hidden">
                    <div class="w-full h-44 rounded-2xl overflow-hidden bg-amber-50 mb-4 relative">
                        <img src="{{ asset($category->image ?? 'images/products/mango_pickle.png') }}" alt="{{ $category->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        <span class="absolute top-3 right-3 px-2.5 py-1 rounded-full bg-white/90 backdrop-blur-xs text-[10px] font-bold text-amber-900 shadow-sm">
                            {{ $category->products_count ?? 0 }} {{ Str::plural('Item', $category->products_count ?? 0) }}
                        </span>
                    </div>

                    <div>
                        <h3 class="font-serif-heading font-bold text-lg text-slate-900 group-hover:text-amber-800 transition-colors">
                            {{ $category->name }}
                        </h3>
                        <p class="text-xs text-slate-500 mt-1.5 line-clamp-2 leading-relaxed">
                            {{ $category->description }}
                        </p>
                    </div>

                    <div class="pt-4 mt-3 border-t border-slate-100 flex items-center justify-between text-xs font-bold text-amber-800">
                        <span>Browse Collection</span>
                        <div class="w-7 h-7 rounded-full bg-amber-100 group-hover:bg-amber-600 group-hover:text-white flex items-center justify-center transition-colors">
                            <i class="fa-solid fa-chevron-right text-[10px]"></i>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>

<!-- Best Sellers & Featured Products with Interactive Weight Variant Selector -->
<section class="py-16 bg-gradient-to-b from-[#FFFDF9] to-[#FDFBF7] border-y border-amber-200/40">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-12">
            <span class="text-xs uppercase font-extrabold tracking-widest text-amber-700">Customer Favorites</span>
            <h2 class="font-serif-heading text-3xl sm:text-4xl font-extrabold text-slate-900 mt-1">Our Best Selling Jars</h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-2">Bottled in glass jars with care. Select your desired size to see live pricing.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($bestsellers as $product)
                @php
                    $defaultVar = $product->variants->where('is_default', true)->first() ?: $product->variants->first();
                @endphp
                <div class="bg-white rounded-3xl p-5 border border-amber-100 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col justify-between relative group" id="productCard-{{ $product->id }}">
                    
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

                        <!-- Dynamic Variant Weight Selector Pills -->
                        <div class="mt-4 pt-3 border-t border-slate-100">
                            <label class="block text-[10px] uppercase font-bold text-slate-400 mb-1.5">Select Weight:</label>
                            <div class="flex flex-wrap gap-1.5">
                                @foreach($product->variants as $variant)
                                    <button type="button" 
                                        onclick="selectCardVariant({{ $product->id }}, {{ $variant->id }}, {{ $variant->effective_price }}, {{ $variant->price }}, '{{ $variant->sku }}')"
                                        class="variant-btn-{{ $product->id }} px-2.5 py-1 text-[11px] font-bold rounded-lg border transition-all {{ $variant->id === $defaultVar->id ? 'bg-amber-700 text-white border-amber-700 shadow-xs' : 'bg-slate-50 text-slate-700 border-slate-200 hover:border-amber-400' }}"
                                        data-variant-id="{{ $variant->id }}">
                                        {{ $variant->weight_value }}
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Price & Add To Cart Button -->
                    <div class="mt-5 pt-3 border-t border-slate-100 flex items-center justify-between gap-2">
                        <div>
                            <div class="flex items-baseline gap-1.5">
                                <span class="text-lg font-extrabold text-amber-900">
                                    ₹<span id="priceDisplay-{{ $product->id }}">{{ number_format($defaultVar->effective_price, 2) }}</span>
                                </span>
                                @if($defaultVar->sale_price && $defaultVar->sale_price < $defaultVar->price)
                                    <span class="text-xs text-slate-400 line-through">
                                        ₹<span id="strikeDisplay-{{ $product->id }}">{{ number_format($defaultVar->price, 2) }}</span>
                                    </span>
                                @endif
                            </div>
                            <span class="text-[10px] text-slate-400 block">Taxes included</span>
                        </div>

                        <button type="button" 
                            id="addBtn-{{ $product->id }}"
                            onclick="addToCart(currentSelectedVariant[{{ $product->id }}] || {{ $defaultVar->id }})" 
                            class="px-4 py-2.5 rounded-xl bg-gradient-to-r from-amber-700 to-amber-800 hover:from-amber-800 hover:to-amber-900 text-white font-bold text-xs shadow-md shadow-amber-800/20 active:scale-95 transition-all flex items-center gap-1.5">
                            <i class="fa-solid fa-cart-plus"></i>
                            <span>Add</span>
                        </button>
                    </div>

                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Tradition & Heritage Story Section -->
<section class="py-20 bg-[#FFFDF7] overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            
            <div class="lg:col-span-6 relative">
                <div class="relative rounded-3xl overflow-hidden shadow-2xl border-4 border-amber-100">
                    <img src="{{ asset('images/products/mango_pickle.png') }}" alt="Tradition of Indian Pickles" class="w-full h-[450px] object-cover">
                </div>
                <!-- Mini floating story card -->
                <div class="absolute -bottom-6 -right-6 bg-amber-900 text-amber-100 p-6 rounded-3xl shadow-2xl max-w-xs border border-amber-700">
                    <div class="font-serif-heading text-3xl font-extrabold text-amber-300">30+ Days</div>
                    <p class="text-xs font-medium text-amber-200/80 mt-1">Sun-cured in glazed clay barnis to develop natural lactobacillus tang & rich depth.</p>
                </div>
            </div>

            <div class="lg:col-span-6 space-y-6">
                <span class="text-xs uppercase font-extrabold tracking-widest text-amber-800">The AchaarG Story</span>
                <h2 class="font-serif-heading text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight leading-tight">
                    Preserving Ancient Earthenware Recipes For Modern Homes
                </h2>
                <p class="text-sm text-slate-600 leading-relaxed">
                    Long before commercial factory pickles were made with synthetic vinegars and artificial colours, every Indian family cherished grandmother's earthen jars placed under the scorching summer sun on rooftop terraces.
                </p>
                <p class="text-sm text-slate-600 leading-relaxed">
                    At AchaarG, we honor that sacred culinary ritual. We source raw mangoes directly from local farms, cold-press fresh black mustard seeds for our oil, and grind whole cumin, fenugreek, and ajwain on traditional stone mills.
                </p>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-4">
                    <div class="p-4 rounded-2xl bg-amber-50/70 border border-amber-200/60">
                        <i class="fa-solid fa-jar-wheat text-amber-700 text-lg mb-2"></i>
                        <h4 class="font-bold text-xs text-slate-900">Traditional Clay Barnis</h4>
                        <p class="text-[11px] text-slate-500 mt-1">Slow natural maturation without modern chemical shortcuts.</p>
                    </div>
                    <div class="p-4 rounded-2xl bg-amber-50/70 border border-amber-200/60">
                        <i class="fa-solid fa-mortar-pestle text-amber-700 text-lg mb-2"></i>
                        <h4 class="font-bold text-xs text-slate-900">Stone Ground Spices</h4>
                        <p class="text-[11px] text-slate-500 mt-1">Retaining essential volatile aromatic oils and healing nutrients.</p>
                    </div>
                </div>

                <div class="pt-2">
                    <a href="{{ route('about') }}" class="inline-flex items-center gap-2 text-xs font-bold text-amber-800 hover:text-amber-950 underline underline-offset-4">
                        <span>Read our complete recipe journey</span>
                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </a>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- Customer Reviews Section -->
<section class="py-16 bg-gradient-to-b from-[#FDFBF7] to-[#FFFBEB]/50 border-t border-amber-200/40">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-12">
            <span class="text-xs uppercase font-extrabold tracking-widest text-amber-700">Verified Testimonials</span>
            <h2 class="font-serif-heading text-3xl font-extrabold text-slate-900 mt-1">Loved by 10,000+ Desi Foodies</h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-2">Here is what pickle and mukhwas connoisseurs across India have to say.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="p-6 rounded-3xl bg-white border border-amber-100 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="flex text-amber-400 text-xs gap-1 mb-3">
                        <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                    </div>
                    <p class="text-xs text-slate-700 leading-relaxed italic">
                        "The Mango Pickle brought tears of joy to my grandmother! She said this is the exact aroma of the achar her mother used to make in Rajasthan. Perfectly sour, pungent mustard oil, and crisp mango pieces."
                    </p>
                </div>
                <div class="pt-4 mt-4 border-t border-slate-100 flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full bg-amber-100 text-amber-800 font-bold flex items-center justify-center text-xs">P</div>
                    <div>
                        <h4 class="text-xs font-bold text-slate-900">Pooja Mathur</h4>
                        <p class="text-[10px] text-slate-400">Jaipur, Rajasthan • Verified Buyer</p>
                    </div>
                </div>
            </div>

            <div class="p-6 rounded-3xl bg-white border border-amber-100 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="flex text-amber-400 text-xs gap-1 mb-3">
                        <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                    </div>
                    <p class="text-xs text-slate-700 leading-relaxed italic">
                        "Calcutta Meetha Paan mukhwas is unbelievably fresh. You get the crunch of real betel leaves and fragrant rose gulkand without any harmful chemicals. It has become a mandatory staple after our dinner."
                    </p>
                </div>
                <div class="pt-4 mt-4 border-t border-slate-100 flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full bg-amber-100 text-amber-800 font-bold flex items-center justify-center text-xs">R</div>
                    <div>
                        <h4 class="text-xs font-bold text-slate-900">Rohan Kulkarni</h4>
                        <p class="text-[10px] text-slate-400">Pune, Maharashtra • Verified Buyer</p>
                    </div>
                </div>
            </div>

            <div class="p-6 rounded-3xl bg-white border border-amber-100 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="flex text-amber-400 text-xs gap-1 mb-3">
                        <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                    </div>
                    <p class="text-xs text-slate-700 leading-relaxed italic">
                        "Banarasi Stuffed Red Chilli is insane! Thick, fleshy chillies packed with tangy masala. Glass jar packaging arrived safely bubble-wrapped without a single drop of leak. Ordering 1kg next time."
                    </p>
                </div>
                <div class="pt-4 mt-4 border-t border-slate-100 flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full bg-amber-100 text-amber-800 font-bold flex items-center justify-center text-xs">A</div>
                    <div>
                        <h4 class="text-xs font-bold text-slate-900">Ananya Sen</h4>
                        <p class="text-[10px] text-slate-400">Bengaluru, Karnataka • Verified Buyer</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action Banner -->
<section class="py-16 bg-[#2B170E] text-white text-center relative overflow-hidden">
    <div class="max-w-4xl mx-auto px-4 relative z-10 space-y-4">
        <span class="inline-block px-3 py-1 rounded-full bg-amber-600/40 text-amber-300 text-xs font-bold uppercase tracking-wider">Fast Nationwide Shipping</span>
        <h2 class="font-serif-heading text-3xl sm:text-4xl font-extrabold">Ready to Taste True Indian Heritage?</h2>
        <p class="text-amber-200/80 text-sm max-w-xl mx-auto">Get free shipping on orders above ₹499. Fresh small-batch pickles dispatched in tamper-proof glass packaging.</p>
        <div class="pt-3">
            <a href="{{ route('shop.index') }}" class="inline-block px-8 py-3.5 rounded-2xl bg-amber-600 hover:bg-amber-700 text-white font-bold text-sm shadow-xl hover:shadow-2xl transition-all">
                Explore All Products Now
            </a>
        </div>
    </div>
</section>

@push('scripts')
<script>
    // State storage for selected variant per card
    const currentSelectedVariant = {};

    function selectCardVariant(productId, variantId, effPrice, origPrice, sku) {
        currentSelectedVariant[productId] = variantId;

        // Update active class on buttons
        const card = document.getElementById('productCard-' + productId);
        if (card) {
            const btns = card.querySelectorAll('.variant-btn-' + productId);
            btns.forEach(btn => {
                if (btn.getAttribute('data-variant-id') == variantId) {
                    btn.className = `variant-btn-${productId} px-2.5 py-1 text-[11px] font-bold rounded-lg border bg-amber-700 text-white border-amber-700 shadow-xs transition-all`;
                } else {
                    btn.className = `variant-btn-${productId} px-2.5 py-1 text-[11px] font-bold rounded-lg border bg-slate-50 text-slate-700 border-slate-200 hover:border-amber-400 transition-all`;
                }
            });
        }

        // Update price display
        const priceDisplay = document.getElementById('priceDisplay-' + productId);
        if (priceDisplay) priceDisplay.textContent = Number(effPrice).toFixed(2);

        const strikeDisplay = document.getElementById('strikeDisplay-' + productId);
        if (strikeDisplay) {
            strikeDisplay.textContent = Number(origPrice).toFixed(2);
        }
    }
</script>
@endpush
@endsection
