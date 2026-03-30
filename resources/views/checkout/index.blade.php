@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<div class="container py-5">
    <h1 class="mb-4">Checkout</h1>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(empty($cart))
        <div class="alert alert-info">
            <p>Your cart is empty. <a href="{{ route('shop.index') }}" class="alert-link">Go shopping</a></p>
        </div>
    @else
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5>Order Summary</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Qty</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $total = 0; @endphp
                                @foreach($cart as $item)
                                    @php $subtotal = $item['price'] * $item['quantity']; $total += $subtotal; @endphp
                                    <tr>
                                        <td>
                                            <img src="{{ asset($item['image']) }}" width="40" class="me-2">
                                            {{ $item['name'] }}
                                        </td>
                                        <td>₦{{ number_format($item['price']) }}</td>
                                        <td>{{ $item['quantity'] }}</td>
                                        <td>₦{{ number_format($subtotal) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="3" class="text-end">Total:</th>
                                    <th>₦{{ number_format($total) }}</th>
                                </tr>
                            </tfoot>
                        </table>
                        <a href="{{ route('cart.index') }}" class="btn btn-outline-secondary">← Back to Cart</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5>Customer Information</h5>
                    </div>
                    <div class="card-body">
                        <form id="checkout-form">
                            @csrf
                            <div class="mb-3">
                                <label for="customer_name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text" id="customer_name" class="form-control" placeholder="John Doe" required>
                            </div>
                            <div class="mb-3">
                                <label for="customer_email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" id="customer_email" class="form-control" placeholder="john@example.com" required>
                            </div>
                            <div class="mb-3">
                                <label for="customer_phone" class="form-label">Phone Number</label>
                                <input type="tel" id="customer_phone" class="form-control" placeholder="08012345678">
                            </div>

                            <div class="d-grid">
                                <button type="button" class="btn btn-success btn-lg" onclick="payWithPaystack()" id="pay-btn">
                                    <i class="fa fa-lock"></i> Pay ₦{{ number_format($total) }}
                                </button>
                            </div>
                            <small class="text-muted d-block mt-2 text-center">Secure payment powered by Paystack</small>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<script src="https://js.paystack.co/v1/inline.js"></script>
<script>
function payWithPaystack() {
    let name = document.getElementById('customer_name').value.trim();
    let email = document.getElementById('customer_email').value.trim();
    let phone = document.getElementById('customer_phone').value.trim();
    let btn = document.getElementById('pay-btn');

    if (!name || !email) {
        alert('Please fill in your name and email.');
        return;
    }

    // Email validation
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        alert('Please enter a valid email address.');
        return;
    }

    // Disable button
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Processing...';

    let total = {{ $total * 100 }}; // convert to Kobo

    let handler = PaystackPop.setup({
        key: '{{ config("services.paystack.key") }}',
        email: email,
        amount: total,
        currency: "NGN",
        ref: 'ORD-'+Date.now()+'-'+Math.floor(Math.random() * 1000),
        metadata: {
            custom_fields: [
                {display_name: "Name", variable_name: "name", value: name},
                {display_name: "Phone", variable_name: "phone", value: phone}
            ]
        },
        callback: function(response) {
            window.location.href = "{{ route('payment.verify') }}?reference=" + response.reference;
        },
        onClose: function() {
            btn.disabled = false;
            btn.innerHTML = '<i class="fa fa-lock"></i> Pay ₦{{ number_format($total) }}';
        }
    });
    handler.openIframe();
}
</script>
@endsection
