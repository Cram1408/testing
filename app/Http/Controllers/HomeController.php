<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::active()->withCount('products')->orderBy('sort_order')->get();
        $featuredProducts = Product::active()->featured()->with(['category', 'variants'])->take(6)->get();
        $bestsellers = Product::active()->bestseller()->with(['category', 'variants'])->take(8)->get();
        $recentProducts = Product::active()->with(['category', 'variants'])->latest()->take(4)->get();

        return view('home', compact('categories', 'featuredProducts', 'bestsellers', 'recentProducts'));
    }
}
