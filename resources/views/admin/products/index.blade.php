@extends('layouts.admin')

@section('title', 'Products & Variants - Admin')
@section('header_title', 'Product Catalog')
@section('header_subtitle', 'Manage pickles, mouth fresheners, weights, and pricing')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <!-- Search & Filter -->
        <form method="GET" action="{{ route('admin.products.index') }}" class="flex flex-wrap items-center gap-3">
            <div class="relative">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Search products..." class="bg-amber-50/40 border border-amber-200/80 rounded-xl py-2 pl-9 pr-4 text-xs font-medium focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-600 text-slate-900 placeholder:text-slate-400">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-3 text-amber-700/60 text-xs"></i>
            </div>
            <select name="category" onchange="this.form.submit()" class="bg-amber-50/40 border border-amber-200/80 rounded-xl py-2 px-3 text-xs font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-amber-500">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
        </form>

        <a href="{{ route('admin.products.create') }}" class="px-4 py-2.5 bg-gradient-to-r from-amber-700 to-amber-900 hover:from-amber-800 hover:to-amber-950 text-white font-bold text-xs rounded-xl shadow-md transition-all flex items-center gap-1.5 self-start sm:self-auto">
            <i class="fa-solid fa-plus text-xs"></i>
            <span>Add New Product</span>
        </a>
    </div>

    <div class="bg-white rounded-3xl border border-amber-200/70 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-[#FFFDF9] border-b border-amber-200/60 text-amber-900/60 uppercase text-[10px] font-bold tracking-wider">
                    <tr>
                        <th class="p-4 pl-6">Product</th>
                        <th class="p-4">Category</th>
                        <th class="p-4">Variants (Weight & Price)</th>
                        <th class="p-4">Total Stock</th>
                        <th class="p-4">Badges</th>
                        <th class="p-4">Status</th>
                        <th class="p-4 pr-6 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-amber-100/60">
                    @forelse($products as $product)
                        <tr class="hover:bg-amber-50/30 transition-colors">
                            <td class="p-4 pl-6 flex items-center gap-3">
                                <img src="{{ asset($product->image ?? 'images/products/mango_pickle.png') }}" onerror="this.onerror=null;this.src='{{ asset('images/products/mango_pickle.png') }}';" alt="{{ $product->name }}" class="w-14 h-14 rounded-2xl object-cover border border-amber-200 bg-amber-50 shrink-0">
                                <div>
                                    <h4 class="font-bold text-slate-900 text-sm">
                                        <a href="{{ route('admin.products.edit', $product->id) }}" class="hover:text-amber-800">{{ $product->name }}</a>
                                    </h4>
                                    <p class="text-[11px] text-slate-400 line-clamp-1 max-w-xs">{{ $product->tagline ?? $product->short_description }}</p>
                                </div>
                            </td>
                            <td class="p-4">
                                <span class="px-2.5 py-0.5 rounded-full bg-amber-100/70 text-amber-900 border border-amber-200/60 font-bold text-[10px]">
                                    {{ $product->category->name }}
                                </span>
                            </td>
                            <td class="p-4">
                                <div class="space-y-1">
                                    @foreach($product->variants as $v)
                                        <div class="flex items-center gap-2 text-[11px]">
                                            <span class="font-bold text-slate-800">{{ $v->weight_value }}:</span>
                                            <span class="font-semibold text-amber-900">₹{{ number_format($v->effective_price, 2) }}</span>
                                            @if($v->sale_price)
                                                <span class="text-slate-400 line-through text-[10px]">₹{{ number_format($v->price, 2) }}</span>
                                            @endif
                                            <span class="text-[10px] {{ $v->stock_quantity <= 10 ? 'text-rose-600 font-bold' : 'text-slate-400' }}">({{ $v->stock_quantity }} qty)</span>
                                        </div>
                                    @endforeach
                                </div>
                            </td>
                            <td class="p-4 font-bold">
                                @php $totalStock = $product->variants->sum('stock_quantity'); @endphp
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold {{ $totalStock <= 20 ? 'bg-rose-100 text-rose-800 border border-rose-200' : 'bg-emerald-100 text-emerald-800 border border-emerald-200' }}">
                                    {{ $totalStock }} Units
                                </span>
                            </td>
                            <td class="p-4">
                                <div class="flex flex-col gap-1 items-start">
                                    @if($product->is_bestseller)
                                        <span class="px-2 py-0.5 rounded-full bg-amber-100 text-amber-900 text-[10px] font-bold border border-amber-300">Bestseller</span>
                                    @endif
                                    @if($product->is_featured)
                                        <span class="px-2 py-0.5 rounded-full bg-spice-100 text-spice-800 text-[10px] font-bold border border-orange-200">Featured</span>
                                    @endif
                                </div>
                            </td>
                            <td class="p-4">
                                @if($product->is_active)
                                    <span class="px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-800 border border-emerald-200 text-[10px] font-bold">Active</span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full bg-amber-50 text-slate-500 border border-amber-200/60 text-[10px] font-bold">Draft</span>
                                @endif
                            </td>
                            <td class="p-4 pr-6 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('shop.show', $product->slug) }}" target="_blank" class="p-2 rounded-xl bg-amber-50 hover:bg-amber-100 text-amber-900 border border-amber-200/70 transition-colors" title="View in Store">
                                        <i class="fa-solid fa-arrow-up-right-from-square text-xs"></i>
                                    </a>
                                    <a href="{{ route('admin.products.edit', $product->id) }}" class="p-2 rounded-xl bg-amber-50 hover:bg-amber-100 text-amber-900 border border-amber-200/70 transition-colors" title="Edit Product">
                                        <i class="fa-solid fa-pen-to-square text-xs"></i>
                                    </a>
                                    <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this product and all its variants?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-200/70 transition-colors" title="Delete">
                                            <i class="fa-solid fa-trash-can text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-12 text-center text-slate-400">
                                No products found. <a href="{{ route('admin.products.create') }}" class="text-amber-800 font-bold hover:underline">Add one now!</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-amber-100">
            {{ $products->links() }}
        </div>
    </div>
</div>
@endsection
