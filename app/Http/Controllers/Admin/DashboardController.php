<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Order;

class DashboardController extends Controller
{
    public function index()
    {
        $totalRevenue = Order::where('status', 'completed')->sum('total');
        $pendingOrders = Order::where('status', 'pending')->count();
        
        return view('admin.dashboard', [
            'totalProducts' => Product::count(),
            'totalOrders'   => Order::count(),
            'totalRevenue'  => $totalRevenue,
            'pendingOrders' => $pendingOrders,
            'products'      => Product::latest()->take(5)->get(),
            'orders'        => Order::latest()->take(5)->get(),
        ]);
    }
}
