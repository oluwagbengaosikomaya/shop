@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-0">Order #{{ $order->id }}</h4>
            <small class="text-muted">Placed {{ $order->created_at->format('d M Y, h:i A') }}</small>
        </div>
        <a href="{{ route('user.dashboard') }}" class="btn btn-outline-secondary btn-sm">← My Orders</a>
    </div>

    {{-- Status Timeline --}}
    @php
        $steps = ['pending','processing','shipped','completed'];
        $currentIndex = array_search($order->status, $steps);
        $cancelled = $order->status === 'cancelled';
    @endphp
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            @if($cancelled)
                <div class="alert alert-danger mb-0"><i class="fa fa-times-circle me-2"></i>This order was cancelled.</div>
            @else
            <div class="d-flex justify-content-between align-items-center position-relative">
                <div class="position-absolute top-50 start-0 end-0 translate-middle-y" style="height:2px;background:#dee2e6;z-index:0;"></div>
                @foreach($steps as $i => $step)
                @php $done = $currentIndex !== false && $i <= $currentIndex; @endphp
                <div class="text-center position-relative" style="z-index:1;flex:1;">
                    <div class="mx-auto rounded-circle d-flex align-items-center justify-content-center mb-1"
                         style="width:32px;height:32px;background:{{ $done ? '#198754' : '#dee2e6' }};">
                        <i class="fa fa-check text-white small"></i>
                    </div>
                    <small class="{{ $done ? 'text-success fw-semibold' : 'text-muted' }}">{{ ucfirst($step) }}</small>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>

    <div class="row g-3">
        {{-- Items --}}
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">Order Items</h6>
                    <table class="table mb-0">
                        <thead class="table-light">
                            <tr><th>Product</th><th>Price</th><th>Qty</th><th>Subtotal</th></tr>
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
                            <tr class="fw-bold">
                                <td colspan="3" class="text-end">Total</td>
                                <td>₦{{ number_format($order->total) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="col-md-4">
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">Order Info</h6>
                    <p class="mb-1 small"><strong>Status:</strong>
                        <span class="badge bg-{{ $order->statusColor() }}">{{ ucfirst($order->status) }}</span>
                    </p>
                    <p class="mb-1 small"><strong>Name:</strong> {{ $order->customer_name }}</p>
                    <p class="mb-1 small"><strong>Email:</strong> {{ $order->customer_email }}</p>
                    @if($order->customer_phone)
                    <p class="mb-1 small"><strong>Phone:</strong> {{ $order->customer_phone }}</p>
                    @endif
                </div>
            </div>

            @if($order->delivery_address)
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-body">
                    <h6 class="fw-bold mb-2"><i class="fa fa-map-marker-alt me-2 text-danger"></i>Delivery Address</h6>
                    <p class="mb-0 small">{{ $order->delivery_address }}</p>
                    @if($order->delivery_city || $order->delivery_state)
                    <p class="mb-0 small text-muted">{{ implode(', ', array_filter([$order->delivery_city, $order->delivery_state])) }}</p>
                    @endif
                </div>
            </div>
            @endif

            <div class="d-grid gap-2">
                @if($order->isCancellable())
                <form method="POST" action="{{ route('user.orders.cancel', $order->id) }}">
                    @csrf
                    <button class="btn btn-outline-danger w-100" onclick="return confirm('Cancel this order?')">
                        <i class="fa fa-times me-1"></i> Cancel Order
                    </button>
                </form>
                @endif
                @if($order->status === 'completed')
                <form method="POST" action="{{ route('user.orders.reorder', $order->id) }}">
                    @csrf
                    <button class="btn btn-success w-100">
                        <i class="fa fa-redo me-1"></i> Reorder
                    </button>
                </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
