@extends('layouts.app')

@section('title', 'Your Cart — The Gift Shop')

@section('content')
<div class="container py-4">
  <h2 class="fw-bold mb-4"><i class="fa fa-shopping-cart me-2 text-danger"></i>Your Cart</h2>

  <div id="cart-container">
    @if(!$cart || count($cart) === 0)
      <div class="text-center py-5">
        <i class="fa fa-shopping-cart fa-4x text-muted mb-3"></i>
        <p class="lead text-muted">Your cart is empty.</p>
        <a href="{{ route('shop.index') }}" class="btn btn-danger btn-lg">Start Shopping</a>
      </div>
    @else
      <div class="row g-4">
        <div class="col-lg-8">
          <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
              <div class="table-responsive">
                <table class="table table-hover mb-0">
                  <thead class="table-light">
                    <tr>
                      <th class="ps-3">Product</th>
                      <th>Price</th>
                      <th>Quantity</th>
                      <th>Subtotal</th>
                      <th></th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($cart as $id => $item)
                    <tr data-id="{{ $id }}" class="align-middle">
                      <td class="ps-3">
                        <div class="d-flex align-items-center gap-3">
                          <img src="{{ asset($item['image']) }}" width="60" height="60" style="object-fit:cover;" class="rounded" alt="{{ $item['name'] }}">
                          <div>
                            <p class="mb-0 fw-semibold small">{{ $item['name'] }}</p>
                          </div>
                        </div>
                      </td>
                      <td class="small">₦{{ number_format($item['price']) }}</td>
                      <td>
                        <div class="input-group" style="max-width:120px;">
                          <button class="btn btn-outline-secondary btn-sm decrease-qty" type="button">-</button>
                          <input type="number" class="form-control form-control-sm text-center quantity" value="{{ $item['quantity'] }}" min="1" max="{{ $item['stock'] ?? 99 }}">
                          <button class="btn btn-outline-secondary btn-sm increase-qty" type="button">+</button>
                        </div>
                      </td>
                      <td class="subtotal fw-semibold">₦{{ number_format($item['price'] * $item['quantity']) }}</td>
                      <td>
                        <button class="btn btn-sm btn-outline-danger remove-item" title="Remove">
                          <i class="fa fa-trash"></i>
                        </button>
                      </td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <div class="mt-3">
            <a href="{{ route('shop.index') }}" class="btn btn-outline-secondary">
              <i class="fa fa-arrow-left me-1"></i>Continue Shopping
            </a>
            <a href="{{ route('cart.clear') }}" class="btn btn-outline-danger ms-2"
               onclick="return confirm('Clear entire cart?')">
              <i class="fa fa-trash me-1"></i>Clear Cart
            </a>
          </div>
        </div>

        <div class="col-lg-4">
          <div class="card border-0 shadow-sm">
            <div class="card-body">
              <h5 class="fw-bold mb-3">Order Summary</h5>
              <div class="d-flex justify-content-between mb-2">
                <span class="text-muted">Subtotal</span>
                <span>₦<span id="cart-total">{{ number_format($total) }}</span></span>
              </div>
              <div class="d-flex justify-content-between mb-2">
                <span class="text-muted">Delivery</span>
                <span class="text-success small">Calculated at checkout</span>
              </div>
              <hr>
              <div class="d-flex justify-content-between fw-bold fs-5 mb-4">
                <span>Total</span>
                <span class="text-danger">₦<span id="cart-total-2">{{ number_format($total) }}</span></span>
              </div>
              <a href="{{ route('checkout.index') }}" class="btn btn-danger w-100 btn-lg">
                <i class="fa fa-lock me-2"></i>Proceed to Checkout
              </a>
            </div>
          </div>
        </div>
      </div>
    @endif
  </div>
</div>
@endsection

@section('scripts')
<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

function syncTotals(total) {
    document.getElementById('cart-total').textContent  = total;
    document.getElementById('cart-total-2').textContent = total;
}

document.querySelectorAll('.increase-qty').forEach(btn => {
    btn.addEventListener('click', function () {
        const input  = this.previousElementSibling;
        const maxVal = parseInt(input.max) || 99;
        input.value  = Math.min(maxVal, parseInt(input.value) + 1);
        input.dispatchEvent(new Event('change'));
    });
});

document.querySelectorAll('.decrease-qty').forEach(btn => {
    btn.addEventListener('click', function () {
        const input = this.nextElementSibling;
        input.value = Math.max(1, parseInt(input.value) - 1);
        input.dispatchEvent(new Event('change'));
    });
});

document.querySelectorAll('.quantity').forEach(input => {
    input.addEventListener('change', function () {
        const row       = this.closest('tr');
        const productId = row.dataset.id;
        const qty       = this.value;

        fetch(`/cart/update/${productId}`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken, 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify({ quantity: qty })
        })
        .then(r => r.json())
        .then(data => {
            if (data.error) {
                showToast(data.error, 'error');
            } else {
                row.querySelector('.subtotal').textContent = '₦' + data.subtotal;
                syncTotals(data.total);
                updateCartBadge(data.cartCount);
            }
        });
    });
});

document.querySelectorAll('.remove-item').forEach(btn => {
    btn.addEventListener('click', function () {
        const row       = this.closest('tr');
        const productId = row.dataset.id;

        fetch(`/cart/remove/${productId}`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken, 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify({})
        })
        .then(r => r.json())
        .then(data => {
            row.remove();
            syncTotals(data.total);
            updateCartBadge(data.cartCount);
            showToast('Item removed from cart.', 'info');
            if (document.querySelectorAll('tbody tr').length === 0) {
                document.getElementById('cart-container').innerHTML = `
                    <div class="text-center py-5">
                        <i class="fa fa-shopping-cart fa-4x text-muted mb-3"></i>
                        <p class="lead text-muted">Your cart is empty.</p>
                        <a href="/" class="btn btn-danger btn-lg">Start Shopping</a>
                    </div>`;
            }
        });
    });
});
</script>
@endsection
