@extends('layouts.admin')

@section('title', 'Edit Category - Admin')
@section('header_title', 'Edit Category')
@section('header_subtitle', 'Update ' . $category->name)

@section('content')
<div class="max-w-2xl mx-auto bg-white rounded-3xl p-8 border border-slate-200/80 shadow-sm space-y-6">
    <div class="flex items-center justify-between border-b border-slate-100 pb-4">
        <h3 class="font-bold text-base text-slate-900">Edit Category: {{ $category->name }}</h3>
        <a href="{{ route('admin.categories.index') }}" class="text-xs font-bold text-slate-500 hover:text-slate-800">Cancel</a>
    </div>

    <form action="{{ route('admin.categories.update', $category->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Category Name *</label>
            <input type="text" name="name" required value="{{ old('name', $category->name) }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-amber-500">
            @error('name') <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Slug</label>
            <input type="text" name="slug" value="{{ old('slug', $category->slug) }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs font-mono focus:outline-none focus:ring-2 focus:ring-amber-500">
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Description</label>
            <textarea name="description" rows="3" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs font-medium focus:outline-none focus:ring-2 focus:ring-amber-500">{{ old('description', $category->description) }}</textarea>
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Category Image</label>
            @if($category->image)
                <div class="mb-2 flex items-center gap-3">
                    <img src="{{ asset($category->image) }}" class="w-16 h-16 rounded-xl object-cover border border-slate-200">
                    <span class="text-[11px] text-slate-500">Current Image</span>
                </div>
            @endif
            <input type="file" name="image" accept="image/*" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-600">
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Sort Order</label>
                <input type="number" name="sort_order" value="{{ old('sort_order', $category->sort_order) }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-amber-500">
            </div>
            <div class="flex items-center pt-6">
                <label class="flex items-center gap-2 cursor-pointer text-xs font-bold text-slate-700">
                    <input type="checkbox" name="is_active" value="1" {{ $category->is_active ? 'checked' : '' }} class="rounded text-amber-600 focus:ring-amber-500">
                    <span>Active on Storefront</span>
                </label>
            </div>
        </div>

        <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
            <a href="{{ route('admin.categories.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-300 text-slate-700 text-xs font-bold hover:bg-slate-50 transition-colors">Cancel</a>
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold shadow-md transition-all">Update Category</button>
        </div>
    </form>
</div>
@endsection
