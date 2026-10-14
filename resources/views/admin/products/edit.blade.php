@extends('layouts.admin')

@section('title', 'Edit Product - Admin')
@section('header_title', 'Edit ' . $product->name)
@section('header_subtitle', 'Update product details and multi-weight variants')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <h3 class="font-bold text-base text-slate-900">Edit Product: {{ $product->name }}</h3>
        <a href="{{ route('admin.products.index') }}" class="text-xs font-bold text-slate-500 hover:text-slate-800">← Back to Products</a>
    </div>

    <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- General Info Card -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-sm space-y-6">
            <h4 class="font-bold text-sm text-slate-900 border-b border-slate-100 pb-3 flex items-center gap-2">
                <i class="fa-solid fa-jar text-amber-600"></i>
                <span>General Information</span>
            </h4>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Category *</label>
                    <select name="category_id" required class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs font-semibold focus:ring-2 focus:ring-amber-500">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ $product->category_id == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Product Name *</label>
                    <input type="text" name="name" required value="{{ old('name', $product->name) }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs font-semibold focus:ring-2 focus:ring-amber-500">
                    @error('name') <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Custom Slug</label>
                    <input type="text" name="slug" value="{{ old('slug', $product->slug) }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs font-mono focus:ring-2 focus:ring-amber-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Spice Profile</label>
                    <select name="spice_level" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs font-semibold focus:ring-2 focus:ring-amber-500">
                        @foreach(['Spicy', 'Extra Spicy', 'Medium', 'Mild', 'Sweet & Tangy', 'Aromatic & Refreshing'] as $sp)
                            <option value="{{ $sp }}" {{ $product->spice_level === $sp ? 'selected' : '' }}>{{ $sp }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Tagline</label>
                    <input type="text" name="tagline" value="{{ old('tagline', $product->tagline) }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs focus:ring-2 focus:ring-amber-500">
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Short Summary *</label>
                    <textarea name="short_description" rows="2" required class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs focus:ring-2 focus:ring-amber-500">{{ old('short_description', $product->short_description) }}</textarea>
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Full Description & Heritage Story</label>
                    <textarea name="description" rows="4" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs focus:ring-2 focus:ring-amber-500">{{ old('description', $product->description) }}</textarea>
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Ingredients List</label>
                    <textarea name="ingredients" rows="2" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs focus:ring-2 focus:ring-amber-500">{{ old('ingredients', $product->ingredients) }}</textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Shelf Life</label>
                    <input type="text" name="shelf_life" value="{{ old('shelf_life', $product->shelf_life) }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs focus:ring-2 focus:ring-amber-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Product Photo</label>

                    {{-- Current photo / placeholder --}}
                    <div class="mb-3 flex items-center gap-3">
                        @if($product->image)
                            <img id="currentProductImg"
                                 src="{{ asset('storage/' . $product->image) }}"
                                 class="w-16 h-16 rounded-xl object-cover border border-slate-200 shadow-sm"
                                 onerror="this.style.display='none'; document.getElementById('noImgPlaceholder').style.display='flex';">
                        @endif

                        {{-- Black placeholder shown when no image or image fails to load --}}
                        <div id="noImgPlaceholder"
                             class="w-16 h-16 rounded-xl bg-slate-900 flex items-center justify-center border border-slate-700 shadow-sm shrink-0"
                             style="{{ $product->image ? 'display:none' : 'display:flex' }}">
                            <i class="fa-solid fa-image text-slate-600 text-lg"></i>
                        </div>

                        <div>
                            <p class="text-[11px] font-semibold text-slate-600">Current Photo</p>
                            @if($product->image)
                                <p class="text-[10px] text-slate-400 mt-0.5 break-all">{{ basename($product->image) }}</p>
                            @else
                                <p class="text-[10px] text-slate-400 mt-0.5 italic">No image uploaded</p>
                            @endif
                        </div>
                    </div>

                    {{-- File input with live preview --}}
                    <input type="file" name="image" id="productImageInput" accept="image/*"
                           onchange="previewProductImage(event)"
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-600 cursor-pointer">
                    @error('image') <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="flex flex-wrap gap-6 pt-2 border-t border-slate-100">
                <label class="flex items-center gap-2 cursor-pointer text-xs font-bold text-slate-700">
                    <input type="checkbox" name="is_featured" value="1" {{ $product->is_featured ? 'checked' : '' }} class="rounded text-amber-600 focus:ring-amber-500">
                    <span>Featured Product</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer text-xs font-bold text-slate-700">
                    <input type="checkbox" name="is_bestseller" value="1" {{ $product->is_bestseller ? 'checked' : '' }} class="rounded text-amber-600 focus:ring-amber-500">
                    <span>Best Seller Badge</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer text-xs font-bold text-slate-700">
                    <input type="checkbox" name="is_active" value="1" {{ $product->is_active ? 'checked' : '' }} class="rounded text-amber-600 focus:ring-amber-500">
                    <span>Active on Storefront</span>
                </label>
            </div>
        </div>

        <!-- Product Variants Builder Card -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-sm space-y-6">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <div>
                    <h4 class="font-bold text-sm text-slate-900 flex items-center gap-2">
                        <i class="fa-solid fa-weight-scale text-amber-600"></i>
                        <span>Weight Variants & Pricing</span>
                    </h4>
                    <p class="text-xs text-slate-400 mt-0.5">Manage weights, SKUs, prices, and stock units.</p>
                </div>
                <button type="button" onclick="addVariantRow()" class="px-3.5 py-1.5 bg-amber-50 hover:bg-amber-100 text-amber-900 border border-amber-300 font-bold text-xs rounded-xl transition-colors flex items-center gap-1.5">
                    <i class="fa-solid fa-plus text-[10px]"></i>
                    <span>Add Size Variant</span>
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs" id="variantsTable">
                    <thead class="bg-slate-50 border-b border-slate-200 text-slate-400 uppercase text-[10px] font-bold">
                        <tr>
                            <th class="p-3">Weight / Size *</th>
                            <th class="p-3">SKU</th>
                            <th class="p-3">Price (₹) *</th>
                            <th class="p-3">Sale Price (₹)</th>
                            <th class="p-3">Stock Units *</th>
                            <th class="p-3 text-center">Default</th>
                            <th class="p-3 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody id="variantsBody" class="divide-y divide-slate-100">
                        @foreach($product->variants as $idx => $v)
                            <tr class="variant-row">
                                <input type="hidden" name="variants[{{ $idx }}][id]" value="{{ $v->id }}">
                                <td class="p-2.5">
                                    <input type="text" name="variants[{{ $idx }}][weight_value]" required value="{{ $v->weight_value }}" class="w-28 bg-slate-50 border border-slate-200 rounded-lg p-2 text-xs font-bold text-slate-900">
                                </td>
                                <td class="p-2.5">
                                    <input type="text" name="variants[{{ $idx }}][sku]" value="{{ $v->sku }}" class="w-24 bg-slate-50 border border-slate-200 rounded-lg p-2 text-xs font-mono">
                                </td>
                                <td class="p-2.5">
                                    <input type="number" step="0.01" name="variants[{{ $idx }}][price]" required value="{{ $v->price }}" class="w-24 bg-slate-50 border border-slate-200 rounded-lg p-2 text-xs font-bold">
                                </td>
                                <td class="p-2.5">
                                    <input type="number" step="0.01" name="variants[{{ $idx }}][sale_price]" value="{{ $v->sale_price }}" placeholder="Optional" class="w-24 bg-slate-50 border border-slate-200 rounded-lg p-2 text-xs">
                                </td>
                                <td class="p-2.5">
                                    <input type="number" name="variants[{{ $idx }}][stock_quantity]" required value="{{ $v->stock_quantity }}" class="w-20 bg-slate-50 border border-slate-200 rounded-lg p-2 text-xs font-bold text-center">
                                </td>
                                <td class="p-2.5 text-center">
                                    <input type="radio" name="default_variant_radio" {{ $v->is_default ? 'checked' : '' }} onchange="setDefaultRadio({{ $idx }})" class="text-amber-600 focus:ring-amber-500">
                                    <input type="hidden" name="variants[{{ $idx }}][is_default]" id="is_default_{{ $idx }}" value="{{ $v->is_default ? '1' : '0' }}">
                                </td>
                                <td class="p-2.5 text-right">
                                    <button type="button" onclick="removeVariantRow(this)" class="text-slate-300 hover:text-rose-600 p-1.5 transition-colors" title="Delete Variant">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('admin.products.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-300 text-slate-700 text-xs font-bold hover:bg-slate-50 transition-colors">Cancel</a>
            <button type="submit" class="px-8 py-3 rounded-xl bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold shadow-md transition-all">Update Product & Variants</button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    let variantIndex = {{ count($product->variants) + 10 }};

    function addVariantRow() {
        const body = document.getElementById('variantsBody');
        const tr = document.createElement('tr');
        tr.className = 'variant-row';
        tr.innerHTML = `
            <td class="p-2.5">
                <input type="text" name="variants[${variantIndex}][weight_value]" required value="Pack" placeholder="e.g. 1kg Jar" class="w-28 bg-slate-50 border border-slate-200 rounded-lg p-2 text-xs font-bold text-slate-900">
            </td>
            <td class="p-2.5">
                <input type="text" name="variants[${variantIndex}][sku]" placeholder="SKU" class="w-24 bg-slate-50 border border-slate-200 rounded-lg p-2 text-xs font-mono">
            </td>
            <td class="p-2.5">
                <input type="number" step="0.01" name="variants[${variantIndex}][price]" required value="199.00" class="w-24 bg-slate-50 border border-slate-200 rounded-lg p-2 text-xs font-bold">
            </td>
            <td class="p-2.5">
                <input type="number" step="0.01" name="variants[${variantIndex}][sale_price]" placeholder="Optional" class="w-24 bg-slate-50 border border-slate-200 rounded-lg p-2 text-xs">
            </td>
            <td class="p-2.5">
                <input type="number" name="variants[${variantIndex}][stock_quantity]" required value="25" class="w-20 bg-slate-50 border border-slate-200 rounded-lg p-2 text-xs font-bold text-center">
            </td>
            <td class="p-2.5 text-center">
                <input type="radio" name="default_variant_radio" onchange="setDefaultRadio(${variantIndex})" class="text-amber-600 focus:ring-amber-500">
                <input type="hidden" name="variants[${variantIndex}][is_default]" id="is_default_${variantIndex}" value="0">
            </td>
            <td class="p-2.5 text-right">
                <button type="button" onclick="removeVariantRow(this)" class="text-slate-300 hover:text-rose-600 p-1.5 transition-colors" title="Delete Variant">
                    <i class="fa-solid fa-trash-can"></i>
                </button>
            </td>
        `;
        body.appendChild(tr);
        variantIndex++;
    }

    function removeVariantRow(btn) {
        const rows = document.querySelectorAll('.variant-row');
        if (rows.length <= 1) {
            alert('A product must have at least one weight variant.');
            return;
        }
        btn.closest('tr').remove();
    }

    function setDefaultRadio(idx) {
        document.querySelectorAll('input[id^="is_default_"]').forEach(el => el.value = "0");
        const target = document.getElementById('is_default_' + idx);
        if (target) target.value = "1";
    }

    function previewProductImage(event) {
        const file = event.target.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function (e) {
            // Show/update the current image element
            let img = document.getElementById('currentProductImg');
            const placeholder = document.getElementById('noImgPlaceholder');

            if (!img) {
                // Image element doesn't exist yet (product had no image) — create it
                img = document.createElement('img');
                img.id = 'currentProductImg';
                img.className = 'w-16 h-16 rounded-xl object-cover border border-slate-200 shadow-sm';
                placeholder.parentNode.insertBefore(img, placeholder);
            }

            img.src = e.target.result;
            img.style.display = 'block';
            placeholder.style.display = 'none';
        };
        reader.readAsDataURL(file);
    }
</script>
@endpush
@endsection
