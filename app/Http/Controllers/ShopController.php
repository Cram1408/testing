<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::active()->withCount('products')->orderBy('sort_order')->get();
        $query = Product::active()->with(['category', 'variants']);

        // Filter by category
        if ($request->filled('category')) {
            $catSlug = $request->get('category');
            $query->whereHas('category', function ($q) use ($catSlug) {
                $q->where('slug', $catSlug);
            });
        }

        // Search query
        if ($request->filled('q')) {
            $search = $request->get('q');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('tagline', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('ingredients', 'like', "%{$search}%");
            });
        }

        // Filter by spice level
        if ($request->filled('spice')) {
            $query->where('spice_level', $request->get('spice'));
        }

        // Sorting
        $sort = $request->get('sort', 'popular');
        switch ($sort) {
            case 'price_low':
                $query->withMin('variants', 'price')->orderBy('variants_min_price', 'asc');
                break;
            case 'price_high':
                $query->withMax('variants', 'price')->orderBy('variants_max_price', 'desc');
                break;
            case 'newest':
                $query->latest();
                break;
            case 'popular':
            default:
                $query->orderByDesc('is_bestseller')->latest();
                break;
        }

        $products = $query->paginate(9)->withQueryString();
        $currentCategory = $request->filled('category') ? Category::where('slug', $request->get('category'))->first() : null;

        return view('shop.index', compact('products', 'categories', 'currentCategory', 'sort'));
    }

    public function show($slug)
    {
        $product = Product::active()->where('slug', $slug)->with(['category', 'variants'])->firstOrFail();
        $relatedProducts = Product::active()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->with(['category', 'variants'])
            ->take(4)
            ->get();

        return view('shop.show', compact('product', 'relatedProducts'));
    }

    public function category($slug)
    {
        $category = Category::active()->where('slug', $slug)->firstOrFail();
        return redirect()->route('shop.index', ['category' => $category->slug]);
    }
}
