@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between mb-4">
        <h2>Order #{{ $order->id }}</h2>
        <a href="{{ route('user.dashboard') }}" class="btn btn-secondary">← Back to Orders</a>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-3">
                <div class="card-header">
                    <h5>Order Items</h5>
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                            <tr>
                                <td>{{ $item->product_name }}</td>
                                <td>₦{{ number_format($item->price) }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>₦{{ number_format($item->price * $item->quantity) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3" class="text-end">Total:</th>
                                <th>₦{{ number_format($order->total) }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5>Order Details</h5>
                </div>
                <div class="card-body">
                    <p><strong>Order Date:</strong><br>{{ $order->created_at->format('d M Y, h:i A') }}</p>
                    <p><strong>Status:</strong><br>
                        <span class="badge bg-{{ $order->status === 'completed' ? 'success' : ($order->status === 'cancelled' ? 'danger' : 'warning') }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </p>
                    <hr>
                    <p><strong>Customer Name:</strong><br>{{ $order->customer_name }}</p>
                    <p><strong>Email:</strong><br>{{ $order->customer_email }}</p>
                    <p><strong>Phone:</strong><br>{{ $order->customer_phone ?? 'N/A' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
