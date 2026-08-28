@extends('admin.layouts.app')

@section('title', 'Orders')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">Orders</h4>
    <a href="{{ route('admin.orders.export') }}" class="btn btn-outline-success btn-sm">
        <i class="fa fa-download me-1"></i> Export CSV
    </a>
</div>

<livewire:admin-order-filter />
@endsection
