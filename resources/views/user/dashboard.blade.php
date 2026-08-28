@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">My Orders</h4>
        <a href="{{ route('shop.index') }}" class="btn btn-danger btn-sm"><i class="fa fa-shopping-bag me-1"></i> Shop More</a>
    </div>

    <livewire:user-dashboard />
</div>
@endsection
