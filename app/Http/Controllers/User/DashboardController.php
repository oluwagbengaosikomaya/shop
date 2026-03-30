<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
}
