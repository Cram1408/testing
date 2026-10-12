@extends('layouts.admin')

@section('title', 'Add Product - Admin')
@section('header_title', 'New Pickle / Mukhwas Product')
@section('header_subtitle', 'Create product with multiple weight variants and inventory')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <h3 class="font-bold text-base text-slate-900">Add New Product</h3>
        <a href="{{ route('admin.products.index') }}" class="text-xs font-bold text-slate-500 hover:text-slate-800">← Back to Products</a>
    </div>

    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

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
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Product Name *</label>
                    <input type="text" name="name" required value="{{ old('name') }}" placeholder="e.g. Dadi's Special Mango Pickle" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs font-semibold focus:ring-2 focus:ring-amber-500">
                    @error('name') <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Tagline</label>
                    <input type="text" name="tagline" value="{{ old('tagline') }}" placeholder="e.g. Handcrafted Raw Mango in Cold-Pressed Mustard Oil" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs focus:ring-2 focus:ring-amber-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Spice Profile</label>
                    <select name="spice_level" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs font-semibold focus:ring-2 focus:ring-amber-500">
                        <option value="Spicy">Spicy</option>
                        <option value="Extra Spicy">Extra Spicy</option>
                        <option value="Medium">Medium</option>
                        <option value="Mild">Mild</option>
                        <option value="Sweet & Tangy">Sweet & Tangy</option>
                        <option value="Aromatic & Refreshing">Aromatic & Refreshing</option>
                    </select>
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Short Summary *</label>
                    <textarea name="short_description" rows="2" required placeholder="Quick appetizing 1-2 lines..." class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs focus:ring-2 focus:ring-amber-500">{{ old('short_description') }}</textarea>
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Full Description & Heritage Story</label>
                    <textarea name="description" rows="4" placeholder="Detailed culinary story, maturation period, and serving suggestions..." class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs focus:ring-2 focus:ring-amber-500">{{ old('description') }}</textarea>
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Ingredients List</label>
                    <textarea name="ingredients" rows="2" placeholder="e.g. Raw Mango Chunks, Cold-Pressed Mustard Oil, Fenugreek, Mustard Seeds, Rock Salt..." class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs focus:ring-2 focus:ring-amber-500">{{ old('ingredients') }}</textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Shelf Life</label>
                    <input type="text" name="shelf_life" value="{{ old('shelf_life', '12 Months') }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs focus:ring-2 focus:ring-amber-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Product Photo</label>
                    <input type="file" name="image" accept="image/*" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-600">
                </div>
            </div>

            <div class="flex flex-wrap gap-6 pt-2 border-t border-slate-100">
                <label class="flex items-center gap-2 cursor-pointer text-xs font-bold text-slate-700">
                    <input type="checkbox" name="is_featured" value="1" class="rounded text-amber-600 focus:ring-amber-500">
                    <span>Featured Product</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer text-xs font-bold text-slate-700">
                    <input type="checkbox" name="is_bestseller" value="1" checked class="rounded text-amber-600 focus:ring-amber-500">
                    <span>Best Seller Badge</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer text-xs font-bold text-slate-700">
                    <input type="checkbox" name="is_active" value="1" checked class="rounded text-amber-600 focus:ring-amber-500">
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
                        <span>Product Weight Variants & Pricing</span>
                    </h4>
                    <p class="text-xs text-slate-400 mt-0.5">Every product can have multiple sizes (e.g. 250g Jar, 500g Jar, 1kg Family Pack).</p>
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
                            <th class="p-3">Regular Price (₹) *</th>
                            <th class="p-3">Sale Price (₹)</th>
                            <th class="p-3">Stock Units *</th>
                            <th class="p-3 text-center">Default</th>
                            <th class="p-3 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody id="variantsBody" class="divide-y divide-slate-100">
                        <!-- Variant Row 1 (Default) -->
                        <tr class="variant-row">
                            <td class="p-2.5">
                                <input type="text" name="variants[0][weight_value]" required value="250g Jar" placeholder="250g Jar" class="w-28 bg-slate-50 border border-slate-200 rounded-lg p-2 text-xs font-bold text-slate-900">
                            </td>
                            <td class="p-2.5">
                                <input type="text" name="variants[0][sku]" placeholder="PKL-001" class="w-24 bg-slate-50 border border-slate-200 rounded-lg p-2 text-xs font-mono">
                            </td>
                            <td class="p-2.5">
                                <input type="number" step="0.01" name="variants[0][price]" required value="149.00" class="w-24 bg-slate-50 border border-slate-200 rounded-lg p-2 text-xs font-bold">
                            </td>
                            <td class="p-2.5">
                                <input type="number" step="0.01" name="variants[0][sale_price]" value="129.00" placeholder="Optional" class="w-24 bg-slate-50 border border-slate-200 rounded-lg p-2 text-xs">
                            </td>
                            <td class="p-2.5">
                                <input type="number" name="variants[0][stock_quantity]" required value="50" class="w-20 bg-slate-50 border border-slate-200 rounded-lg p-2 text-xs font-bold text-center">
                            </td>
                            <td class="p-2.5 text-center">
                                <input type="radio" name="default_variant_radio" checked onchange="setDefaultRadio(0)" class="text-amber-600 focus:ring-amber-500">
                                <input type="hidden" name="variants[0][is_default]" id="is_default_0" value="1">
                            </td>
                            <td class="p-2.5 text-right">
                                <button type="button" onclick="removeVariantRow(this)" class="text-slate-300 hover:text-rose-600 p-1.5 transition-colors" title="Delete Variant">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </td>
                        </tr>

                        <!-- Variant Row 2 -->
                        <tr class="variant-row">
                            <td class="p-2.5">
                                <input type="text" name="variants[1][weight_value]" required value="500g Jar" placeholder="500g Jar" class="w-28 bg-slate-50 border border-slate-200 rounded-lg p-2 text-xs font-bold text-slate-900">
                            </td>
                            <td class="p-2.5">
                                <input type="text" name="variants[1][sku]" placeholder="PKL-002" class="w-24 bg-slate-50 border border-slate-200 rounded-lg p-2 text-xs font-mono">
                            </td>
                            <td class="p-2.5">
                                <input type="number" step="0.01" name="variants[1][price]" required value="269.00" class="w-24 bg-slate-50 border border-slate-200 rounded-lg p-2 text-xs font-bold">
                            </td>
                            <td class="p-2.5">
                                <input type="number" step="0.01" name="variants[1][sale_price]" value="239.00" placeholder="Optional" class="w-24 bg-slate-50 border border-slate-200 rounded-lg p-2 text-xs">
                            </td>
                            <td class="p-2.5">
                                <input type="number" name="variants[1][stock_quantity]" required value="75" class="w-20 bg-slate-50 border border-slate-200 rounded-lg p-2 text-xs font-bold text-center">
                            </td>
                            <td class="p-2.5 text-center">
                                <input type="radio" name="default_variant_radio" onchange="setDefaultRadio(1)" class="text-amber-600 focus:ring-amber-500">
                                <input type="hidden" name="variants[1][is_default]" id="is_default_1" value="0">
                            </td>
                            <td class="p-2.5 text-right">
                                <button type="button" onclick="removeVariantRow(this)" class="text-slate-300 hover:text-rose-600 p-1.5 transition-colors" title="Delete Variant">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('admin.products.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-300 text-slate-700 text-xs font-bold hover:bg-slate-50 transition-colors">Cancel</a>
            <button type="submit" class="px-8 py-3 rounded-xl bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold shadow-md transition-all">Publish Product & Variants</button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    let variantIndex = 2;

    function addVariantRow() {
        const body = document.getElementById('variantsBody');
        const tr = document.createElement('tr');
        tr.className = 'variant-row';
        tr.innerHTML = `
            <td class="p-2.5">
                <input type="text" name="variants[${variantIndex}][weight_value]" required value="1kg Pack" placeholder="1kg Pack" class="w-28 bg-slate-50 border border-slate-200 rounded-lg p-2 text-xs font-bold text-slate-900">
            </td>
            <td class="p-2.5">
                <input type="text" name="variants[${variantIndex}][sku]" placeholder="SKU" class="w-24 bg-slate-50 border border-slate-200 rounded-lg p-2 text-xs font-mono">
            </td>
            <td class="p-2.5">
                <input type="number" step="0.01" name="variants[${variantIndex}][price]" required value="499.00" class="w-24 bg-slate-50 border border-slate-200 rounded-lg p-2 text-xs font-bold">
            </td>
            <td class="p-2.5">
                <input type="number" step="0.01" name="variants[${variantIndex}][sale_price]" placeholder="Optional" class="w-24 bg-slate-50 border border-slate-200 rounded-lg p-2 text-xs">
            </td>
            <td class="p-2.5">
                <input type="number" name="variants[${variantIndex}][stock_quantity]" required value="30" class="w-20 bg-slate-50 border border-slate-200 rounded-lg p-2 text-xs font-bold text-center">
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
</script>
@endpush
@endsection
