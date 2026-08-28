<?php

namespace App\Livewire;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Livewire\Component;

class AdminDashboard extends Component
{
    public function render()
    {
        $monthlyRevenue = Order::where('status', 'completed')
            ->where('created_at', '>=', now()->subMonths(6))
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month, SUM(total) as revenue")
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('revenue', 'month');

        $ordersByStatus = Order::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        return view('livewire.admin-dashboard', [
            'totalProducts'    => Product::count(),
            'totalOrders'      => Order::count(),
            'totalRevenue'     => Order::where('status', 'completed')->sum('total'),
            'pendingOrders'    => Order::where('status', 'pending')->count(),
            'totalUsers'       => User::where('is_admin', false)->count(),
            'orders'           => Order::latest()->take(5)->get(),
            'products'         => Product::latest()->take(5)->get(),
            'monthlyRevenue'   => $monthlyRevenue,
            'ordersByStatus'   => $ordersByStatus,
            'lowStockProducts' => Product::where('stock', '<=', 3)->where('stock', '>', 0)->get(),
            'outOfStock'       => Product::where('stock', 0)->count(),
        ]);
    }
}
