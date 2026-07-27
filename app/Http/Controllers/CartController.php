<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class CartController extends Controller
{
    public function index()
    {
        $cart  = session()->get('cart', []);
        $total = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);
        return view('cart.index', compact('cart', 'total'));
    }

    public function add(Request $request, Product $product)
    {
        if ($product->isOutOfStock()) {
            return response()->json(['error' => 'This product is out of stock.'], 422);
        }

        $cart    = session()->get('cart', []);
        $current = $cart[$product->id]['quantity'] ?? 0;

        if ($current + 1 > $product->stock) {
            return response()->json(['error' => "Only {$product->stock} in stock."], 422);
        }

        $cart[$product->id] = [
            'product_id' => $product->id,
            'name'       => $product->name,
            'price'      => $product->price,
            'quantity'   => $current + 1,
            'image'      => $product->image ?: 'assets/images/no-image.png',
            'stock'      => $product->stock,
        ];

        session()->put('cart', $cart);

        $cartCount = collect($cart)->sum('quantity');

        return response()->json([
            'success'   => "{$product->name} added to cart!",
            'cartCount' => $cartCount,
        ]);
    }

    public function update(Request $request, Product $product)
    {
        $cart     = session()->get('cart', []);
        $quantity = max(1, (int) $request->quantity);

        if ($quantity > $product->stock) {
            return response()->json(['error' => "Only {$product->stock} in stock."], 422);
        }

        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity'] = $quantity;
            session(['cart' => $cart]);
        }

        $subtotal  = $cart[$product->id]['price'] * $cart[$product->id]['quantity'];
        $total     = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);
        $cartCount = collect($cart)->sum('quantity');

        return response()->json([
            'subtotal'  => number_format($subtotal),
            'total'     => number_format($total),
            'cartCount' => $cartCount,
        ]);
    }

    public function remove(Product $product)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$product->id])) {
            unset($cart[$product->id]);
            session(['cart' => $cart]);
        }

        $total     = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);
        $cartCount = collect($cart)->sum('quantity');

        return response()->json([
            'total'     => number_format($total),
            'cartCount' => $cartCount,
        ]);
    }

    public function count()
    {
        $cart  = session('cart', []);
        $count = collect($cart)->sum('quantity');
        return response()->json(['count' => $count]);
    }
}
