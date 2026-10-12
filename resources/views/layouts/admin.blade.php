<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Admin Portal') - Swad Desi Control Center</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            500: '#F59E0B',
                            600: '#D97706',
                            700: '#B45309',
                            800: '#92400E',
                        }
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    @stack('styles')
</head>
<body class="h-full font-sans antialiased text-slate-800 flex overflow-hidden">

    <!-- Sidebar -->
    <aside class="w-64 bg-slate-900 text-slate-300 flex flex-col shrink-0 border-r border-slate-800 z-30">
        <!-- Brand Header -->
        <div class="h-20 flex items-center px-6 gap-3 border-b border-slate-800/80 bg-slate-950/40">
            <div class="w-10 h-10 rounded-xl bg-amber-600 flex items-center justify-center text-white shadow-md shadow-amber-600/30 font-bold">
                <i class="fa-solid fa-jar text-lg"></i>
            </div>
            <div>
                <h1 class="font-bold text-white text-base tracking-tight leading-none">Swad Desi</h1>
                <span class="text-[10px] text-amber-400 font-semibold uppercase tracking-wider">Store Admin</span>
            </div>
        </div>

        <!-- Navigation Links -->
        <nav class="flex-1 px-4 py-6 space-y-1.5 overflow-y-auto">
            <div class="text-[10px] font-bold uppercase tracking-wider text-slate-500 px-3 mb-2">Management</div>

            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-amber-600 text-white shadow-md shadow-amber-600/20' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}">
                <i class="fa-solid fa-chart-pie w-4 text-sm"></i>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('admin.categories.*') ? 'bg-amber-600 text-white shadow-md shadow-amber-600/20' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}">
                <i class="fa-solid fa-layer-group w-4 text-sm"></i>
                <span>Categories</span>
            </a>

            <a href="{{ route('admin.products.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('admin.products.*') ? 'bg-amber-600 text-white shadow-md shadow-amber-600/20' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}">
                <i class="fa-solid fa-boxes-stacked w-4 text-sm"></i>
                <span>Products & Variants</span>
            </a>

            <a href="{{ route('admin.inventory.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('admin.inventory.*') ? 'bg-amber-600 text-white shadow-md shadow-amber-600/20' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}">
                <i class="fa-solid fa-warehouse w-4 text-sm"></i>
                <span>Stock Inventory</span>
            </a>

            <div class="text-[10px] font-bold uppercase tracking-wider text-slate-500 px-3 mt-6 mb-2">Fulfillment</div>

            <a href="{{ route('admin.orders.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('admin.orders.*') ? 'bg-amber-600 text-white shadow-md shadow-amber-600/20' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}">
                <i class="fa-solid fa-receipt w-4 text-sm"></i>
                <span>Orders</span>
                @php
                    $pendingOrdersCount = \App\Models\Order::whereIn('order_status', ['pending', 'processing'])->count();
                @endphp
                @if($pendingOrdersCount > 0)
                    <span class="ml-auto px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-500 text-slate-950">{{ $pendingOrdersCount }}</span>
                @endif
            </a>

            <a href="{{ route('admin.customers.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('admin.customers.*') ? 'bg-amber-600 text-white shadow-md shadow-amber-600/20' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}">
                <i class="fa-solid fa-users w-4 text-sm"></i>
                <span>Customers</span>
            </a>

            <div class="text-[10px] font-bold uppercase tracking-wider text-slate-500 px-3 mt-6 mb-2">Storefront</div>

            <a href="{{ route('home') }}" target="_blank" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold text-slate-400 hover:text-white hover:bg-slate-800/60 transition-all">
                <i class="fa-solid fa-arrow-up-right-from-square w-4 text-sm text-emerald-400"></i>
                <span>Live Storefront</span>
            </a>
        </nav>

        <!-- Admin Profile Footer -->
        <div class="p-4 border-t border-slate-800 bg-slate-950/60 flex items-center justify-between">
            <div class="flex items-center gap-3 min-w-0">
                <div class="w-8 h-8 rounded-lg bg-amber-600 text-white font-bold flex items-center justify-center text-xs shrink-0">
                    {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                </div>
                <div class="truncate">
                    <p class="text-xs font-semibold text-white truncate">{{ auth()->user()->name ?? 'Admin' }}</p>
                    <p class="text-[10px] text-slate-400">Store Manager</p>
                </div>
            </div>
            <form action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button type="submit" title="Log Out" class="p-2 text-slate-400 hover:text-rose-400 transition-colors">
                    <i class="fa-solid fa-power-off text-sm"></i>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <!-- Top Header -->
        <header class="h-20 bg-white border-b border-slate-200 px-8 flex items-center justify-between z-20 shrink-0">
            <div>
                <h2 class="text-xl font-bold text-slate-900">@yield('header_title', 'Dashboard')</h2>
                <p class="text-xs text-slate-500">@yield('header_subtitle', 'Store overview, order fulfillment and live stock status')</p>
            </div>
            <div class="flex items-center gap-4">
                <a href="{{ route('home') }}" target="_blank" class="px-3.5 py-2 rounded-xl bg-amber-50 hover:bg-amber-100 text-amber-900 border border-amber-200 text-xs font-bold transition-all flex items-center gap-2">
                    <i class="fa-solid fa-store text-amber-700"></i>
                    <span>Visit Store</span>
                </a>
            </div>
        </header>

        <!-- Main Body Content -->
        <main class="flex-1 overflow-y-auto p-8 bg-slate-100">
            <!-- Flash Message Alerts -->
            @if(session('success'))
                <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl flex items-center justify-between shadow-sm text-xs font-medium">
                    <div class="flex items-center gap-2.5">
                        <i class="fa-solid fa-circle-check text-emerald-600 text-base"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-800"><i class="fa-solid fa-xmark"></i></button>
                </div>
            @endif
            @if(session('error'))
                <div class="mb-6 bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-xl flex items-center justify-between shadow-sm text-xs font-medium">
                    <div class="flex items-center gap-2.5">
                        <i class="fa-solid fa-circle-exclamation text-rose-600 text-base"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-800"><i class="fa-solid fa-xmark"></i></button>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>
