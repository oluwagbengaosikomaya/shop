<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;

class DashboardController extends Controller
{
    public function index()
    {
        $orders = auth()->user()->orders()->with('items.product')->latest()->paginate(10);
        return view('user.dashboard', compact('orders'));
    }

    public function show($id)
    {
        $order = auth()->user()->orders()->with('items.product')->findOrFail($id);
        return view('user.order-details', compact('order'));
    }

    public function cancel($id)
    {
        $order = auth()->user()->orders()->findOrFail($id);

        if (!$order->isCancellable()) {
            return back()->with('error', 'This order can no longer be cancelled.');
        }

        // Restore stock
        foreach ($order->items as $item) {
            Product::where('id', $item->product_id)->increment('stock', $item->quantity);
        }

        $order->update(['status' => 'cancelled']);

        return back()->with('success', "Order #{$order->id} has been cancelled.");
    }

    public function reorder($id)
    {
        $order = auth()->user()->orders()->with('items.product')->findOrFail($id);
        $cart  = session()->get('cart', []);
        $skipped = [];

        foreach ($order->items as $item) {
            $product = $item->product;

            if (!$product || $product->isOutOfStock()) {
                $skipped[] = $item->product_name;
                continue;
            }

            $current  = $cart[$product->id]['quantity'] ?? 0;
            $addQty   = min($item->quantity, $product->stock - $current);

            if ($addQty <= 0) {
                $skipped[] = $item->product_name;
                continue;
            }

            $cart[$product->id] = [
                'product_id' => $product->id,
                'name'       => $product->name,
                'price'      => $product->price,
                'quantity'   => $current + $addQty,
                'image'      => $product->image ?: 'assets/images/no-image.png',
                'stock'      => $product->stock,
            ];
        }

        session()->put('cart', $cart);

        $msg = 'Items added to cart!';
        if (!empty($skipped)) {
            $msg .= ' Some items were skipped (out of stock): ' . implode(', ', $skipped);
        }

        return redirect()->route('cart.index')->with('success', $msg);
    }
}
