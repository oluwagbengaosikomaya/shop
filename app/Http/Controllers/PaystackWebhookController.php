<?php

namespace App\Http\Controllers;

use App\Mail\OrderPlaced;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Support\DeliveryFee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PaystackWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $secret = config('services.paystack.secret');

        // Verify signature
        $computedSignature = hash_hmac('sha512', $request->getContent(), $secret);
        if ($computedSignature !== $request->header('x-paystack-signature')) {
            return response('Unauthorized', 401);
        }

        $payload = $request->json()->all();

        if (($payload['event'] ?? '') !== 'charge.success') {
            return response('OK', 200);
        }

        $data      = $payload['data'];
        $reference = $data['reference'] ?? null;

        if (!$reference) {
            return response('OK', 200);
        }

        // Idempotency — skip if already processed
        if (Order::where('payment_reference', $reference)->exists()) {
            return response('OK', 200);
        }

        $meta          = collect($data['metadata']['custom_fields'] ?? []);
        $customerName  = $meta->firstWhere('variable_name', 'name')['value']    ?? 'Customer';
        $customerPhone = $meta->firstWhere('variable_name', 'phone')['value']   ?? null;
        $address       = $meta->firstWhere('variable_name', 'address')['value'] ?? null;
        $city          = $meta->firstWhere('variable_name', 'city')['value']    ?? null;
        $state         = $meta->firstWhere('variable_name', 'state')['value']   ?? null;
        $customerEmail = $data['customer']['email'] ?? null;
        $userId        = $meta->firstWhere('variable_name', 'user_id')['value'] ?? null;
        $cartJson      = $meta->firstWhere('variable_name', 'cart')['value']    ?? null;
        $couponCode    = $meta->firstWhere('variable_name', 'coupon_code')['value'] ?? null;
        $discount      = (int) ($meta->firstWhere('variable_name', 'discount')['value'] ?? 0);

        if (!$cartJson) {
            Log::warning("Paystack webhook: no cart in metadata for reference {$reference}");
            return response('OK', 200);
        }

        $cart = json_decode($cartJson, true);

        if (empty($cart)) {
            return response('OK', 200);
        }

        try {
            $order = DB::transaction(function () use (
                $cart, $customerName, $customerEmail, $customerPhone,
                $address, $city, $state, $reference, $userId, $discount
            ) {
                foreach ($cart as $item) {
                    if (!isset($item['product_id'])) continue;
                    $product = Product::lockForUpdate()->find($item['product_id']);
                    if (!$product || $product->stock < ($item['quantity'] ?? 1)) {
                        throw new \Exception('Stock unavailable for: ' . ($item['name'] ?? 'a product'));
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
                    Product::where('id', $item['product_id'])->decrement('stock', $item['quantity'] ?? 1);
                }

                return $order;
            });
        } catch (\Exception $e) {
            Log::error('Webhook order creation failed: ' . $e->getMessage(), ['reference' => $reference]);
            return response('OK', 200); // Always 200 to Paystack
        }

        if ($couponCode) {
            Coupon::where('code', $couponCode)->increment('used_count');
        }

        try {
            Mail::to($order->customer_email)->send(new OrderPlaced($order->load('items')));
        } catch (\Exception $e) {
            Log::error('Webhook order email failed: ' . $e->getMessage());
        }

        return response('OK', 200);
    }
}
