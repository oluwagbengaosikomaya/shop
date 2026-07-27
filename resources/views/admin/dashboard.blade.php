@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('head')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">Dashboard</h4>
    <span class="text-muted small">{{ now()->format('l, d M Y') }}</span>
</div>

{{-- Stat Cards --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card stat-card shadow-sm p-3 border-start border-primary border-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <p class="text-muted small mb-1">Total Products</p>
                    <h3 class="fw-bold mb-0">{{ $totalProducts }}</h3>
                </div>
                <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                    <i class="fa fa-box text-primary fa-lg"></i>
                </div>
            </div>
            <a href="{{ route('admin.products.index') }}" class="small text-primary mt-2 d-block">Manage →</a>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card stat-card shadow-sm p-3 border-start border-warning border-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <p class="text-muted small mb-1">Total Orders</p>
                    <h3 class="fw-bold mb-0">{{ $totalOrders }}</h3>
                </div>
                <div class="bg-warning bg-opacity-10 rounded-circle p-3">
                    <i class="fa fa-shopping-bag text-warning fa-lg"></i>
                </div>
            </div>
            <a href="{{ route('admin.orders.index') }}" class="small text-warning mt-2 d-block">View all →</a>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card stat-card shadow-sm p-3 border-start border-success border-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <p class="text-muted small mb-1">Total Revenue</p>
                    <h3 class="fw-bold mb-0">₦{{ number_format($totalRevenue) }}</h3>
                </div>
                <div class="bg-success bg-opacity-10 rounded-circle p-3">
                    <i class="fa fa-naira-sign text-success fa-lg"></i>
                </div>
            </div>
            <small class="text-muted mt-2 d-block">Completed orders only</small>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card stat-card shadow-sm p-3 border-start border-danger border-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <p class="text-muted small mb-1">Pending Orders</p>
                    <h3 class="fw-bold mb-0">{{ $pendingOrders }}</h3>
                </div>
                <div class="bg-danger bg-opacity-10 rounded-circle p-3">
                    <i class="fa fa-clock text-danger fa-lg"></i>
                </div>
            </div>
            <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" class="small text-danger mt-2 d-block">View →</a>
        </div>
    </div>
</div>

{{-- Low Stock Alert --}}
@if($lowStockProducts->isNotEmpty() || $outOfStock > 0)
<div class="alert alert-warning d-flex align-items-start gap-3 mb-4">
    <i class="fa fa-exclamation-triangle fa-lg mt-1"></i>
    <div>
        <strong>Stock Alert:</strong>
        @if($outOfStock > 0)
            <span class="badge bg-danger me-1">{{ $outOfStock }} out of stock</span>
        @endif
        @foreach($lowStockProducts as $p)
            <span class="badge bg-warning text-dark me-1">{{ $p->name }}: {{ $p->stock }} left</span>
        @endforeach
        <a href="{{ route('admin.products.index') }}" class="ms-2 small">Manage stock →</a>
    </div>
</div>
@endif

{{-- Charts Row --}}
<div class="row g-3 mb-4">
    <div class="col-md-8">
        <div class="card shadow-sm border-0 p-3">
            <h6 class="fw-bold mb-3"><i class="fa fa-chart-line me-2 text-primary"></i>Revenue (Last 6 Months)</h6>
            <canvas id="revenueChart" height="100"></canvas>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm border-0 p-3">
            <h6 class="fw-bold mb-3"><i class="fa fa-chart-pie me-2 text-warning"></i>Orders by Status</h6>
            <canvas id="statusChart" height="180"></canvas>
        </div>
    </div>
</div>

{{-- Recent Orders --}}
<div class="card shadow-sm border-0 mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="fw-bold mb-0"><i class="fa fa-shopping-bag me-2 text-warning"></i>Recent Orders</h6>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-secondary">View All</a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr><th>Order</th><th>Customer</th><th>Total</th><th>Status</th><th>Date</th><th></th></tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr>
                        <td class="fw-semibold">#{{ $order->id }}</td>
                        <td>{{ $order->customer_name }}</td>
                        <td>₦{{ number_format($order->total) }}</td>
                        <td><span class="badge bg-{{ $order->statusColor() }}">{{ ucfirst($order->status) }}</span></td>
                        <td class="text-muted small">{{ $order->created_at->diffForHumans() }}</td>
                        <td><a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-outline-primary">View</a></td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center text-muted">No orders yet</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Recent Products --}}
<div class="card shadow-sm border-0">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="fw-bold mb-0"><i class="fa fa-box me-2 text-primary"></i>Recent Products</h6>
            <a href="{{ route('admin.products.create') }}" class="btn btn-sm btn-danger">+ Add Product</a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr><th>Product</th><th>Price</th><th>Stock</th><th>Category</th><th>Added</th></tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                    <tr>
                        <td class="fw-semibold">{{ $product->name }}</td>
                        <td>₦{{ number_format($product->price) }}</td>
                        <td>
                            @if($product->isOutOfStock())
                                <span class="badge bg-danger">Out of Stock</span>
                            @elseif($product->isLowStock())
                                <span class="badge bg-warning text-dark">{{ $product->stock }} left</span>
                            @else
                                <span class="badge bg-success">{{ $product->stock }}</span>
                            @endif
                        </td>
                        <td><span class="badge bg-secondary">{{ $product->category ?? 'general' }}</span></td>
                        <td class="text-muted small">{{ $product->created_at?->diffForHumans() ?? '—' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center text-muted">No products yet</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
const revenueLabels = @json($monthlyRevenue->keys());
const revenueData   = @json($monthlyRevenue->values());

new Chart(document.getElementById('revenueChart'), {
    type: 'line',
    data: {
        labels: revenueLabels.length ? revenueLabels : ['No data'],
        datasets: [{
            label: 'Revenue (₦)',
            data: revenueData.length ? revenueData : [0],
            borderColor: '#0d6efd',
            backgroundColor: 'rgba(13,110,253,.08)',
            fill: true,
            tension: .4,
            pointRadius: 4,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true, ticks: { callback: v => '₦' + v.toLocaleString() } } }
    }
});

const statusData   = @json($ordersByStatus);
const statusLabels = Object.keys(statusData).map(s => s.charAt(0).toUpperCase() + s.slice(1));
const statusValues = Object.values(statusData);
const statusColors = { Pending: '#ffc107', Processing: '#0d6efd', Shipped: '#0dcaf0', Completed: '#198754', Cancelled: '#dc3545' };

new Chart(document.getElementById('statusChart'), {
    type: 'doughnut',
    data: {
        labels: statusLabels.length ? statusLabels : ['No orders'],
        datasets: [{
            data: statusValues.length ? statusValues : [1],
            backgroundColor: statusLabels.map(l => statusColors[l] || '#6c757d'),
            borderWidth: 2,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { position: 'bottom', labels: { boxWidth: 12 } } }
    }
});
</script>
@endsection
