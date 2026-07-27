@extends('layouts.app')

@section('title', 'Order Confirmed — The Gift Shop')

@section('content')
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-lg-7">

      {{-- Success Banner --}}
      <div class="text-center mb-4">
        <div class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
             style="width:80px;height:80px;">
          <i class="fa fa-check fa-2x"></i>
        </div>
        <h1 class="fw-bold">Order Confirmed!</h1>
        <p class="lead text-muted">Thank you, <strong>{{ $order->customer_name }}</strong>!</p>
        <p class="text-muted small">A confirmation email has been sent to <strong>{{ $order->customer_email }}</strong></p>
      </div>

      {{-- Order Details --}}
      <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold mb-0">Order #{{ $order->id }}</h5>
            <span class="badge bg-{{ $order->statusColor() }} fs-6">{{ ucfirst($order->status) }}</span>
          </div>
          <p class="text-muted small mb-3">
            <i class="fa fa-calendar me-1"></i>{{ $order->created_at->format('d M Y, h:i A') }}
          </p>

          @foreach($order->items as $item)
            <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
              <div>
                <p class="mb-0 fw-semibold">{{ $item->product_name }}</p>
                <p class="mb-0 text-muted small">x{{ $item->quantity }} × ₦{{ number_format($item->price) }}</p>
              </div>
              <span class="fw-semibold">₦{{ number_format($item->price * $item->quantity) }}</span>
            </div>
          @endforeach

          <div class="d-flex justify-content-between fw-bold fs-5 mt-3">
            <span>Total Paid</span>
            <span class="text-danger">₦{{ number_format($order->total) }}</span>
          </div>
        </div>
      </div>

      {{-- Delivery Address --}}
      @if($order->delivery_address)
      <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
          <h6 class="fw-bold mb-2"><i class="fa fa-map-marker-alt me-2 text-danger"></i>Delivery Address</h6>
          <p class="mb-0">{{ $order->delivery_address }}</p>
          @if($order->delivery_city || $order->delivery_state)
            <p class="mb-0 text-muted small">
              {{ implode(', ', array_filter([$order->delivery_city, $order->delivery_state])) }}
            </p>
          @endif
        </div>
      </div>
      @endif

      {{-- Status Timeline --}}
      @php
        $steps        = ['pending' => 'Order Placed', 'processing' => 'Processing', 'shipped' => 'Shipped', 'completed' => 'Delivered'];
        $stepKeys     = array_keys($steps);
        $currentIndex = array_search($order->status, $stepKeys);
      @endphp
      @if($order->status !== 'cancelled')
      <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
          <h6 class="fw-bold mb-4"><i class="fa fa-truck me-2 text-danger"></i>Order Progress</h6>
          <div class="d-flex align-items-start">
            @foreach($steps as $key => $label)
              @php
                $index  = array_search($key, $stepKeys);
                $done   = $currentIndex !== false && $index < $currentIndex;
                $active = $order->status === $key;
              @endphp
              <div class="text-center flex-fill">
                <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center mb-2
                  {{ $active ? 'bg-danger text-white' : ($done ? 'bg-success text-white' : 'bg-light text-muted') }}"
                  style="width:36px;height:36px;">
                  <i class="fa fa-{{ $done ? 'check' : 'circle' }} small"></i>
                </div>
                <p class="small mb-0 {{ $active ? 'fw-bold text-danger' : ($done ? 'text-success' : 'text-muted') }}">
                  {{ $label }}
                </p>
              </div>
              @if(!$loop->last)
                <div class="flex-fill" style="margin-top:17px;">
                  <hr class="m-0 {{ $done ? 'border-success border-2' : 'border-secondary' }}">
                </div>
              @endif
            @endforeach
          </div>
        </div>
      </div>
      @endif

      <div class="d-flex gap-3 justify-content-center">
        <a href="{{ route('shop.index') }}" class="btn btn-danger btn-lg">
          <i class="fa fa-shopping-bag me-2"></i>Continue Shopping
        </a>
        @auth
          <a href="{{ route('user.dashboard') }}" class="btn btn-outline-secondary btn-lg">
            <i class="fa fa-list me-2"></i>My Orders
          </a>
        @endauth
      </div>

    </div>
  </div>
</div>
@endsection
