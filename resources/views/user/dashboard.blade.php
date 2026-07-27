@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">My Orders</h4>
        <a href="{{ route('shop.index') }}" class="btn btn-danger btn-sm"><i class="fa fa-shopping-bag me-1"></i> Shop More</a>
    </div>

    @if($orders->isEmpty())
        <div class="text-center py-5">
            <i class="fa fa-box-open fa-3x text-muted mb-3"></i>
            <p class="text-muted">You haven't placed any orders yet.</p>
            <a href="{{ route('shop.index') }}" class="btn btn-danger">Start Shopping</a>
        </div>
    @else
        <div class="card shadow-sm border-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Order #</th>
                            <th>Date</th>
                            <th>Items</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        <tr>
                            <td class="fw-semibold">#{{ $order->id }}</td>
                            <td class="text-muted small">{{ $order->created_at->format('d M Y') }}</td>
                            <td>{{ $order->items->count() }} item(s)</td>
                            <td>₦{{ number_format($order->total) }}</td>
                            <td>
                                <span class="badge bg-{{ $order->statusColor() }}">{{ ucfirst($order->status) }}</span>
                            </td>
                            <td>
                                <div class="d-flex gap-1 flex-wrap">
                                    <a href="{{ route('user.orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary">View</a>
                                    @if($order->isCancellable())
                                    <form method="POST" action="{{ route('user.orders.cancel', $order->id) }}">
                                        @csrf
                                        <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Cancel this order?')">Cancel</button>
                                    </form>
                                    @endif
                                    @if($order->status === 'completed')
                                    <form method="POST" action="{{ route('user.orders.reorder', $order->id) }}">
                                        @csrf
                                        <button class="btn btn-sm btn-outline-success">Reorder</button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $orders->links() }}
        </div>
    @endif
</div>
@endsection
