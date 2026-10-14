@extends('layouts.app')

@section('title', 'My Account & Orders - AchaarG')

@section('content')
<div class="bg-amber-50/50 py-6 border-b border-amber-200/40">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="font-serif-heading text-3xl font-extrabold text-slate-900">Welcome, {{ $user->name }}</h1>
            <p class="text-xs sm:text-sm text-slate-600 mt-1">Manage your saved addresses and track your handmade pickle orders.</p>
        </div>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="px-4 py-2 rounded-xl bg-white hover:bg-rose-50 text-rose-600 border border-slate-200 text-xs font-bold transition-colors flex items-center gap-1.5">
                <i class="fa-solid fa-arrow-right-from-bracket"></i>
                <span>Sign Out</span>
            </button>
        </form>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- Left Column: Profile & Address Details -->
        <div class="lg:col-span-4 bg-white rounded-3xl p-6 border border-amber-100 shadow-sm space-y-6">
            <div class="flex items-center gap-3 border-b border-slate-100 pb-4">
                <div class="w-12 h-12 rounded-2xl bg-amber-600 text-white font-extrabold text-lg flex items-center justify-center shadow-md">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div class="truncate">
                    <h3 class="font-bold text-sm text-slate-900 truncate">{{ $user->name }}</h3>
                    <p class="text-xs text-slate-500 truncate">{{ $user->email }}</p>
                </div>
            </div>

            <form action="{{ route('customer.profile.update') }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1">Full Name</label>
                    <input type="text" name="name" required value="{{ old('name', $user->name) }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs font-semibold text-slate-900 focus:outline-none focus:ring-2 focus:ring-amber-500">
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1">Phone Number</label>
                    <input type="tel" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="+91 98765 43210" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs font-semibold text-slate-900 focus:outline-none focus:ring-2 focus:ring-amber-500">
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1">Default Address</label>
                    <textarea name="address" rows="2" placeholder="House/Flat No., Street" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs font-semibold text-slate-900 focus:outline-none focus:ring-2 focus:ring-amber-500">{{ old('address', $user->address) }}</textarea>
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1">City</label>
                        <input type="text" name="city" value="{{ old('city', $user->city) }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs font-semibold text-slate-900 focus:outline-none focus:ring-2 focus:ring-amber-500">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1">PIN Code</label>
                        <input type="text" name="postal_code" value="{{ old('postal_code', $user->postal_code) }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs font-semibold text-slate-900 focus:outline-none focus:ring-2 focus:ring-amber-500">
                    </div>
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1">State</label>
                    <input type="text" name="state" value="{{ old('state', $user->state) }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs font-semibold text-slate-900 focus:outline-none focus:ring-2 focus:ring-amber-500">
                </div>

                <button type="submit" class="w-full py-2.5 rounded-xl bg-amber-700 hover:bg-amber-800 text-white font-bold text-xs shadow-md transition-all">
                    Update Profile
                </button>
            </form>
        </div>

        <!-- Right Column: Order History -->
        <div class="lg:col-span-8 bg-white rounded-3xl p-6 sm:p-8 border border-amber-100 shadow-sm space-y-6">
            <h3 class="font-serif-heading font-bold text-lg text-slate-900 border-b border-slate-100 pb-3">My Orders History</h3>

            @if($orders->isEmpty())
                <div class="py-12 text-center">
                    <div class="w-16 h-16 rounded-2xl bg-amber-50 text-amber-600 mx-auto flex items-center justify-center text-2xl mb-3">
                        <i class="fa-solid fa-box-open"></i>
                    </div>
                    <h4 class="font-bold text-slate-800 text-sm">No orders yet</h4>
                    <p class="text-xs text-slate-400 mt-1">Ready for traditional flavours? Browse our freshly sun-cured pickles!</p>
                    <a href="{{ route('shop.index') }}" class="mt-4 inline-block px-6 py-2.5 bg-amber-700 hover:bg-amber-800 text-white text-xs font-bold rounded-xl transition-colors">
                        Shop Now
                    </a>
                </div>
            @else
                <div class="space-y-4">
                    @foreach($orders as $order)
                        <div class="p-5 rounded-2xl border border-slate-200 hover:border-amber-300 transition-all bg-slate-50/40 space-y-3">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-slate-200/60 pb-3">
                                <div>
                                    <span class="font-mono font-bold text-amber-950 text-xs">#{{ $order->order_number }}</span>
                                    <span class="text-slate-400 text-xs ml-2">• {{ $order->created_at->format('M d, Y') }}</span>
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

                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <div class="text-xs text-slate-600 space-y-1">
                                    <p class="font-semibold text-slate-800">{{ $order->items->count() }} {{ Str::plural('Item', $order->items->count()) }}:</p>
                                    <p class="text-slate-500 truncate max-w-md">
                                        {{ $order->items->pluck('product_name')->join(', ') }}
                                    </p>
                                </div>
                                <div class="flex items-center justify-between sm:justify-end gap-4">
                                    <div class="text-right">
                                        <span class="text-[10px] text-slate-400 uppercase font-bold block">Amount</span>
                                        <span class="font-extrabold text-sm text-slate-900">₹{{ number_format($order->total_amount, 2) }}</span>
                                    </div>
                                    <a href="{{ route('customer.orders.show', $order->order_number) }}" class="px-3.5 py-2 rounded-xl bg-amber-100 hover:bg-amber-200 text-amber-900 font-bold text-xs transition-colors flex items-center gap-1.5">
                                        <span>Details</span>
                                        <i class="fa-solid fa-chevron-right text-[10px]"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="pt-4">
                    {{ $orders->links() }}
                </div>
            @endif
        </div>

    </div>
</div>
@endsection
