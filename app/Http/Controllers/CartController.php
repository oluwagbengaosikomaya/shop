<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class CartController extends Controller
{
    // Show cart
    public function index()
    {
        $cart = session()->get('cart', []);
        $total = collect($cart)->sum(function($item) {
            return $item['price'] * $item['quantity'];
        });
        return view('cart.index', compact('cart', 'total'));
    }

    // Add product to cart
public function add(Request $request, Product $product)
{
    $cart = session()->get('cart', []);

    $cart[$product->id] = [
        'product_id' => $product->id,
        'name'       => $product->name,
        'price'      => $product->price,
        'quantity'   => ($cart[$product->id]['quantity'] ?? 0) + 1,
        'image'      => $product->image ?: 'assets/images/no-image.png',
    ];

    session()->put('cart', $cart);

    return redirect()->back()->with('success', "{$product->name} added to cart!");
}

    // Update quantity
   public function update(Request $request, Product $product)
{
    $cart = session()->get('cart', []);
    $quantity = max(1, (int)$request->quantity);

    if(isset($cart[$product->id])) {
        $cart[$product->id]['quantity'] = $quantity;
        session(['cart' => $cart]);
    }

    $subtotal = $cart[$product->id]['price'] * $cart[$product->id]['quantity'];
    $total = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);
    $cartCount = collect($cart)->sum('quantity');

    return response()->json([
        'subtotal' => number_format($subtotal),
        'total' => number_format($total),
        'cartCount' => $cartCount
    ]);
}

// Remove product
public function remove(Product $product)
{
    $cart = session()->get('cart', []);

    if(isset($cart[$product->id])) {
        unset($cart[$product->id]);
        session(['cart' => $cart]);
    }

    $total = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);
    $cartCount = collect($cart)->sum('quantity');

    return response()->json([
        'total' => number_format($total),
        'cartCount' => $cartCount
    ]);
}

    public function count()
{
    $cart = session('cart', []);
    $total = collect($cart)->sum('quantity');

    return response()->json(['count' => $total]);
}
}
