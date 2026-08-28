@extends('admin.layouts.app')

@section('title', 'Products')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">Products</h4>
    <a href="{{ route('admin.products.create') }}" class="btn btn-danger btn-sm">
        <i class="fa fa-plus me-1"></i> Add Product
    </a>
</div>

<livewire:admin-product-filter />
@endsection
