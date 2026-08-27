<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Mail\OrderPlaced;
use App\Support\DeliveryFee;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('shop.index')->with('info', 'Your cart is empty.');
        }

        // Re-validate stock on checkout page load
        $stockErrors = [];
        foreach ($cart as $id => $item) {
            $product = Product::find($id);
            if (!$product || $product->isOutOfStock()) {
                $stockErrors[] = ($item['name'] ?? 'A product') . ' is no longer available.';
                unset($cart[$id]);
            } elseif ($item['quantity'] > $product->stock) {
                $cart[$id]['quantity'] = $product->stock;
                $cart[$id]['stock']    = $product->stock;
                $stockErrors[] = ($item['name'] ?? 'A product') . ' quantity adjusted to ' . $product->stock . ' (available stock).';
            }
        }

        if (!empty($stockErrors)) {
            session()->put('cart', $cart);
        }

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'All items in your cart are out of stock.');
        }

        $subtotal    = collect($cart)->sum(fn($i) => $i['price'] * $i['quantity']);
        $coupon      = session('coupon');
        $discount    = $coupon['discount'] ?? 0;
        $deliveryFee = 0; // updated via JS when state is selected
        $total       = max(0, $subtotal - $discount + $deliveryFee);
        $feesMap     = DeliveryFee::all();

        return view('checkout.index', compact('cart', 'subtotal', 'total', 'discount', 'coupon', 'deliveryFee', 'feesMap', 'stockErrors'));
    }

    public function verifyPayment(Request $request)
    {
        $reference = $request->query('reference');

        if (!$reference) {
            return redirect()->route('shop.index')->with('error', 'Payment reference missing.');
        }

        // Prevent duplicate processing
        $existing = Order::where('payment_reference', $reference)->first();
        if ($existing) {
            return redirect()->route('order.confirmation', $existing->id);
        }

        // Verify with Paystack
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . config('services.paystack.secret'),
        ])->get("https://api.paystack.co/transaction/verify/{$reference}");

        if (!$response->successful()) {
            return redirect()->route('shop.index')->with('error', 'Could not reach payment gateway. Please contact support.');
        }

        $data = $response->json();

        if (empty($data['status']) || ($data['data']['status'] ?? '') !== 'success') {
            return redirect()->route('cart.index')->with('error', 'Payment was not successful. Please try again.');
        }

        // Extract metadata first — used as fallback if session is gone (e.g. on cloud/multi-server)
        $meta          = collect($data['data']['metadata']['custom_fields'] ?? []);
        $customerName  = $meta->firstWhere('variable_name', 'name')['value']    ?? auth()->user()?->name  ?? 'Customer';
        $customerPhone = $meta->firstWhere('variable_name', 'phone')['value']   ?? auth()->user()?->phone ?? null;
        $address       = $meta->firstWhere('variable_name', 'address')['value'] ?? null;
        $city          = $meta->firstWhere('variable_name', 'city')['value']    ?? null;
        $state         = $meta->firstWhere('variable_name', 'state')['value']   ?? null;
        $customerEmail = $data['data']['customer']['email'] ?? auth()->user()?->email;
        $userId        = $meta->firstWhere('variable_name', 'user_id')['value'] ?? auth()->id();
        $couponCode    = $meta->firstWhere('variable_name', 'coupon_code')['value'] ?? session('coupon.code');
        $discount      = (int) ($meta->firstWhere('variable_name', 'discount')['value'] ?? session('coupon.discount', 0));
        $cart = session('cart', []);

        // If webhook already created the order (race condition), wait briefly and check again
        if (empty($cart)) {
            sleep(2);
            $existing = Order::where('payment_reference', $reference)->first();
            if ($existing) {
                session()->forget(['cart', 'coupon']);
                return redirect()->route('order.confirmation', $existing->id);
            }
            return redirect()->route('shop.index')->with('error', 'Your session expired. Please contact support with reference: ' . $reference);
        }

        try {
            $order = DB::transaction(function () use ($cart, $customerName, $customerEmail, $customerPhone, $address, $city, $state, $reference, $userId, $discount) {

                // Final stock check inside transaction
                foreach ($cart as $item) {
                    if (!isset($item['product_id'])) continue;
                    $product = Product::lockForUpdate()->find($item['product_id']);
                    if (!$product || $product->stock < ($item['quantity'] ?? 1)) {
                        throw new \Exception('Stock unavailable for: ' . ($item['name'] ?? 'a product') . '. Please update your cart.');
                    }
                }

                $subtotal    = collect($cart)->sum(fn($i) => ($i['price'] ?? 0) * ($i['quantity'] ?? 1));
                $deliveryFee = $state ? DeliveryFee::for($state) : 0;
                $finalTotal  = max(0, $subtotal - $discount + $deliveryFee);

                $order = Order::create([
                    'user_id'           => $userId ?: null,
                    'customer_name'     => $customerName,
                    'customer_email'    => $customerEmail,
                    'customer_phone'    => $customerPhone,
                    'delivery_address'  => $address,
                    'delivery_city'     => $city,
                    'delivery_state'    => $state,
                    'total'             => $finalTotal,
                    'delivery_fee'      => $deliveryFee,
                    'status'            => 'pending',
                    'payment_reference' => $reference,
                ]);

                foreach ($cart as $item) {
                    if (!isset($item['product_id'])) continue;

                    OrderItem::create([
                        'order_id'     => $order->id,
                        'product_id'   => $item['product_id'],
                        'product_name' => $item['name']     ?? 'Unknown Product',
                        'quantity'     => $item['quantity'] ?? 1,
                        'price'        => $item['price']    ?? 0,
                    ]);

                    Product::where('id', $item['product_id'])
                        ->decrement('stock', $item['quantity'] ?? 1);
                }

                return $order;
            });
        } catch (\Exception $e) {
            Log::error('Order creation failed: ' . $e->getMessage());
            return redirect()->route('cart.index')->with('error', $e->getMessage());
        }

        // Save phone to user profile if provided and not already set
        if (auth()->check() && $customerPhone && !auth()->user()->phone) {
            auth()->user()->update(['phone' => $customerPhone]);
        }

        // Send confirmation email
        try {
            Mail::to($order->customer_email)->send(new OrderPlaced($order->load('items')));
        } catch (\Exception $e) {
            Log::error('Order email failed: ' . $e->getMessage());
        }

        // Increment coupon usage
        if ($couponCode) {
            \App\Models\Coupon::where('code', $couponCode)->increment('used_count');
        }

        session()->forget(['cart', 'coupon']);

        return redirect()->route('order.confirmation', $order->id);
    }

    public function confirmation($id)
    {
        $order = Order::with('items')->findOrFail($id);

        if ($order->user_id && auth()->id() !== $order->user_id) {
            abort(403);
        }

        return view('checkout.confirmation', compact('order'));
    }
}
