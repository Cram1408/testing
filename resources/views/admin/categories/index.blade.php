@extends('layouts.admin')

@section('title', 'Categories - Admin')
@section('header_title', 'Category Management')
@section('header_subtitle', 'Organize product collections and catalog taxonomy')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h3 class="font-bold text-base text-slate-800">All Categories ({{ $categories->total() }})</h3>
        <a href="{{ route('admin.categories.create') }}" class="px-4 py-2.5 bg-amber-600 hover:bg-amber-700 text-white font-bold text-xs rounded-xl shadow-md transition-all flex items-center gap-1.5">
            <i class="fa-solid fa-plus text-xs"></i>
            <span>Add New Category</span>
        </a>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 border-b border-slate-200 text-slate-400 uppercase text-[10px] font-bold tracking-wider">
                    <tr>
                        <th class="p-4 pl-6">Category</th>
                        <th class="p-4">Slug</th>
                        <th class="p-4">Products</th>
                        <th class="p-4">Sort Order</th>
                        <th class="p-4">Status</th>
                        <th class="p-4 pr-6 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($categories as $category)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="p-4 pl-6 flex items-center gap-3">
                                <img src="{{ asset($category->image ?? 'images/products/mango_pickle.png') }}" alt="{{ $category->name }}" class="w-12 h-12 rounded-xl object-cover border border-slate-200 bg-amber-50 shrink-0">
                                <div>
                                    <h4 class="font-bold text-slate-900 text-sm">{{ $category->name }}</h4>
                                    <p class="text-[11px] text-slate-400 line-clamp-1 max-w-xs">{{ $category->description }}</p>
                                </div>
                            </td>
                            <td class="p-4 font-mono text-slate-600 font-semibold">{{ $category->slug }}</td>
                            <td class="p-4 font-bold text-slate-900">
                                <span class="px-2.5 py-0.5 rounded-full bg-amber-100 text-amber-900 font-bold text-[11px]">
                                    {{ $category->products_count }} Products
                                </span>
                            </td>
                            <td class="p-4 text-slate-600 font-semibold">{{ $category->sort_order }}</td>
                            <td class="p-4">
                                @if($category->is_active)
                                    <span class="px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-800 text-[10px] font-bold">Active</span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-600 text-[10px] font-bold">Hidden</span>
                                @endif
                            </td>
                            <td class="p-4 pr-6 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.categories.edit', $category->id) }}" class="p-2 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 transition-colors" title="Edit">
                                        <i class="fa-solid fa-pen-to-square text-xs"></i>
                                    </a>
                                    <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this category?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-600 transition-colors" title="Delete">
                                            <i class="fa-solid fa-trash-can text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-100">
            {{ $categories->links() }}
        </div>
    </div>
</div>
@endsection
