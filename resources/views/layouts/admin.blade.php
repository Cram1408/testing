<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-[#FDFBF7]">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Admin Portal') - AchaarG Control Center</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;0,800;1,600&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

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
        /* Custom scrollbar matching heritage palette */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #24130A;
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
<body class="h-full font-sans antialiased text-slate-800 flex overflow-hidden selection:bg-amber-500 selection:text-white">

    <!-- Sidebar -->
    <aside class="w-64 bg-gradient-to-b from-[#2E180E] via-[#24130A] to-[#170A04] text-amber-100/90 flex flex-col shrink-0 border-r border-[#452414] shadow-xl z-30">
        <!-- Brand Header -->
        <div class="h-20 flex items-center px-6 gap-3 border-b border-[#452414] bg-[#221107]/80">
            <div class="w-11 h-11 rounded-2xl bg-gradient-to-br from-amber-500 to-amber-700 flex items-center justify-center text-white shadow-md shadow-amber-950/50 border border-amber-400/30 font-bold shrink-0">
                <i class="fa-solid fa-jar text-xl"></i>
            </div>
            <div>
                <h1 class="font-serif-heading font-bold text-white text-lg tracking-tight leading-none">AchaarG</h1>
                @if(auth()->user()->isSuperAdmin())
                    <span class="text-[10px] text-amber-400 font-extrabold uppercase tracking-wider flex items-center gap-1 mt-1">
                        <i class="fa-solid fa-crown text-[9px]"></i>
                        <span>Super Admin</span>
                    </span>
                @else
                    <span class="text-[10px] text-amber-300/80 font-semibold uppercase tracking-wider flex items-center gap-1 mt-1">
                        <i class="fa-solid fa-shield text-[9px]"></i>
                        <span>{{ auth()->user()->role_title }}</span>
                    </span>
                @endif
            </div>
        </div>

        <!-- Navigation Links -->
        <nav class="flex-1 px-4 py-6 space-y-1.5 overflow-y-auto">
            <div class="text-[10px] font-bold uppercase tracking-wider text-amber-400/80 px-3 mb-2">Management</div>

            @if(auth()->user()->hasPermission('dashboard'))
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-gradient-to-r from-amber-600 to-amber-700 text-white shadow-md shadow-amber-950/40 font-bold' : 'text-amber-200/75 hover:text-white hover:bg-amber-900/30' }}">
                    <i class="fa-solid fa-chart-pie w-4 text-sm text-amber-400"></i>
                    <span>Dashboard</span>
                </a>
            @endif

            @if(auth()->user()->hasPermission('products'))
                <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('admin.categories.*') ? 'bg-gradient-to-r from-amber-600 to-amber-700 text-white shadow-md shadow-amber-950/40 font-bold' : 'text-amber-200/75 hover:text-white hover:bg-amber-900/30' }}">
                    <i class="fa-solid fa-layer-group w-4 text-sm text-amber-400"></i>
                    <span>Categories</span>
                </a>

                <a href="{{ route('admin.products.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('admin.products.*') ? 'bg-gradient-to-r from-amber-600 to-amber-700 text-white shadow-md shadow-amber-950/40 font-bold' : 'text-amber-200/75 hover:text-white hover:bg-amber-900/30' }}">
                    <i class="fa-solid fa-boxes-stacked w-4 text-sm text-amber-400"></i>
                    <span>Products & Variants</span>
                </a>
            @endif

            @if(auth()->user()->hasPermission('inventory'))
                <a href="{{ route('admin.inventory.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('admin.inventory.*') ? 'bg-gradient-to-r from-amber-600 to-amber-700 text-white shadow-md shadow-amber-950/40 font-bold' : 'text-amber-200/75 hover:text-white hover:bg-amber-900/30' }}">
                    <i class="fa-solid fa-warehouse w-4 text-sm text-amber-400"></i>
                    <span>Stock Inventory</span>
                </a>
            @endif

            @if(auth()->user()->hasPermission('orders') || auth()->user()->hasPermission('customers'))
                <div class="text-[10px] font-bold uppercase tracking-wider text-amber-400/80 px-3 mt-6 mb-2">Fulfillment</div>

                @if(auth()->user()->hasPermission('orders'))
                    <a href="{{ route('admin.orders.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('admin.orders.*') ? 'bg-gradient-to-r from-amber-600 to-amber-700 text-white shadow-md shadow-amber-950/40 font-bold' : 'text-amber-200/75 hover:text-white hover:bg-amber-900/30' }}">
                        <i class="fa-solid fa-receipt w-4 text-sm text-amber-400"></i>
                        <span>Orders</span>
                        @php
                            $pendingOrdersCount = \App\Models\Order::whereIn('order_status', ['pending', 'processing'])->count();
                        @endphp
                        @if($pendingOrdersCount > 0)
                            <span class="ml-auto px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-amber-500 text-amber-950 shadow-xs">{{ $pendingOrdersCount }}</span>
                        @endif
                    </a>
                @endif

                @if(auth()->user()->hasPermission('customers'))
                    <a href="{{ route('admin.customers.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('admin.customers.*') ? 'bg-gradient-to-r from-amber-600 to-amber-700 text-white shadow-md shadow-amber-950/40 font-bold' : 'text-amber-200/75 hover:text-white hover:bg-amber-900/30' }}">
                        <i class="fa-solid fa-users w-4 text-sm text-amber-400"></i>
                        <span>Customers</span>
                    </a>
                @endif
            @endif

            @if(auth()->user()->hasPermission('reports'))
                <div class="text-[10px] font-bold uppercase tracking-wider text-amber-400/80 px-3 mt-6 mb-2">Analytics</div>

                <a href="{{ route('admin.reports.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('admin.reports.*') ? 'bg-gradient-to-r from-amber-600 to-amber-700 text-white shadow-md shadow-amber-950/40 font-bold' : 'text-amber-200/75 hover:text-white hover:bg-amber-900/30' }}">
                    <i class="fa-solid fa-chart-line w-4 text-sm text-amber-400"></i>
                    <span>Reports & Analytics</span>
                </a>
            @endif

            @if(auth()->user()->isSuperAdmin() || auth()->user()->hasPermission('admins') || auth()->user()->hasPermission('settings'))
                <div class="text-[10px] font-bold uppercase tracking-wider text-amber-400 px-3 mt-6 mb-2 flex items-center gap-1.5">
                    <i class="fa-solid fa-crown text-[9px] text-amber-400"></i>
                    <span>Super Admin Controls</span>
                </div>

                @if(auth()->user()->isSuperAdmin() || auth()->user()->hasPermission('admins'))
                    <a href="{{ route('admin.admins.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('admin.admins.*') ? 'bg-gradient-to-r from-amber-600 to-amber-700 text-white shadow-md shadow-amber-950/40 font-bold' : 'text-amber-200/75 hover:text-white hover:bg-amber-900/30' }}">
                        <i class="fa-solid fa-user-shield w-4 text-sm text-amber-400"></i>
                        <span>Admin Staff Accounts</span>
                    </a>

                    <a href="{{ route('admin.roles.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('admin.roles.*') ? 'bg-gradient-to-r from-amber-600 to-amber-700 text-white shadow-md shadow-amber-950/40 font-bold' : 'text-amber-200/75 hover:text-white hover:bg-amber-900/30' }}">
                        <i class="fa-solid fa-key w-4 text-sm text-amber-400"></i>
                        <span>Roles & Permissions</span>
                    </a>
                @endif

                @if(auth()->user()->isSuperAdmin() || auth()->user()->hasPermission('settings'))
                    <a href="{{ route('admin.settings.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('admin.settings.*') ? 'bg-gradient-to-r from-amber-600 to-amber-700 text-white shadow-md shadow-amber-950/40 font-bold' : 'text-amber-200/75 hover:text-white hover:bg-amber-900/30' }}">
                        <i class="fa-solid fa-sliders w-4 text-sm text-amber-400"></i>
                        <span>Website Settings</span>
                    </a>
                @endif
            @endif

            <div class="text-[10px] font-bold uppercase tracking-wider text-amber-400/80 px-3 mt-6 mb-2">Storefront</div>

            <a href="{{ route('home') }}" target="_blank" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold text-amber-200/75 hover:text-white hover:bg-amber-900/30 transition-all">
                <i class="fa-solid fa-arrow-up-right-from-square w-4 text-sm text-emerald-400"></i>
                <span>Live Storefront</span>
            </a>
        </nav>

        <!-- Admin Profile Footer -->
        <div class="p-4 border-t border-[#452414] bg-[#1A0B04]/90 flex items-center justify-between">
            <div class="flex items-center gap-3 min-w-0">
                <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-amber-600 to-amber-800 text-white font-bold flex items-center justify-center text-xs shrink-0 shadow-sm border border-amber-400/30">
                    {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                </div>
                <div class="truncate">
                    <p class="text-xs font-semibold text-white truncate">{{ auth()->user()->name ?? 'Admin' }}</p>
                    <p class="text-[10px] text-amber-300/60 truncate">{{ auth()->user()->role_title }}</p>
                </div>
            </div>
            <form action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button type="submit" title="Log Out" class="p-2 text-amber-300/60 hover:text-rose-400 transition-colors">
                    <i class="fa-solid fa-power-off text-sm"></i>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col overflow-hidden bg-[#FDFBF7]">
        <!-- Top Header -->
        <header class="h-20 bg-[#FFFDF9]/95 backdrop-blur-md border-b border-amber-200/60 px-8 flex items-center justify-between z-20 shrink-0 shadow-xs">
            <div>
                <h2 class="font-serif-heading text-xl font-bold text-amber-950">@yield('header_title', 'Dashboard')</h2>
                <p class="text-xs text-amber-800/70">@yield('header_subtitle', 'Store overview, order fulfillment and live stock status')</p>
            </div>
            <div class="flex items-center gap-4">
                <a href="{{ route('home') }}" target="_blank" class="px-3.5 py-2 rounded-xl bg-amber-50/80 hover:bg-amber-100 text-amber-900 border border-amber-300/80 text-xs font-bold transition-all flex items-center gap-2 shadow-xs">
                    <i class="fa-solid fa-store text-amber-700"></i>
                    <span>Visit Store</span>
                </a>
            </div>
        </header>

        <!-- Main Body Content -->
        <main class="flex-1 overflow-y-auto p-8 bg-[#FDFBF7]">
            <!-- Flash Message Alerts -->
            @if(session('success'))
                <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-2xl flex items-center justify-between shadow-sm text-xs font-medium">
                    <div class="flex items-center gap-2.5">
                        <i class="fa-solid fa-circle-check text-emerald-600 text-base"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-800"><i class="fa-solid fa-xmark"></i></button>
                </div>
            @endif
            @if(session('error'))
                <div class="mb-6 bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-2xl flex items-center justify-between shadow-sm text-xs font-medium">
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
