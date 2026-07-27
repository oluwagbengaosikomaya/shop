<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::with(['items.product'])
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->search, fn($q) => $q->where('customer_email', 'like', "%{$request->search}%")
                                                  ->orWhere('customer_name', 'like', "%{$request->search}%"))
            ->latest()
            ->paginate(15);

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['items.product']);
        return view('admin.orders.show', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,completed,cancelled',
        ]);

        $order->update(['status' => $request->status]);
        return redirect()->back()->with('success', 'Order status updated!');
    }

    public function destroy(Order $order)
    {
        $order->items()->delete();
        $order->delete();
        return redirect()->route('admin.orders.index')->with('success', 'Order deleted successfully!');
    }

    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'order_ids'  => 'required|array',
            'order_ids.*'=> 'exists:orders,id',
            'status'     => 'required|in:pending,processing,shipped,completed,cancelled',
        ]);

        Order::whereIn('id', $request->order_ids)->update(['status' => $request->status]);

        return back()->with('success', count($request->order_ids) . ' orders updated to ' . $request->status . '.');
    }

    public function export(): StreamedResponse
    {
        $orders = Order::with('items')->latest()->get();

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="orders_' . now()->format('Y-m-d') . '.csv"',
        ];

        return response()->stream(function () use ($orders) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, ['Order ID', 'Customer', 'Email', 'Phone', 'Address', 'City', 'State', 'Total', 'Status', 'Date']);

            foreach ($orders as $order) {
                fputcsv($handle, [
                    '#' . $order->id,
                    $order->customer_name,
                    $order->customer_email,
                    $order->customer_phone,
                    $order->delivery_address,
                    $order->delivery_city,
                    $order->delivery_state,
                    $order->total,
                    $order->status,
                    $order->created_at->format('Y-m-d H:i'),
                ]);
            }

            fclose($handle);
        }, 200, $headers);
    }
}
