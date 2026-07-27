@extends('admin.layouts.app')

@section('title', 'Order #' . $order->id)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0">Order #{{ $order->id }}</h4>
        <small class="text-muted">{{ $order->created_at->format('d M Y, h:i A') }}</small>
    </div>
    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary btn-sm">← Back</a>
</div>

<div class="row g-3">
    {{-- Items --}}
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h6 class="fw-bold mb-3"><i class="fa fa-box me-2 text-primary"></i>Order Items</h6>
                <table class="table mb-0">
                    <thead class="table-light">
                        <tr><th>Product</th><th>Qty</th><th>Unit Price</th><th>Subtotal</th></tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                        <tr>
                            <td>{{ $item->product_name }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>₦{{ number_format($item->price) }}</td>
                            <td>₦{{ number_format($item->price * $item->quantity) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="fw-bold">
                            <td colspan="3" class="text-end">Total</td>
                            <td>₦{{ number_format($order->total) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    {{-- Details sidebar --}}
    <div class="col-md-4">
        <div class="card shadow-sm border-0 mb-3">
            <div class="card-body">
                <h6 class="fw-bold mb-3"><i class="fa fa-user me-2 text-warning"></i>Customer</h6>
                <p class="mb-1"><strong>{{ $order->customer_name }}</strong></p>
                <p class="mb-1 text-muted small">{{ $order->customer_email }}</p>
                @if($order->customer_phone)
                <p class="mb-1 text-muted small">{{ $order->customer_phone }}</p>
                @endif
            </div>
        </div>

        @if($order->delivery_address)
        <div class="card shadow-sm border-0 mb-3">
            <div class="card-body">
                <h6 class="fw-bold mb-3"><i class="fa fa-map-marker-alt me-2 text-danger"></i>Delivery Address</h6>
                <p class="mb-0 small">{{ $order->delivery_address }}</p>
                @if($order->delivery_city || $order->delivery_state)
                <p class="mb-0 small text-muted">{{ implode(', ', array_filter([$order->delivery_city, $order->delivery_state])) }}</p>
                @endif
            </div>
        </div>
        @endif

        @if($order->payment_reference)
        <div class="card shadow-sm border-0 mb-3">
            <div class="card-body">
                <h6 class="fw-bold mb-2"><i class="fa fa-credit-card me-2 text-success"></i>Payment</h6>
                <p class="mb-0 small text-muted font-monospace">{{ $order->payment_reference }}</p>
            </div>
        </div>
        @endif

        {{-- Update Status --}}
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h6 class="fw-bold mb-3"><i class="fa fa-tag me-2"></i>Update Status</h6>
                <form method="POST" action="{{ route('admin.orders.update', $order) }}">
                    @csrf @method('PUT')
                    <select name="status" class="form-select mb-2">
                        @foreach(['pending','processing','shipped','completed','cancelled'] as $s)
                        <option value="{{ $s }}" {{ $order->status === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                    <button class="btn btn-success w-100">Update Status</button>
                </form>
                <form method="POST" action="{{ route('admin.orders.destroy', $order) }}" class="mt-2">
                    @csrf @method('DELETE')
                    <button class="btn btn-outline-danger w-100 btn-sm" onclick="return confirm('Delete this order?')">
                        <i class="fa fa-trash me-1"></i> Delete Order
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
