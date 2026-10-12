<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $query = ProductVariant::with(['product.category']);

        if ($request->get('filter') === 'low') {
            $query->where('stock_quantity', '<=', 25)->where('stock_quantity', '>', 0);
        } elseif ($request->get('filter') === 'out') {
            $query->where('stock_quantity', '<=', 0);
        }

        if ($request->filled('q')) {
            $search = $request->get('q');
            $query->where(function ($q) use ($search) {
                $q->where('sku', 'like', "%{$search}%")
                  ->orWhere('weight_value', 'like', "%{$search}%")
                  ->orWhereHas('product', function ($p) use ($search) {
                      $p->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $variants = $query->orderBy('stock_quantity', 'asc')->paginate(15)->withQueryString();

        $stats = [
            'total_items' => ProductVariant::count(),
            'low_stock' => ProductVariant::where('stock_quantity', '<=', 25)->where('stock_quantity', '>', 0)->count(),
            'out_of_stock' => ProductVariant::where('stock_quantity', '<=', 0)->count(),
        ];

        return view('admin.inventory.index', compact('variants', 'stats'));
    }

    public function update(Request $request, ProductVariant $variant)
    {
        $request->validate([
            'stock_quantity' => 'required|integer|min:0',
        ]);

        $variant->update([
            'stock_quantity' => $request->stock_quantity,
        ]);

        return back()->with('success', "Stock updated for {$variant->product->name} ({$variant->weight_value}) to {$request->stock_quantity} units.");
    }
}
