@extends('layouts.app')

@section('content')
<div class="container py-3 py-md-5">
    <h2>Your Cart</h2>

    <div id="cart-container">
        @if(!$cart)
            <p>Your cart is empty!</p>
        @else
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Subtotal</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cart as $id => $item)
                    <tr data-id="{{ $id }}">
                        <td>
                            <img src="{{ asset($item['image']) }}" width="50" alt="{{ $item['name'] }}" class="img-fluid">
                        </td>
                        <td class="small">{{ $item['name'] }}</td>
                        <td class="small">₦{{ number_format($item['price']) }}</td>
                        <td>
                            <div class="input-group" style="max-width: 130px;">
                                <button class="btn btn-outline-secondary btn-sm decrease-qty" type="button">-</button>
                                <input type="number" class="form-control form-control-sm text-center quantity" value="{{ $item['quantity'] }}" min="1" style="max-width: 50px;">
                                <button class="btn btn-outline-secondary btn-sm increase-qty" type="button">+</button>
                            </div>
                        </td>
                        <td class="subtotal small">₦{{ number_format($item['price'] * $item['quantity']) }}</td>
                        <td>
                            <button class="btn btn-danger btn-sm remove-item">Remove</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <h4 class="text-end">Total: ₦<span id="cart-total">{{ number_format($total) }}</span></h4>
        @endif
    </div>

@if($cart)
    <div class="mt-3 d-grid d-md-block">
        <a href="{{ route('checkout.index') }}" class="btn btn-success btn-lg">
            Proceed to Checkout
        </a>
    </div>
@endif
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function(){

    $('.increase-qty').click(function(){
        let input = $(this).siblings('.quantity');
        let newQty = parseInt(input.val()) + 1;
        input.val(newQty).trigger('change');
    });

    $('.decrease-qty').click(function(){
        let input = $(this).siblings('.quantity');
        let newQty = Math.max(1, parseInt(input.val()) - 1);
        input.val(newQty).trigger('change');
    });

    $('.quantity').on('change', function(){
        let row = $(this).closest('tr');
        let productId = row.data('id');
        let qty = $(this).val();

        $.post('/cart/update/' + productId, {
            _token: '{{ csrf_token() }}',
            quantity: qty
        }, function(response){
            row.find('.subtotal').text('₦' + response.subtotal);
            $('#cart-total').text(response.total);
            let badge = $('.fa-shopping-cart').siblings('span.badge');
            if(badge.length === 0 && response.cartCount > 0){
                $('.fa-shopping-cart').after(
                    `<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">${response.cartCount}</span>`
                );
            } else {
                badge.text(response.cartCount);
                if(response.cartCount == 0) badge.remove();
            }
        });
    });

    $('.remove-item').click(function(){
        let row = $(this).closest('tr');
        let productId = row.data('id');

        $.post('/cart/remove/' + productId, {
            _token: '{{ csrf_token() }}'
        }, function(response){
            row.remove();
            $('#cart-total').text(response.total);
            let badge = $('.fa-shopping-cart').siblings('span.badge');
            if(response.cartCount > 0){
                badge.text(response.cartCount);
            } else {
                badge.remove();
            }
            if($('tbody tr').length == 0){
                $('#cart-container').html('<p>Your cart is empty!</p>');
            }
        });
    });

});
</script>
@endsection
