<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-[#FDFBF7]">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Swad Desi - Authentic Homemade Pickles & Royal Mukhwas')</title>
    <meta name="description" content="@yield('meta_description', 'Handcrafted traditional Indian pickles cured in cold-pressed mustard oil and royal artisanal mouth fresheners prepared with heirloom recipes.')">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;0,800;1,600&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Tailwind CSS (CDN for guaranteed instant styling across environments) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#FFFBEB',
                            100: '#FEF3C7',
                            200: '#FDE68A',
                            300: '#FCD34D',
                            400: '#FBBF24',
                            500: '#F59E0B',
                            600: '#D97706',
                            700: '#B45309',
                            800: '#92400E',
                            900: '#78350F',
                        },
                        spice: {
                            50: '#FFF7ED',
                            100: '#FFEDD5',
                            500: '#F97316',
                            600: '#EA580C',
                            700: '#C2410C',
                            800: '#9A3412',
                        },
                        forest: {
                            50: '#F0FDF4',
                            100: '#DCFCE7',
                            600: '#16A34A',
                            700: '#15803D',
                            800: '#166534',
                            900: '#14532D',
                        },
                        parchment: '#FDFBF7',
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                        serif: ['"Playfair Display"', 'serif'],
                    }
                }
            }
        }
    </script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #FDFBF7;
            color: #1F2937;
        }
        .font-serif-heading {
            font-family: 'Playfair Display', serif;
        }
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #FDFBF7;
        }
        ::-webkit-scrollbar-thumb {
            background: #D97706;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #B45309;
        }
    </style>

    @stack('styles')
