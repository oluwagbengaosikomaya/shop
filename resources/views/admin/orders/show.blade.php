@extends('admin.layouts.app')

@section('content')
<h4 class="mb-3">Order #{{ $order->id }}</h4>

<div class="card mb-4">
    <div class="card-body">
        <p><strong>Name:</strong> {{ $order->customer_name }}</p>
        <p><strong>Email:</strong> {{ $order->customer_email }}</p>
        <p><strong>Total:</strong> ₦{{ number_format($order->total) }}</p>
    </div>
</div>

<h5>Items</h5>
<table class="table table-bordered bg-white mb-4">
    <thead>
        <tr>
            <th>Product</th>
            <th>Qty</th>
            <th>Price</th>
        </tr>
    </thead>
    <tbody>
        @foreach($order->items as $item)
        <tr>
            <td>{{ $item->product_name }}</td>
            <td>{{ $item->quantity }}</td>
            <td>₦{{ number_format($item->price) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<form method="POST" action="{{ route('admin.orders.update', $order) }}" class="d-flex gap-2">
    @csrf
    @method('PUT')

    <select name="status" class="form-select w-auto">
        <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
        <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Completed</option>
    </select>

    <button class="btn btn-success">Update</button>
</form>

<form method="POST" action="{{ route('admin.orders.destroy', $order) }}" class="mt-3">
    @csrf
    @method('DELETE')
    <button class="btn btn-danger"
        onclick="return confirm('Delete this order?')">
        Delete Order
    </button>
</form>
@endsection
