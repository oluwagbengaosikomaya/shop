<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalRevenue  = Order::where('status', 'completed')->sum('total');
        $pendingOrders = Order::where('status', 'pending')->count();
        $totalUsers    = User::where('is_admin', false)->count();

        // Monthly revenue for chart (last 6 months)
        $monthlyRevenue = Order::where('status', 'completed')
            ->where('created_at', '>=', now()->subMonths(6))
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month, SUM(total) as revenue")
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('revenue', 'month');

        // Order status breakdown for pie chart
        $ordersByStatus = Order::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        // Low stock products (stock <= 3)
        $lowStockProducts = Product::where('stock', '<=', 3)->where('stock', '>', 0)->get();
        $outOfStock       = Product::where('stock', 0)->count();

        return view('admin.dashboard', [
            'totalProducts'    => Product::count(),
            'totalOrders'      => Order::count(),
            'totalRevenue'     => $totalRevenue,
            'pendingOrders'    => $pendingOrders,
            'totalUsers'       => $totalUsers,
            'products'         => Product::latest()->take(5)->get(),
            'orders'           => Order::latest()->take(5)->get(),
            'monthlyRevenue'   => $monthlyRevenue,
            'ordersByStatus'   => $ordersByStatus,
            'lowStockProducts' => $lowStockProducts,
            'outOfStock'       => $outOfStock,
        ]);
    }
}
