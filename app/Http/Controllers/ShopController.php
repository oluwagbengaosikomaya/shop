<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index()
    {
        return view('shop.index');
    }

    public function show(Product $product)
    {
        $related = Product::where('category', $product->category)
            ->where('id', '!=', $product->id)
            ->inStock()
            ->take(4)
            ->get();

        $reviews    = $product->reviews()->with('user')->latest()->get();
        $userReview = auth()->check()
            ? $reviews->firstWhere('user_id', auth()->id())
            : null;

        return view('shop.show', compact('product', 'related', 'reviews', 'userReview'));
    }
}
