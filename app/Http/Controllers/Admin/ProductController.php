<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::orderBy('name')->get();
        $query = Product::with(['category', 'variants']);

        if ($request->filled('category')) {
            $query->where('category_id', $request->get('category'));
        }

        if ($request->filled('q')) {
            $search = $request->get('q');
            $query->where('name', 'like', "%{$search}%");
        }

        $products = $query->latest()->paginate(10)->withQueryString();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::active()->orderBy('name')->get();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:150',
            'slug' => 'nullable|string|max:150|unique:products,slug',
            'tagline' => 'nullable|string|max:200',
            'short_description' => 'nullable|string',
            'description' => 'nullable|string',
            'ingredients' => 'nullable|string',
            'spice_level' => 'nullable|string|max:50',
            'shelf_life' => 'nullable|string|max:50',
            'storage_instructions' => 'nullable|string|max:150',
            'image' => 'nullable|image|max:2048',
            'is_featured' => 'nullable|boolean',
            'is_bestseller' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'variants' => 'required|array|min:1',
            'variants.*.weight_value' => 'required|string|max:50',
            'variants.*.sku' => 'nullable|string|max:50',
            'variants.*.price' => 'required|numeric|min:0',
            'variants.*.sale_price' => 'nullable|numeric|min:0',
            'variants.*.stock_quantity' => 'required|integer|min:0',
            'variants.*.is_default' => 'nullable|boolean',
        ]);

        $slug = !empty($validated['slug']) ? Str::slug($validated['slug']) : Str::slug($validated['name']);

        // Store uploaded image; leave null when no file is provided
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        DB::transaction(function () use ($validated, $slug, $imagePath, $request) {
            $product = Product::create([
                'category_id' => $validated['category_id'],
                'name' => $validated['name'],
                'slug' => $slug,
                'tagline' => $validated['tagline'] ?? null,
                'short_description' => $validated['short_description'] ?? null,
                'description' => $validated['description'] ?? null,
                'ingredients' => $validated['ingredients'] ?? null,
                'spice_level' => $validated['spice_level'] ?? null,
                'shelf_life' => $validated['shelf_life'] ?? '12 Months',
                'storage_instructions' => $validated['storage_instructions'] ?? 'Store in a cool, dry place. Keep airtight.',
                'image' => $imagePath,
                'is_featured' => $request->boolean('is_featured'),
                'is_bestseller' => $request->boolean('is_bestseller'),
                'is_active' => $request->boolean('is_active', true),
            ]);

            $defaultSet = false;
            foreach ($validated['variants'] as $idx => $v) {
                $isDef = !empty($v['is_default']) || (!$defaultSet && $idx === 0);
                if ($isDef) $defaultSet = true;

                $product->variants()->create([
                    'weight_value' => $v['weight_value'],
                    'sku' => $v['sku'] ?? 'SKU-' . strtoupper(Str::random(6)),
                    'price' => $v['price'],
                    'sale_price' => !empty($v['sale_price']) ? $v['sale_price'] : null,
                    'stock_quantity' => $v['stock_quantity'],
                    'is_default' => $isDef,
                ]);
            }
        });

        return redirect()->route('admin.products.index')->with('success', 'Product and variants created successfully.');
    }

    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get();
        $product->load('variants');
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:150',
            'slug' => 'nullable|string|max:150|unique:products,slug,' . $product->id,
            'tagline' => 'nullable|string|max:200',
            'short_description' => 'nullable|string',
            'description' => 'nullable|string',
            'ingredients' => 'nullable|string',
            'spice_level' => 'nullable|string|max:50',
            'shelf_life' => 'nullable|string|max:50',
            'storage_instructions' => 'nullable|string|max:150',
            'image' => 'nullable|image|max:2048',
            'is_featured' => 'nullable|boolean',
            'is_bestseller' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'variants' => 'required|array|min:1',
            'variants.*.id' => 'nullable|integer',
            'variants.*.weight_value' => 'required|string|max:50',
            'variants.*.sku' => 'nullable|string|max:50',
            'variants.*.price' => 'required|numeric|min:0',
            'variants.*.sale_price' => 'nullable|numeric|min:0',
            'variants.*.stock_quantity' => 'required|integer|min:0',
            'variants.*.is_default' => 'nullable|boolean',
        ]);

        $slug = !empty($validated['slug']) ? Str::slug($validated['slug']) : Str::slug($validated['name']);

        // Update image only when a new file is uploaded; delete the old one to avoid orphans
        $imagePath = $product->image;
        if ($request->hasFile('image')) {
            // Delete previous image from storage (only if it was a stored file, not a legacy path)
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            $imagePath = $request->file('image')->store('products', 'public');
        }

        DB::transaction(function () use ($validated, $slug, $imagePath, $product, $request) {
            $product->update([
                'category_id' => $validated['category_id'],
                'name' => $validated['name'],
                'slug' => $slug,
                'tagline' => $validated['tagline'] ?? null,
                'short_description' => $validated['short_description'] ?? null,
                'description' => $validated['description'] ?? null,
                'ingredients' => $validated['ingredients'] ?? null,
                'spice_level' => $validated['spice_level'] ?? null,
                'shelf_life' => $validated['shelf_life'] ?? '12 Months',
                'storage_instructions' => $validated['storage_instructions'] ?? 'Store in a cool, dry place. Keep airtight.',
                'image' => $imagePath,
                'is_featured' => $request->boolean('is_featured'),
                'is_bestseller' => $request->boolean('is_bestseller'),
                'is_active' => $request->boolean('is_active', true),
            ]);

            // Sync variants
            $existingVariantIds = [];
            foreach ($validated['variants'] as $v) {
                if (!empty($v['id'])) {
                    $existingVariantIds[] = $v['id'];
                    ProductVariant::where('id', $v['id'])->where('product_id', $product->id)->update([
                        'weight_value' => $v['weight_value'],
                        'sku' => $v['sku'] ?? 'SKU-' . strtoupper(Str::random(6)),
                        'price' => $v['price'],
                        'sale_price' => !empty($v['sale_price']) ? $v['sale_price'] : null,
                        'stock_quantity' => $v['stock_quantity'],
                        'is_default' => !empty($v['is_default']),
                    ]);
                } else {
                    $newVariant = $product->variants()->create([
                        'weight_value' => $v['weight_value'],
                        'sku' => $v['sku'] ?? 'SKU-' . strtoupper(Str::random(6)),
                        'price' => $v['price'],
                        'sale_price' => !empty($v['sale_price']) ? $v['sale_price'] : null,
                        'stock_quantity' => $v['stock_quantity'],
                        'is_default' => !empty($v['is_default']),
                    ]);
                    $existingVariantIds[] = $newVariant->id;
                }
            }

            // Remove omitted variants that are not referenced in active orders
            $product->variants()->whereNotIn('id', $existingVariantIds)->delete();
        });

        return redirect()->route('admin.products.index')->with('success', 'Product and variants updated successfully.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
    }
}
