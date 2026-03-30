@extends('layouts.app')

@section('title', 'The Gift Shop')

@section('content')

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<section class="py-3 py-md-5 text-center container">
  <div class="row">
    <div class="col-lg-6 col-md-8 mx-auto">
      <h1 class="custom-font">Cash Shop? Sure Hassle!</h1>
      <p class="lead">Every Moments, you can still show your loved ones that you care...</p>
      <p>
        <a href="#shopnow" class="btn btn-outline-danger my-2">SHOP NOW</a>
        <a href="#" class="btn btn-outline-warning">GIFT CARD</a>
      </p>
    </div>
  </div>
</section>

<div class="album py-3 py-md-5 bg-light">
  <div class="container" id="shopnow">
    <div class="row">
      @forelse ($products as $product)
        <div class="col-6 col-md-4 col-lg-3 mb-4">
          <div class="card shadow-sm h-100">
            <img src="{{ asset($product->image) }}" class="img-fluid" alt="{{ $product->name }}" style="height: 200px; object-fit: cover;">
            <div class="card-body text-center">
              <p class="card-text small">{{ $product->name }}</p>
              <p class="fw-bold mb-2">₦{{ number_format($product->price) }}</p>
              <form class="add-to-cart-form" data-id="{{ $product->id }}">
                @csrf
                <button type="submit" class="btn btn-sm btn-danger w-100">Add to Cart</button>
              </form>
            </div>
          </div>
        </div>
      @empty
        <div class="col-12 text-center">
          <p class="lead">No products available at the moment.</p>
        </div>
      @endforelse
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('.add-to-cart-form').submit(function(e) {
        e.preventDefault();

        let productId = $(this).data('id');
        let token = $(this).find('input[name="_token"]').val();

        $.post('/cart/add/' + productId, { _token: token }, function(response) {
            $.get('/cart/count', function(data) {
                let badge = $('.fa-shopping-cart').siblings('span.badge');

                if(data.count > 0){
                    if(badge.length === 0){
                        $('.fa-shopping-cart').after(
                            `<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">${data.count}</span>`
                        );
                    } else {
                        badge.text(data.count);
                    }
                } else {
                    badge.remove();
                }
            });

            alert('Added to cart!');
        });
    });
});
</script>
@endsection
