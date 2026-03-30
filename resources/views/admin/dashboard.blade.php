@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">

    <h1 class="mb-4">Admin Dashboard</h1>

    {{-- STATS --}}
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card p-3 shadow-sm">
                <h5>Total Products</h5>
                <h2>{{ $totalProducts }}</h2>
                <a href="{{ route('admin.products.index') }}">Manage Products →</a>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card p-3 shadow-sm">
                <h5>Total Orders</h5>
                <h2>{{ $totalOrders }}</h2>
                <a href="{{ route('admin.orders.index') }}">View Orders →</a>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card p-3 shadow-sm bg-success text-white">
                <h5>Total Revenue</h5>
                <h2>₦{{ number_format($totalRevenue) }}</h2>
                <small>Completed orders</small>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card p-3 shadow-sm bg-warning">
                <h5>Pending Orders</h5>
                <h2>{{ $pendingOrders }}</h2>
                <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}">View →</a>
            </div>
        </div>
    </div>

    {{-- RECENT PRODUCTS --}}
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card p-3 shadow-sm">
                <h4>Recent Products</h4>

                <table class="table table-bordered mt-3">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Created</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                            <tr>
                                <td>{{ $product->name }}</td>
                                <td>₦{{ number_format($product->price) }}</td>
<td>
    {{ $product->created_at ? $product->created_at->diffForHumans() : '—' }}
</td>                            </tr>
                        @empty
                            <tr>
                                <td colspan="3">No products found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- RECENT ORDERS --}}
    <div class="row">
        <div class="col-md-12">
            <div class="card p-3 shadow-sm">
                <h4>Recent Orders</h4>

                <table class="table table-striped mt-3">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                            <tr>
                                <td>#{{ $order->id }}</td>
                                <td>{{ $order->customer_name }}</td>
                                <td>₦{{ number_format($order->total) }}</td>
                                <td>
                                    <span class="badge bg-success">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td>{{ $order->created_at->diffForHumans() }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">No orders found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection
