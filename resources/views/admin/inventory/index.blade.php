@extends('layouts.admin')

@section('title', 'Fast Stock Inventory - Admin')
@section('header_title', 'Stock Inventory Control')
@section('header_subtitle', 'Fast inline stock updates across all jar sizes and mukhwas pouches')

@section('content')
<div class="space-y-6">
    <!-- Stat Chips -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <a href="{{ route('admin.inventory.index') }}" class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-xs flex items-center justify-between {{ !request('filter') ? 'ring-2 ring-amber-600' : '' }}">
            <div>
                <span class="text-xs font-bold text-slate-400 uppercase">Total Size Variants</span>
                <p class="text-xl font-extrabold text-slate-900 mt-0.5">{{ $stats['total_items'] }}</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center font-bold">
                <i class="fa-solid fa-boxes-stacked"></i>
            </div>
        </a>

        <a href="{{ route('admin.inventory.index', ['filter' => 'low']) }}" class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-xs flex items-center justify-between {{ request('filter') === 'low' ? 'ring-2 ring-amber-600' : '' }}">
            <div>
                <span class="text-xs font-bold text-amber-600 uppercase">Low Stock (≤ 25 units)</span>
                <p class="text-xl font-extrabold text-amber-900 mt-0.5">{{ $stats['low_stock'] }}</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
        </a>

        <a href="{{ route('admin.inventory.index', ['filter' => 'out']) }}" class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-xs flex items-center justify-between {{ request('filter') === 'out' ? 'ring-2 ring-amber-600' : '' }}">
            <div>
                <span class="text-xs font-bold text-rose-600 uppercase">Out of Stock (0 units)</span>
                <p class="text-xl font-extrabold text-rose-900 mt-0.5">{{ $stats['out_of_stock'] }}</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center font-bold">
                <i class="fa-solid fa-ban"></i>
            </div>
        </a>
    </div>

    <!-- Search Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <form method="GET" action="{{ route('admin.inventory.index') }}" class="flex items-center gap-2">
            @if(request('filter'))
                <input type="hidden" name="filter" value="{{ request('filter') }}">
            @endif
            <div class="relative">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Search by SKU, product, size..." class="bg-white border border-slate-200 rounded-xl py-2 pl-9 pr-4 text-xs font-semibold focus:ring-2 focus:ring-amber-500">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-3 text-slate-400 text-xs"></i>
            </div>
            <button type="submit" class="px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white font-bold text-xs rounded-xl transition-colors">Search</button>
            @if(request()->hasAny(['filter', 'q']))
                <a href="{{ route('admin.inventory.index') }}" class="text-xs font-bold text-rose-600 hover:underline ml-2">Clear</a>
            @endif
        </form>
    </div>

    <!-- Inventory Table -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 border-b border-slate-200 text-slate-400 uppercase text-[10px] font-bold tracking-wider">
                    <tr>
                        <th class="p-4 pl-6">Product</th>
                        <th class="p-4">Size Variant</th>
                        <th class="p-4">SKU</th>
                        <th class="p-4">Price</th>
                        <th class="p-4">Stock Status</th>
                        <th class="p-4 pr-6 text-right">Inline Stock Update</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($variants as $variant)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="p-4 pl-6 flex items-center gap-3">
                                <img src="{{ asset($variant->product->image ?? 'images/products/mango_pickle.png') }}" class="w-10 h-10 rounded-xl object-cover border border-slate-200 bg-amber-50 shrink-0">
                                <div>
                                    <h4 class="font-bold text-slate-900 text-xs">{{ $variant->product->name }}</h4>
                                    <span class="text-[10px] text-slate-400">{{ $variant->product->category->name }}</span>
                                </div>
                            </td>
                            <td class="p-4 font-bold text-slate-800">{{ $variant->weight_value }}</td>
                            <td class="p-4 font-mono text-slate-500 font-semibold">{{ $variant->sku }}</td>
                            <td class="p-4 font-bold text-slate-900">₹{{ number_format($variant->effective_price, 2) }}</td>
                            <td class="p-4">
                                @if($variant->stock_quantity <= 0)
                                    <span class="px-2.5 py-0.5 rounded-full bg-rose-100 text-rose-800 text-[10px] font-bold">Out of Stock</span>
                                @elseif($variant->stock_quantity <= 25)
                                    <span class="px-2.5 py-0.5 rounded-full bg-amber-100 text-amber-800 text-[10px] font-bold">Low ({{ $variant->stock_quantity }})</span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-800 text-[10px] font-bold">Available ({{ $variant->stock_quantity }})</span>
                                @endif
                            </td>
                            <td class="p-4 pr-6 text-right">
                                <form action="{{ route('admin.inventory.update', $variant->id) }}" method="POST" class="inline-flex items-center gap-2">
                                    @csrf
                                    @method('PUT')
                                    <input type="number" name="stock_quantity" min="0" required value="{{ $variant->stock_quantity }}" class="w-20 p-2 text-center text-xs font-bold bg-slate-50 border border-slate-300 rounded-xl focus:ring-2 focus:ring-amber-500">
                                    <button type="submit" class="px-3 py-2 bg-amber-600 hover:bg-amber-700 text-white font-bold text-xs rounded-xl shadow-xs transition-colors flex items-center gap-1">
                                        <i class="fa-solid fa-floppy-disk text-[10px]"></i>
                                        <span>Update</span>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-12 text-center text-slate-400">No inventory matches found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-100">
            {{ $variants->links() }}
        </div>
    </div>
</div>
@endsection
