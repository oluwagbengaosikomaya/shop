<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderPlaced;

class CheckoutController extends Controller
{
    public function index()
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Please login to checkout.');
        }

        $cart = session()->get('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('shop.index')->with('info', 'Your cart is empty.');
        }
        
        return view('checkout.index', compact('cart'));
    }

    /**
     * Verify payment and create order
     */
public function verifyPayment(Request $request)
{
    $reference = $request->query('reference');

    if (!$reference) {
        return redirect()->route('shop.index')->with('error', 'Payment reference missing.');
    }

    // 1️⃣ Verify payment via gateway
    $response = Http::withHeaders([
        'Authorization' => 'Bearer ' . config('services.paystack.secret'),
    ])->get("https://api.paystack.co/transaction/verify/{$reference}");

    $data = $response->json();

    if (!$data['status'] || $data['data']['status'] !== 'success') {
        return redirect()->route('shop.index')->with('error', 'Payment verification failed.');
    }

    // 2️⃣ Get cart from session
    $cart = session('cart', []);

    if (empty($cart)) {
        return redirect()->route('shop.index')->with('error', 'Cart is empty.');
    }

    // 3️⃣ Extract customer info from metadata
    $metadata = $data['data']['metadata']['custom_fields'] ?? [];
    $customerName = collect($metadata)->firstWhere('variable_name', 'name')['value'] ?? 'Customer';
    $customerPhone = collect($metadata)->firstWhere('variable_name', 'phone')['value'] ?? null;

    // 4️⃣ Save Order
    $order = Order::create([
        'user_id'        => auth()->id(),
        'customer_name'  => $customerName,
        'customer_email' => $data['data']['customer']['email'] ?? 'noemail@example.com',
        'customer_phone' => $customerPhone,
        'total'          => collect($cart)->sum(fn($i) => ($i['price'] ?? 0) * ($i['quantity'] ?? 1)),
        'status'         => 'pending',
    ]);

    // 5️⃣ Save Order Items
    foreach ($cart as $item) {
        if (!isset($item['product_id'])) {
            continue;
        }

        OrderItem::create([
            'order_id'     => $order->id,
            'product_id'   => $item['product_id'],
            'product_name' => $item['name'] ?? 'Unknown Product',
            'quantity'     => $item['quantity'] ?? 1,
            'price'        => $item['price'] ?? 0,
        ]);
    }

    // 6️⃣ Send confirmation email
    try {
        Mail::to($order->customer_email)->send(new OrderPlaced($order));
    } catch (\Exception $e) {
        \Log::error('Order email failed: ' . $e->getMessage());
    }

    // 7️⃣ Clear cart
    session()->forget('cart');

    return redirect()->route('shop.index')->with('success', "Thank you! Your order #​{$order->id} has been placed successfully. Check your email for confirmation.");
}

}
