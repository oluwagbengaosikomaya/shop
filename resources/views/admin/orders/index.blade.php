@extends('admin.layouts.app')

@section('title', 'Orders')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">Orders</h4>
    <a href="{{ route('admin.orders.export') }}" class="btn btn-outline-success btn-sm">
        <i class="fa fa-download me-1"></i> Export CSV
    </a>
</div>

{{-- Filters --}}
<div class="card shadow-sm border-0 p-3 mb-4">
    <form method="GET" class="row g-2 align-items-end">
        <div class="col-md-4">
            <input type="text" name="search" class="form-control" placeholder="Search by name or email..." value="{{ request('search') }}">
        </div>
        <div class="col-md-3">
            <select name="status" class="form-select">
                <option value="">All Statuses</option>
                @foreach(['pending','processing','shipped','completed','cancelled'] as $s)
                    <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-secondary w-100">Filter</button>
        </div>
        <div class="col-md-2">
            <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary w-100">Clear</a>
        </div>
    </form>
</div>

{{-- Bulk Update --}}
<form method="POST" action="{{ route('admin.orders.bulk-update') }}" id="bulk-form">
    @csrf
    <div class="d-flex align-items-center gap-2 mb-3">
        <select name="status" class="form-select w-auto">
            @foreach(['pending','processing','shipped','completed','cancelled'] as $s)
                <option value="{{ $s }}">{{ ucfirst($s) }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn btn-primary btn-sm"
                onclick="return document.querySelectorAll('.order-check:checked').length > 0 || (alert('Select at least one order.') && false)">
            Bulk Update
        </button>
        <span class="text-muted small" id="selected-count">0 selected</span>
    </div>

    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th><input type="checkbox" id="check-all"></th>
                        <th>Order</th>
                        <th>Customer</th>
                        <th>Email</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr>
                        <td><input type="checkbox" name="order_ids[]" value="{{ $order->id }}" class="order-check"></td>
                        <td class="fw-semibold">#{{ $order->id }}</td>
                        <td>{{ $order->customer_name }}</td>
                        <td class="text-muted small">{{ $order->customer_email }}</td>
                        <td>₦{{ number_format($order->total) }}</td>
                        <td><span class="badge bg-{{ $order->statusColor() }}">{{ ucfirst($order->status) }}</span></td>
                        <td class="text-muted small">{{ $order->created_at->format('d M Y') }}</td>
                        <td>
                            <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-outline-primary">View</a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center text-muted py-4">No orders found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</form>

<div class="d-flex justify-content-center mt-4">
    {{ $orders->links() }}
</div>
@endsection

@section('scripts')
<script>
document.getElementById('check-all').addEventListener('change', function () {
    document.querySelectorAll('.order-check').forEach(c => c.checked = this.checked);
    updateCount();
});
document.querySelectorAll('.order-check').forEach(c => c.addEventListener('change', updateCount));
function updateCount() {
    const n = document.querySelectorAll('.order-check:checked').length;
    document.getElementById('selected-count').textContent = n + ' selected';
}
</script>
@endsection