</head>
<body class="flex flex-col min-h-screen text-slate-800 antialiased selection:bg-amber-500 selection:text-white">

    <!-- Top Announcement Bar -->
    <div class="bg-gradient-to-r from-amber-700 via-amber-800 to-amber-900 text-amber-100 text-xs py-2 px-4 shadow-sm border-b border-amber-600/30">
        <div class="max-w-7xl mx-auto flex flex-col sm:flex-row justify-between items-center gap-1 text-center sm:text-left">
            <div class="flex items-center gap-2 font-medium">
                <span class="inline-block px-2 py-0.5 rounded-full bg-amber-500/20 text-amber-300 text-[10px] uppercase tracking-wider font-bold border border-amber-400/30">Dadi's Secret</span>
                <span>Sun-Dried Pickles in Kachi Ghani Mustard Oil & Royal Digestive Mukhwas</span>
            </div>
            <div class="flex items-center gap-4 text-xs">
                <span class="hidden md:inline-flex items-center gap-1.5"><i class="fa-solid fa-truck-fast text-amber-300"></i> Free Delivery above ₹499</span>
                <span class="hidden md:inline-block text-amber-500/60">•</span>
                <a href="{{ route('orders.track.index') }}" class="hover:text-amber-300 transition-colors flex items-center gap-1 underline underline-offset-2">
                    <i class="fa-solid fa-map-location-dot"></i> Track Order
                </a>
                <span class="text-amber-500/60">•</span>
                <a href="tel:+919876543210" class="hover:text-amber-300 transition-colors flex items-center gap-1 font-semibold">
                    <i class="fa-solid fa-phone text-[10px]"></i> +91 98765 43210
                </a>
            </div>
        </div>
    </div>

    <!-- Main Navigation Header -->
    <header class="sticky top-0 z-40 bg-[#FFFDF9]/95 backdrop-blur-md border-b border-amber-200/50 shadow-sm transition-all duration-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20 gap-4">

                <!-- Logo & Brand -->
                <div class="flex items-center gap-3">
                    <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-amber-600 to-amber-800 flex items-center justify-center text-white shadow-md shadow-amber-800/20 group-hover:scale-105 transition-transform border border-amber-400/40">
                            <i class="fa-solid fa-jar text-2xl text-amber-100"></i>
                        </div>
                        <div class="flex flex-col">
                            <span class="font-serif-heading text-2xl font-bold tracking-tight text-amber-950 group-hover:text-amber-700 transition-colors leading-tight">
                                Swad Desi
                            </span>
                            <span class="text-[10px] tracking-widest uppercase font-semibold text-amber-700">
                                Pickles & Mukhwas
                            </span>
                        </div>
                    </a>
                </div>

                <!-- Desktop Nav Links -->
                <nav class="hidden lg:flex items-center gap-1 text-sm font-semibold text-slate-700">
                    <a href="{{ route('home') }}" class="px-3.5 py-2 rounded-lg hover:text-amber-700 hover:bg-amber-50 transition-colors {{ request()->routeIs('home') ? 'text-amber-700 bg-amber-50 font-bold' : '' }}">
                        Home
                    </a>
                    <a href="{{ route('shop.index') }}" class="px-3.5 py-2 rounded-lg hover:text-amber-700 hover:bg-amber-50 transition-colors {{ request()->routeIs('shop.index') && !request('category') ? 'text-amber-700 bg-amber-50 font-bold' : '' }}">
                        All Products
                    </a>
                    <a href="{{ route('shop.category', 'traditional-pickles') }}" class="px-3.5 py-2 rounded-lg hover:text-amber-700 hover:bg-amber-50 transition-colors {{ request('category') === 'traditional-pickles' ? 'text-amber-700 bg-amber-50 font-bold' : '' }}">
                        Achar (Pickles)
                    </a>
                    <a href="{{ route('shop.category', 'royal-mukhwas') }}" class="px-3.5 py-2 rounded-lg hover:text-amber-700 hover:bg-amber-50 transition-colors {{ request('category') === 'royal-mukhwas' ? 'text-amber-700 bg-amber-50 font-bold' : '' }}">
                        Royal Mukhwas
                    </a>
                    <a href="{{ route('shop.category', 'sweet-pickles-murabba') }}" class="px-3.5 py-2 rounded-lg hover:text-amber-700 hover:bg-amber-50 transition-colors {{ request('category') === 'sweet-pickles-murabba' ? 'text-amber-700 bg-amber-50 font-bold' : '' }}">
                        Chunda & Murabba
                    </a>
                    <a href="{{ route('about') }}" class="px-3.5 py-2 rounded-lg hover:text-amber-700 hover:bg-amber-50 transition-colors {{ request()->routeIs('about') ? 'text-amber-700 bg-amber-50 font-bold' : '' }}">
                        Our Heritage
                    </a>
                    <a href="{{ route('contact') }}" class="px-3.5 py-2 rounded-lg hover:text-amber-700 hover:bg-amber-50 transition-colors {{ request()->routeIs('contact') ? 'text-amber-700 bg-amber-50 font-bold' : '' }}">
                        Contact
                    </a>
                </nav>

                <!-- Search & Actions -->
                <div class="flex items-center gap-3">
                    <!-- Search Input (Desktop) -->
                    <form action="{{ route('shop.index') }}" method="GET" class="hidden md:flex relative items-center w-52 lg:w-64">
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Search achar, saunf..." 
                            class="w-full bg-amber-50/70 border border-amber-200/80 rounded-full py-1.5 pl-9 pr-4 text-xs font-medium focus:outline-none focus:ring-2 focus:ring-amber-500/30 focus:border-amber-600 transition-all placeholder:text-slate-400">
                        <i class="fa-solid fa-magnifying-glass absolute left-3 text-slate-400 text-xs"></i>
                    </form>

                    <!-- Account / Auth Dropdown -->
                    <div class="relative group">
                        @auth
                            <button class="flex items-center gap-2 p-2 rounded-xl hover:bg-amber-50 text-slate-700 transition-colors border border-amber-200/60 bg-amber-50/40">
                                <div class="w-8 h-8 rounded-lg bg-amber-600 text-white font-bold flex items-center justify-center text-xs shadow-sm">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </div>
                                <span class="hidden sm:inline-block text-xs font-semibold text-slate-800 max-w-[90px] truncate">
                                    {{ auth()->user()->name }}
                                </span>
                                <i class="fa-solid fa-chevron-down text-[10px] text-slate-400"></i>
                            </button>
                            <div class="absolute right-0 mt-1 w-48 bg-white rounded-xl shadow-xl border border-amber-100 py-1.5 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-150 z-50">
                                <div class="px-4 py-2 border-b border-slate-100">
                                    <p class="text-xs font-semibold text-slate-800">{{ auth()->user()->name }}</p>
                                    <p class="text-[10px] text-slate-500 truncate">{{ auth()->user()->email }}</p>
                                </div>
                                @if(auth()->user()->isAdmin())
                                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 px-4 py-2 text-xs font-semibold text-amber-700 hover:bg-amber-50">
                                        <i class="fa-solid fa-screwdriver-wrench w-4 text-amber-600"></i> Admin Panel
                                    </a>
                                @endif
                                <a href="{{ route('customer.dashboard') }}" class="flex items-center gap-2 px-4 py-2 text-xs text-slate-700 hover:bg-amber-50">
                                    <i class="fa-solid fa-box-open w-4 text-slate-500"></i> My Orders & Profile
                                </a>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center gap-2 px-4 py-2 text-xs text-rose-600 hover:bg-rose-50 text-left">
                                        <i class="fa-solid fa-arrow-right-from-bracket w-4 text-rose-500"></i> Log Out
                                    </button>
                                </form>
                            </div>
                        @else
                            <a href="{{ route('login') }}" class="flex items-center gap-1.5 px-3 py-2 text-xs font-semibold text-slate-700 hover:text-amber-700 hover:bg-amber-50 rounded-xl transition-colors border border-amber-200/60 bg-amber-50/40">
                                <i class="fa-regular fa-user text-sm"></i>
                                <span class="hidden sm:inline">Sign In</span>
                            </a>
                        @endauth
                    </div>

                    <!-- Cart Drawer Trigger Button -->
                    @php
                        $cartService = app(\App\Services\CartService::class);
                        $cartCount = $cartService->getCount();
                        $cartSubtotal = $cartService->getSubtotal();
                    @endphp
                    <button id="openCartBtn" onclick="toggleCartDrawer(true)" class="relative flex items-center gap-2 px-3.5 py-2 bg-gradient-to-r from-amber-600 to-amber-700 hover:from-amber-700 hover:to-amber-800 text-white rounded-xl shadow-md shadow-amber-700/20 hover:shadow-lg transition-all active:scale-95 group">
                        <i class="fa-solid fa-bag-shopping text-base group-hover:rotate-6 transition-transform"></i>
                        <span class="hidden sm:inline-block text-xs font-bold tracking-wide">Cart</span>
                        <span id="headerCartCount" class="w-5 h-5 rounded-full bg-white text-amber-800 text-[11px] font-extrabold flex items-center justify-center shadow-inner">
                            {{ $cartCount }}
                        </span>
                    </button>

                    <!-- Mobile Menu Hamburger -->
                    <button onclick="toggleMobileNav()" class="lg:hidden p-2 text-slate-700 hover:text-amber-700 rounded-lg hover:bg-amber-50">
                        <i class="fa-solid fa-bars text-xl"></i>
                    </button>
                </div>

            </div>
        </div>

        <!-- Mobile Navigation Menu -->
        <div id="mobileNavMenu" class="hidden lg:hidden border-t border-amber-100 bg-[#FFFDF9] px-4 pt-3 pb-5 space-y-2 shadow-lg">
            <form action="{{ route('shop.index') }}" method="GET" class="relative mb-3">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Search achar, mukhwas..." 
                    class="w-full bg-amber-50 border border-amber-200 rounded-xl py-2 pl-9 pr-4 text-xs font-medium focus:ring-2 focus:ring-amber-500">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-3 text-slate-400 text-xs"></i>
            </form>
            <a href="{{ route('home') }}" class="block px-3 py-2 rounded-lg text-sm font-semibold {{ request()->routeIs('home') ? 'bg-amber-100 text-amber-800' : 'text-slate-700 hover:bg-amber-50' }}">Home</a>
            <a href="{{ route('shop.index') }}" class="block px-3 py-2 rounded-lg text-sm font-semibold text-slate-700 hover:bg-amber-50">All Products</a>
            <a href="{{ route('shop.category', 'traditional-pickles') }}" class="block px-3 py-2 rounded-lg text-sm font-semibold text-slate-700 hover:bg-amber-50">Achar (Pickles)</a>
            <a href="{{ route('shop.category', 'royal-mukhwas') }}" class="block px-3 py-2 rounded-lg text-sm font-semibold text-slate-700 hover:bg-amber-50">Royal Mukhwas</a>
            <a href="{{ route('shop.category', 'sweet-pickles-murabba') }}" class="block px-3 py-2 rounded-lg text-sm font-semibold text-slate-700 hover:bg-amber-50">Chunda & Murabba</a>
            <a href="{{ route('about') }}" class="block px-3 py-2 rounded-lg text-sm font-semibold text-slate-700 hover:bg-amber-50">Our Heritage</a>
            <a href="{{ route('contact') }}" class="block px-3 py-2 rounded-lg text-sm font-semibold text-slate-700 hover:bg-amber-50">Contact Us</a>
            <a href="{{ route('orders.track.index') }}" class="block px-3 py-2 rounded-lg text-sm font-semibold text-amber-800 bg-amber-50/70"><i class="fa-solid fa-map-location-dot mr-1"></i> Track Order</a>
        </div>
    </header>

    <!-- Global Toast Alert Container -->
    <div id="toastContainer" class="fixed top-24 right-4 z-50 flex flex-col gap-2 max-w-sm w-full pointer-events-none">
        @if(session('success'))
            <div class="pointer-events-auto bg-emerald-900/95 text-white px-4 py-3 rounded-xl shadow-xl flex items-center justify-between gap-3 text-xs font-medium border border-emerald-500/40 animate-slide-in">
                <div class="flex items-center gap-2.5">
                    <i class="fa-solid fa-circle-check text-emerald-400 text-base"></i>
                    <span>{{ session('success') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-emerald-300 hover:text-white"><i class="fa-solid fa-xmark"></i></button>
            </div>
        @endif
        @if(session('error'))
            <div class="pointer-events-auto bg-rose-900/95 text-white px-4 py-3 rounded-xl shadow-xl flex items-center justify-between gap-3 text-xs font-medium border border-rose-500/40 animate-slide-in">
                <div class="flex items-center gap-2.5">
                    <i class="fa-solid fa-circle-exclamation text-rose-400 text-base"></i>
                    <span>{{ session('error') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-rose-300 hover:text-white"><i class="fa-solid fa-xmark"></i></button>
            </div>
        @endif
        @if(session('info'))
            <div class="pointer-events-auto bg-amber-900/95 text-white px-4 py-3 rounded-xl shadow-xl flex items-center justify-between gap-3 text-xs font-medium border border-amber-500/40 animate-slide-in">
                <div class="flex items-center gap-2.5">
                    <i class="fa-solid fa-circle-info text-amber-400 text-base"></i>
                    <span>{{ session('info') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-amber-300 hover:text-white"><i class="fa-solid fa-xmark"></i></button>
            </div>
        @endif
    </div>

    <!-- Main Page Content -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Slide-out Cart Drawer -->
    <div id="cartBackdrop" onclick="toggleCartDrawer(false)" class="fixed inset-0 bg-black/60 backdrop-blur-xs z-50 opacity-0 pointer-events-none transition-opacity duration-300"></div>
    <div id="cartDrawer" class="fixed inset-y-0 right-0 max-w-md w-full bg-white z-50 shadow-2xl flex flex-col translate-x-full transition-transform duration-300 ease-out">
        <!-- Drawer Header -->
        <div class="p-5 border-b border-amber-100 bg-[#FFFDF9] flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-lg bg-amber-100 text-amber-800 flex items-center justify-center">
                    <i class="fa-solid fa-bag-shopping text-sm"></i>
                </div>
                <div>
                    <h3 class="font-serif-heading font-bold text-lg text-slate-900">Your Fresh Basket</h3>
                    <p class="text-[11px] text-slate-500 font-medium"><span id="drawerItemsCount">{{ $cartCount }}</span> items in basket</p>
                </div>
            </div>
            <button onclick="toggleCartDrawer(false)" class="w-8 h-8 rounded-lg hover:bg-slate-100 text-slate-400 hover:text-slate-700 flex items-center justify-center transition-colors">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <!-- Free Shipping Progress -->
        <div class="bg-amber-50/70 p-3.5 border-b border-amber-100/70">
            @php
                $freeRem = $cartService->getFreeShippingRemaining();
                $freePct = min(100, max(0, ($cartSubtotal / 499) * 100));
            @endphp
            <div class="flex justify-between text-xs font-semibold text-slate-700 mb-1.5">
                <span id="freeShippingText">
                    @if($freeRem <= 0 && $cartSubtotal > 0)
                        🎉 Congratulations! You unlocked <strong class="text-emerald-700">FREE SHIPPING</strong>
                    @else
                        Add <strong class="text-amber-800">₹<span id="freeRemainingAmount">{{ $freeRem }}</span></strong> more for Free Shipping!
                    @endif
                </span>
                <span class="text-slate-400 text-[10px]">Threshold: ₹499</span>
            </div>
            <div class="w-full bg-amber-200/60 rounded-full h-2 overflow-hidden">
                <div id="freeShippingBar" class="bg-gradient-to-r from-amber-500 to-emerald-600 h-2 rounded-full transition-all duration-300" style="width: {{ $freePct }}%"></div>
            </div>
        </div>

        <!-- Cart Items List Container -->
        <div id="drawerItemsList" class="flex-1 overflow-y-auto p-5 space-y-4 divide-y divide-slate-100">
            <!-- Dynamically populated via JS or server rendered -->
            @forelse($cartService->getItems() as $item)
                <div class="pt-4 first:pt-0 flex gap-3 group" data-variant-id="{{ $item['variant_id'] }}">
                    <img src="{{ asset($item['image']) }}" alt="{{ $item['name'] }}" class="w-16 h-16 rounded-xl object-cover border border-amber-100 shrink-0 bg-amber-50">
                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between items-start gap-1">
                            <h4 class="text-xs font-bold text-slate-800 truncate">{{ $item['name'] }}</h4>
                            <button onclick="removeCartItem({{ $item['variant_id'] }})" class="text-slate-300 hover:text-rose-600 transition-colors text-xs p-1">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </div>
                        <p class="text-[11px] text-amber-800 font-semibold mt-0.5">{{ $item['weight'] }}</p>
                        <div class="flex justify-between items-center mt-2.5">
                            <div class="flex items-center border border-slate-200 rounded-lg overflow-hidden bg-slate-50">
                                <button onclick="updateCartQty({{ $item['variant_id'] }}, {{ $item['quantity'] - 1 }})" class="px-2 py-0.5 text-xs text-slate-600 hover:bg-slate-200 transition-colors">-</button>
                                <span class="px-2.5 py-0.5 text-xs font-bold text-slate-800">{{ $item['quantity'] }}</span>
                                <button onclick="updateCartQty({{ $item['variant_id'] }}, {{ $item['quantity'] + 1 }})" class="px-2 py-0.5 text-xs text-slate-600 hover:bg-slate-200 transition-colors">+</button>
                            </div>
                            <span class="text-xs font-bold text-slate-900">₹{{ number_format($item['total'], 2) }}</span>
                        </div>
                    </div>
                </div>
            @empty
                <div id="drawerEmptyMsg" class="py-16 text-center">
                    <div class="w-16 h-16 rounded-2xl bg-amber-50 text-amber-600 mx-auto flex items-center justify-center text-2xl mb-3">
                        <i class="fa-solid fa-basket-shopping"></i>
                    </div>
                    <h4 class="font-bold text-slate-700 text-sm">Your basket is empty</h4>
                    <p class="text-xs text-slate-400 mt-1">Explore our heirloom mango pickles and royal mukhwas!</p>
                    <a href="{{ route('shop.index') }}" onclick="toggleCartDrawer(false)" class="mt-4 inline-block px-4 py-2 bg-amber-700 hover:bg-amber-800 text-white text-xs font-bold rounded-xl transition-colors">
                        Browse Shop
                    </a>
                </div>
            @endforelse
        </div>

        <!-- Drawer Footer -->
        <div class="p-5 border-t border-slate-100 bg-[#FFFDF9] space-y-3">
            <div class="flex justify-between items-center text-xs text-slate-600 font-medium">
                <span>Subtotal</span>
                <span class="font-bold text-slate-900 text-sm">₹<span id="drawerSubtotal">{{ number_format($cartSubtotal, 2) }}</span></span>
            </div>
            <div class="flex justify-between items-center text-xs text-slate-500">
                <span>Shipping</span>
                <span id="drawerShippingStatus" class="font-semibold text-emerald-700">
                    {{ $cartService->getShippingFee() > 0 ? '₹' . number_format($cartService->getShippingFee(), 2) : 'FREE' }}
                </span>
            </div>
            <div class="pt-2 border-t border-slate-200 flex justify-between items-center text-sm font-bold text-slate-900">
                <span>Estimated Total</span>
                <span class="text-amber-800 text-base">₹<span id="drawerTotal">{{ number_format($cartService->getTotal(), 2) }}</span></span>
            </div>

            <div class="grid grid-cols-2 gap-2 pt-2">
                <a href="{{ route('cart.index') }}" onclick="toggleCartDrawer(false)" class="w-full py-2.5 px-3 rounded-xl border border-amber-300 hover:border-amber-400 bg-amber-50/50 hover:bg-amber-100/60 text-amber-900 text-xs font-bold text-center transition-colors">
                    View Full Cart
                </a>
                <a href="{{ route('checkout.index') }}" class="w-full py-2.5 px-3 rounded-xl bg-gradient-to-r from-amber-600 to-amber-800 hover:from-amber-700 hover:to-amber-900 text-white text-xs font-bold text-center shadow-md shadow-amber-800/20 hover:shadow-lg transition-all flex items-center justify-center gap-1.5">
                    <span>Checkout</span>
                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>
            <p class="text-[10px] text-center text-slate-400">Guaranteed Freshness & Tamper-Evident Safety Seal</p>
        </div>
    </div>

    <!-- Storefront Footer -->
    <footer class="bg-gradient-to-b from-[#2E180E] to-[#1F0F09] text-amber-100/90 pt-16 pb-12 border-t-4 border-amber-600">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-8 pb-12 border-b border-amber-900/60">

                <!-- Brand Story Column -->
                <div class="lg:col-span-2 space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-amber-600 flex items-center justify-center text-white shadow-md">
                            <i class="fa-solid fa-jar text-xl"></i>
                        </div>
                        <span class="font-serif-heading text-2xl font-bold tracking-tight text-white">
                            Swad Desi
                        </span>
                    </div>
                    <p class="text-xs text-amber-200/70 leading-relaxed max-w-sm">
                        Reviving authentic Indian culinary nostalgia. Our pickles are hand-cured under natural sunlight in earthenware barnis using cold-pressed kachi ghani mustard oil, while our royal mukhwas blends carry centuries-old post-meal digestive wisdom.
                    </p>
                    <div class="flex items-center gap-3 pt-2">
                        <span class="w-8 h-8 rounded-full bg-amber-950 flex items-center justify-center text-amber-400 hover:bg-amber-600 hover:text-white transition-colors cursor-pointer text-xs"><i class="fa-brands fa-instagram"></i></span>
                        <span class="w-8 h-8 rounded-full bg-amber-950 flex items-center justify-center text-amber-400 hover:bg-amber-600 hover:text-white transition-colors cursor-pointer text-xs"><i class="fa-brands fa-facebook-f"></i></span>
                        <span class="w-8 h-8 rounded-full bg-amber-950 flex items-center justify-center text-amber-400 hover:bg-amber-600 hover:text-white transition-colors cursor-pointer text-xs"><i class="fa-brands fa-whatsapp"></i></span>
                    </div>
                </div>

                <!-- Quick Categories -->
                <div class="space-y-3">
                    <h4 class="font-serif-heading text-sm font-bold text-amber-300 uppercase tracking-wider">Flavours</h4>
                    <ul class="space-y-2 text-xs text-amber-200/80">
                        <li><a href="{{ route('shop.category', 'traditional-pickles') }}" class="hover:text-white transition-colors">Aam Ka Achar (Mango)</a></li>
                        <li><a href="{{ route('shop.category', 'traditional-pickles') }}" class="hover:text-white transition-colors">Banarasi Lal Mirch</a></li>
                        <li><a href="{{ route('shop.category', 'traditional-pickles') }}" class="hover:text-white transition-colors">Lahsun Ka Achar (Garlic)</a></li>
                        <li><a href="{{ route('shop.category', 'sweet-pickles-murabba') }}" class="hover:text-white transition-colors">Gujarati Keri Chunda</a></li>
                        <li><a href="{{ route('shop.category', 'royal-mukhwas') }}" class="hover:text-white transition-colors">Royal Panchratna Mukhwas</a></li>
                        <li><a href="{{ route('shop.category', 'royal-mukhwas') }}" class="hover:text-white transition-colors">Calcutta Meetha Paan</a></li>
                    </ul>
                </div>

                <!-- Customer Care -->
                <div class="space-y-3">
                    <h4 class="font-serif-heading text-sm font-bold text-amber-300 uppercase tracking-wider">Assistance</h4>
                    <ul class="space-y-2 text-xs text-amber-200/80">
                        <li><a href="{{ route('orders.track.index') }}" class="hover:text-white transition-colors">Track Your Order</a></li>
                        <li><a href="{{ route('cart.index') }}" class="hover:text-white transition-colors">Shopping Basket</a></li>
                        <li><a href="{{ route('about') }}" class="hover:text-white transition-colors">Our Recipe Heritage</a></li>
                        <li><a href="{{ route('contact') }}" class="hover:text-white transition-colors">Customer Support</a></li>
                        <li><a href="{{ route('admin.login') }}" class="hover:text-amber-400 transition-colors text-amber-400/80"><i class="fa-solid fa-lock text-[10px] mr-1"></i> Admin Portal</a></li>
                    </ul>
                </div>

                <!-- Heritage Standards -->
                <div class="space-y-3">
                    <h4 class="font-serif-heading text-sm font-bold text-amber-300 uppercase tracking-wider">Our Promise</h4>
                    <div class="space-y-2 text-xs text-amber-200/80">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-sun text-amber-400 text-sm"></i>
                            <span>100% Sun-Matured</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-oil-can text-amber-400 text-sm"></i>
                            <span>Cold-Pressed Mustard Oil</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-ban text-amber-400 text-sm"></i>
                            <span>No Artificial Chemicals</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-shield-heart text-amber-400 text-sm"></i>
                            <span>Airtight Glass Packing</span>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Footer Bottom -->
            <div class="pt-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-[11px] text-amber-300/60">
                <p>© {{ date('Y') }} Swad Desi Indian Pickles & Mukhwas. All rights reserved.</p>
                <div class="flex items-center gap-4">
                    <span>Safe & Secure Payments</span>
                    <span class="text-amber-400/70"><i class="fa-brands fa-cc-visa text-base"></i></span>
                    <span class="text-amber-400/70"><i class="fa-brands fa-cc-mastercard text-base"></i></span>
                    <span class="text-amber-400/70"><i class="fa-solid fa-qrcode text-base"></i> UPI</span>
                </div>
            </div>
        </div>
    </footer>

    <!-- Global Scripts -->
    <script>
        // Toggle Cart Drawer
        function toggleCartDrawer(open) {
            const drawer = document.getElementById('cartDrawer');
            const backdrop = document.getElementById('cartBackdrop');
            if (open) {
                drawer.classList.remove('translate-x-full');
                backdrop.classList.remove('opacity-0', 'pointer-events-none');
                backdrop.classList.add('opacity-100');
                document.body.style.overflow = 'hidden';
            } else {
                drawer.classList.add('translate-x-full');
                backdrop.classList.add('opacity-0', 'pointer-events-none');
                backdrop.classList.remove('opacity-100');
                document.body.style.overflow = '';
            }
        }

        // Toggle Mobile Navigation
        function toggleMobileNav() {
            const menu = document.getElementById('mobileNavMenu');
            menu.classList.toggle('hidden');
        }

        // Show Toast Notification
        function showToast(message, type = 'success') {
            const container = document.getElementById('toastContainer');
            const toast = document.createElement('div');
            const colorClass = type === 'success' 
                ? 'bg-emerald-900/95 border-emerald-500/40 text-white' 
                : (type === 'error' ? 'bg-rose-900/95 border-rose-500/40 text-white' : 'bg-amber-900/95 border-amber-500/40 text-white');
            const icon = type === 'success' ? 'fa-circle-check text-emerald-400' : (type === 'error' ? 'fa-circle-exclamation text-rose-400' : 'fa-circle-info text-amber-400');
            
            toast.className = `pointer-events-auto ${colorClass} px-4 py-3 rounded-xl shadow-xl flex items-center justify-between gap-3 text-xs font-medium border transition-all duration-300`;
            toast.innerHTML = `
                <div class="flex items-center gap-2.5">
                    <i class="fa-solid ${icon} text-base"></i>
                    <span>${message}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-white/70 hover:text-white"><i class="fa-solid fa-xmark"></i></button>
            `;
            container.appendChild(toast);
            setTimeout(() => {
                toast.remove();
            }, 4000);
        }

        // AJAX Add to Cart
        function addToCart(variantId, quantity = 1) {
            fetch("{{ route('cart.add') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                    "Accept": "application/json"
                },
                body: JSON.stringify({ variant_id: variantId, quantity: quantity })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    showToast(data.message, 'success');
                    updateDrawerUI(data);
                    toggleCartDrawer(true);
                } else {
                    showToast(data.message, 'error');
                }
            })
            .catch(err => {
                console.error(err);
                showToast("Failed to add to cart. Please try again.", 'error');
            });
        }

        // AJAX Update Quantity
        function updateCartQty(variantId, qty) {
            fetch("{{ route('cart.update') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                    "Accept": "application/json"
                },
                body: JSON.stringify({ variant_id: variantId, quantity: qty })
            })
            .then(res => res.json())
            .then(data => {
                updateDrawerUI(data);
            })
            .catch(err => console.error(err));
        }

        // AJAX Remove Item
        function removeCartItem(variantId) {
            fetch(`/cart/remove/${variantId}`, {
                method: "DELETE",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                    "Accept": "application/json"
                }
            })
            .then(res => res.json())
            .then(data => {
                showToast(data.message, 'info');
                updateDrawerUI(data);
            })
            .catch(err => console.error(err));
        }

        // Refresh Drawer Elements
        function updateDrawerUI(data) {
            const count = data.cart_count || 0;
            const subtotal = data.cart_subtotal || 0;
            const items = data.cart_items || [];

            document.getElementById('headerCartCount').textContent = count;
            document.getElementById('drawerItemsCount').textContent = count;
            document.getElementById('drawerSubtotal').textContent = Number(subtotal).toFixed(2);

            const shipping = subtotal >= 499 || subtotal === 0 ? 0 : 49;
            const total = subtotal + shipping;
            document.getElementById('drawerTotal').textContent = Number(total).toFixed(2);
            document.getElementById('drawerShippingStatus').textContent = shipping === 0 ? 'FREE' : '₹49.00';

            // Free shipping bar
            const remaining = Math.max(0, 499 - subtotal);
            const pct = Math.min(100, Math.max(0, (subtotal / 499) * 100));
            const bar = document.getElementById('freeShippingBar');
            if (bar) bar.style.width = pct + '%';

            const freeText = document.getElementById('freeShippingText');
            if (freeText) {
                if (remaining <= 0 && subtotal > 0) {
                    freeText.innerHTML = `🎉 Congratulations! You unlocked <strong class="text-emerald-700">FREE SHIPPING</strong>`;
                } else {
                    freeText.innerHTML = `Add <strong class="text-amber-800">₹<span id="freeRemainingAmount">${remaining.toFixed(2)}</span></strong> more for Free Shipping!`;
                }
            }

            // Render list
            const listContainer = document.getElementById('drawerItemsList');
            if (!items.length) {
                listContainer.innerHTML = `
                    <div id="drawerEmptyMsg" class="py-16 text-center">
                        <div class="w-16 h-16 rounded-2xl bg-amber-50 text-amber-600 mx-auto flex items-center justify-center text-2xl mb-3">
                            <i class="fa-solid fa-basket-shopping"></i>
                        </div>
                        <h4 class="font-bold text-slate-700 text-sm">Your basket is empty</h4>
                        <p class="text-xs text-slate-400 mt-1">Explore our heirloom mango pickles and royal mukhwas!</p>
                        <a href="{{ route('shop.index') }}" onclick="toggleCartDrawer(false)" class="mt-4 inline-block px-4 py-2 bg-amber-700 hover:bg-amber-800 text-white text-xs font-bold rounded-xl transition-colors">
                            Browse Shop
                        </a>
                    </div>
                `;
            } else {
                let html = '';
                items.forEach(item => {
                    html += `
                        <div class="pt-4 first:pt-0 flex gap-3 group" data-variant-id="${item.variant_id}">
                            <img src="/${item.image}" alt="${item.name}" class="w-16 h-16 rounded-xl object-cover border border-amber-100 shrink-0 bg-amber-50">
                            <div class="flex-1 min-w-0">
                                <div class="flex justify-between items-start gap-1">
                                    <h4 class="text-xs font-bold text-slate-800 truncate">${item.name}</h4>
                                    <button onclick="removeCartItem(${item.variant_id})" class="text-slate-300 hover:text-rose-600 transition-colors text-xs p-1">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </div>
                                <p class="text-[11px] text-amber-800 font-semibold mt-0.5">${item.weight}</p>
                                <div class="flex justify-between items-center mt-2.5">
                                    <div class="flex items-center border border-slate-200 rounded-lg overflow-hidden bg-slate-50">
                                        <button onclick="updateCartQty(${item.variant_id}, ${item.quantity - 1})" class="px-2 py-0.5 text-xs text-slate-600 hover:bg-slate-200 transition-colors">-</button>
                                        <span class="px-2.5 py-0.5 text-xs font-bold text-slate-800">${item.quantity}</span>
                                        <button onclick="updateCartQty(${item.variant_id}, ${item.quantity + 1})" class="px-2 py-0.5 text-xs text-slate-600 hover:bg-slate-200 transition-colors">+</button>
                                    </div>
                                    <span class="text-xs font-bold text-slate-900">₹${Number(item.total).toFixed(2)}</span>
                                </div>
                            </div>
                        </div>
                    `;
                });
                listContainer.innerHTML = html;
            }
        }
    </script>
    @stack('scripts')
</body>
</html>
