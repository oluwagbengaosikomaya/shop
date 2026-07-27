<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function apply(Request $request)
    {
        $code  = strtoupper(trim($request->input('code', '')));
        $cart  = session('cart', []);
        $total = collect($cart)->sum(fn($i) => $i['price'] * $i['quantity']);

        $coupon = Coupon::where('code', $code)->first();

        if (!$coupon || !$coupon->isValid($total)) {
            $reason = match(true) {
                !$coupon                                          => 'Invalid coupon code.',
                !$coupon->active                                  => 'This coupon is no longer active.',
                $coupon->expires_at && $coupon->expires_at->isPast() => 'This coupon has expired.',
                $coupon->max_uses > 0 && $coupon->used_count >= $coupon->max_uses => 'This coupon has reached its usage limit.',
                $total < $coupon->min_order                       => 'Minimum order of ₦' . number_format($coupon->min_order) . ' required.',
                default                                           => 'Coupon cannot be applied.',
            };
            return response()->json(['error' => $reason], 422);
        }

        $discount    = $coupon->discountAmount($total);
        $finalTotal  = $total - $discount;

        session(['coupon' => [
            'code'     => $coupon->code,
            'type'     => $coupon->type,
            'value'    => $coupon->value,
            'discount' => $discount,
        ]]);

        return response()->json([
            'code'        => $coupon->code,
            'discount'    => $discount,
            'final_total' => $finalTotal,
            'label'       => $coupon->type === 'percent'
                ? "{$coupon->value}% off"
                : '₦' . number_format($coupon->value) . ' off',
        ]);
    }

    public function remove()
    {
        session()->forget('coupon');
        $cart  = session('cart', []);
        $total = collect($cart)->sum(fn($i) => $i['price'] * $i['quantity']);
        return response()->json(['total' => $total]);
    }
}
