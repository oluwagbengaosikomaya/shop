<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function index()
    {
        $coupons = Coupon::latest()->paginate(15);
        return view('admin.coupons.index', compact('coupons'));
    }

    public function create()
    {
        return view('admin.coupons.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code'      => 'required|string|max:50|unique:coupons,code',
            'type'      => 'required|in:percent,fixed',
            'value'     => 'required|numeric|min:1',
            'min_order' => 'nullable|numeric|min:0',
            'max_uses'  => 'nullable|integer|min:0',
            'expires_at'=> 'nullable|date|after:today',
        ]);

        Coupon::create([
            'code'       => strtoupper($request->code),
            'type'       => $request->type,
            'value'      => $request->value,
            'min_order'  => $request->min_order  ?? 0,
            'max_uses'   => $request->max_uses   ?? 0,
            'expires_at' => $request->expires_at ?: null,
            'active'     => true,
            'used_count' => 0,
        ]);

        return redirect()->route('admin.coupons.index')->with('success', 'Coupon created!');
    }

    public function toggle(Coupon $coupon)
    {
        $coupon->update(['active' => !$coupon->active]);
        $state = $coupon->active ? 'activated' : 'deactivated';
        return back()->with('success', "Coupon {$state}.");
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();
        return back()->with('success', 'Coupon deleted.');
    }
}
