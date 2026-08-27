@extends('layouts.app')

@section('title', 'Checkout — The Gift Shop')

@section('content')
<div class="container py-5">
  <h1 class="fw-bold mb-4"><i class="fa fa-lock me-2 text-danger"></i>Checkout</h1>

  {{-- Stock adjustment warnings --}}
  @if(!empty($stockErrors))
    <div class="alert alert-warning mb-4">
      <i class="fa fa-exclamation-triangle me-2"></i><strong>Cart updated:</strong>
      <ul class="mb-0 mt-1">
        @foreach($stockErrors as $err)
          <li>{{ $err }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  @if(empty($cart))
    <div class="alert alert-info">
      Your cart is empty. <a href="{{ route('shop.index') }}" class="alert-link">Go shopping</a>
    </div>
  @else
    <div class="row g-4">

      {{-- Left: Customer Info + Delivery --}}
      <div class="col-lg-7">
        <div class="card border-0 shadow-sm mb-4">
          <div class="card-body">
            <h5 class="fw-bold mb-3"><i class="fa fa-user me-2 text-danger"></i>Customer Information</h5>
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
                <input type="text" id="customer_name" class="form-control"
                       value="{{ auth()->user()?->name ?? '' }}" required>
              </div>
              <div class="col-md-6">
                <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                <input type="email" id="customer_email" class="form-control"
                       value="{{ auth()->user()?->email ?? '' }}" required>
              </div>
              <div class="col-md-6">
                <label class="form-label fw-semibold">Phone Number</label>
                <input type="tel" id="customer_phone" class="form-control"
                       placeholder="08012345678"
                       value="{{ auth()->user()?->phone ?? '' }}">
              </div>
            </div>
          </div>
        </div>

        <div class="card border-0 shadow-sm">
          <div class="card-body">
            <h5 class="fw-bold mb-3"><i class="fa fa-map-marker-alt me-2 text-danger"></i>Delivery Address</h5>
            <div class="row g-3">
              <div class="col-12">
                <label class="form-label fw-semibold">Street Address <span class="text-danger">*</span></label>
                <input type="text" id="delivery_address" class="form-control"
                       placeholder="123 Main Street" required>
              </div>
              <div class="col-md-6">
                <label class="form-label fw-semibold">City <span class="text-danger">*</span></label>
                <input type="text" id="delivery_city" class="form-control" placeholder="Lagos" required>
              </div>
              <div class="col-md-6">
                <label class="form-label fw-semibold">State <span class="text-danger">*</span></label>
                <select id="delivery_state" class="form-select" required>
                  <option value="">Select State</option>
                  @foreach(['Abia','Adamawa','Akwa Ibom','Anambra','Bauchi','Bayelsa','Benue','Borno','Cross River','Delta','Ebonyi','Edo','Ekiti','Enugu','FCT','Gombe','Imo','Jigawa','Kaduna','Kano','Katsina','Kebbi','Kogi','Kwara','Lagos','Nasarawa','Niger','Ogun','Ondo','Osun','Oyo','Plateau','Rivers','Sokoto','Taraba','Yobe','Zamfara'] as $state)
                    <option value="{{ $state }}">{{ $state }}</option>
                  @endforeach
                </select>
              </div>
            </div>
          </div>
        </div>
      </div>

      {{-- Right: Order Summary + Pay --}}
      <div class="col-lg-5">
        <div class="card border-0 shadow-sm sticky-top" style="top:80px;">
          <div class="card-body">
            <h5 class="fw-bold mb-3"><i class="fa fa-receipt me-2 text-danger"></i>Order Summary</h5>

            @foreach($cart as $item)
              <div class="d-flex justify-content-between align-items-center mb-2">
                <div class="d-flex align-items-center gap-2">
                  <img src="{{ asset($item['image']) }}" width="44" height="44"
                       style="object-fit:cover;" class="rounded border">
                  <div>
                    <p class="mb-0 small fw-semibold">{{ $item['name'] }}</p>
                    <p class="mb-0 text-muted small">x{{ $item['quantity'] }} × ₦{{ number_format($item['price']) }}</p>
                  </div>
                </div>
                <span class="small fw-semibold">₦{{ number_format($item['price'] * $item['quantity']) }}</span>
              </div>
            @endforeach

            <hr>

            {{-- Coupon --}}
            <div class="mb-3" id="coupon-section">
              @if($coupon)
                <div class="d-flex justify-content-between align-items-center bg-success-subtle border border-success rounded px-3 py-2">
                  <div>
                    <i class="fa fa-tag text-success me-1"></i>
                    <span class="fw-semibold small">{{ $coupon['code'] }}</span>
                    <span class="text-muted small ms-1">({{ $coupon['type'] === 'percent' ? $coupon['value'].'% off' : '₦'.number_format($coupon['value']).' off' }})</span>
                  </div>
                  <button type="button" class="btn btn-sm btn-link text-danger p-0" id="remove-coupon">Remove</button>
                </div>
              @else
                <div class="input-group input-group-sm">
                  <input type="text" id="coupon-input" class="form-control" placeholder="Promo code">
                  <button class="btn btn-outline-secondary" type="button" id="apply-coupon">Apply</button>
                </div>
                <div id="coupon-msg" class="small mt-1"></div>
              @endif
            </div>

            <div class="d-flex justify-content-between text-muted small mb-1">
              <span>Subtotal</span>
              <span>₦{{ number_format($subtotal) }}</span>
            </div>
            <div class="d-flex justify-content-between text-muted small mb-1" id="delivery-row">
              <span><i class="fa fa-truck me-1"></i>Delivery</span>
              <span id="delivery-val" class="text-muted fst-italic">Select state</span>
            </div>
            <div class="d-flex justify-content-between text-success small mb-2" id="discount-row" style="{{ $discount ? '' : 'display:none!important' }}">
              <span>Discount</span>
              <span id="discount-val">-₦{{ number_format($discount) }}</span>
            </div>
            <hr class="my-2">
            <div class="d-flex justify-content-between fw-bold fs-5 mb-4">
              <span>Total</span>
              <span class="text-danger" id="final-total">₦{{ number_format($total) }}</span>
            </div>

            <div class="d-grid">
              <button type="button" class="btn btn-success btn-lg" onclick="payWithPaystack()" id="pay-btn">
                <i class="fa fa-lock me-2"></i>Pay <span id="pay-amount">₦{{ number_format($total) }}</span>
              </button>
            </div>
            <p class="text-center text-muted small mt-2 mb-0">
              <i class="fa fa-shield-alt me-1"></i>Secured by Paystack
            </p>
            <a href="{{ route('cart.index') }}" class="btn btn-outline-secondary w-100 mt-3">
              <i class="fa fa-arrow-left me-1"></i>Back to Cart
            </a>
          </div>
        </div>
      </div>

    </div>
  @endif
</div>

<script src="https://js.paystack.co/v1/inline.js"></script>
@endsection

@section('scripts')
<script>
const feesMap   = @json($feesMap);
const subtotal  = {{ $subtotal }};
const initDiscount = {{ $discount }};

let deliveryFee  = 0;
let discount     = initDiscount;
let currentTotal = subtotal + deliveryFee - discount;
let paystackAmount = currentTotal * 100;

function formatNum(n) {
    return Math.round(n).toLocaleString('en-NG');
}

function recompute() {
    currentTotal   = Math.max(0, subtotal + deliveryFee - discount);
    paystackAmount = currentTotal * 100;
    document.getElementById('final-total').textContent = '₦' + formatNum(currentTotal);
    document.getElementById('pay-amount').textContent  = '₦' + formatNum(currentTotal);
}

// State change → update delivery fee
document.getElementById('delivery_state')?.addEventListener('change', function () {
    const state = this.value;
    if (!state) {
        deliveryFee = 0;
        document.getElementById('delivery-val').textContent = 'Select state';
        document.getElementById('delivery-val').classList.add('fst-italic', 'text-muted');
    } else {
        deliveryFee = feesMap[state] ?? 3500;
        document.getElementById('delivery-val').textContent = '₦' + formatNum(deliveryFee);
        document.getElementById('delivery-val').classList.remove('fst-italic', 'text-muted');
    }
    recompute();
});

// Apply coupon — delegated so it works after DOM swap
document.getElementById('coupon-section')?.addEventListener('click', function(e) {
    const btn = e.target.closest('#apply-coupon');
    if (!btn) return;
    const input = document.getElementById('coupon-input');
    const msg   = document.getElementById('coupon-msg');
    const code  = input?.value.trim();
    if (!code) return;
    btn.disabled = true;
    fetch('{{ route("coupon.apply") }}', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json', 'Accept': 'application/json' },
        body: JSON.stringify({ code })
    })
    .then(r => r.json())
    .then(data => {
        btn.disabled = false;
        if (data.error) {
            msg.innerHTML = '<span class="text-danger"><i class="fa fa-times-circle me-1"></i>' + data.error + '</span>';
        } else {
            discount = data.discount;
            document.getElementById('discount-val').textContent = '-₦' + formatNum(discount);
            document.getElementById('discount-row').style.removeProperty('display');
            msg.innerHTML = '<span class="text-success"><i class="fa fa-check-circle me-1"></i><strong>' + data.code + '</strong> applied — ' + data.label + '</span>';
            recompute();
        }
    })
    .catch(() => { btn.disabled = false; if (msg) msg.textContent = 'Something went wrong.'; });
});

document.getElementById('coupon-section')?.addEventListener('keydown', function(e) {
    if (e.target.id === 'coupon-input' && e.key === 'Enter') {
        e.preventDefault();
        document.getElementById('apply-coupon')?.click();
    }
});

// Remove coupon
document.getElementById('coupon-section')?.addEventListener('click', function(e) {
    if (!e.target.closest('#remove-coupon')) return;
    fetch('{{ route("coupon.remove") }}', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
    })
    .then(() => {
        discount = 0;
        document.getElementById('discount-row').style.display = 'none';
        recompute();
        document.getElementById('coupon-section').innerHTML = `
            <div class="input-group input-group-sm">
              <input type="text" id="coupon-input" class="form-control" placeholder="Promo code">
              <button class="btn btn-outline-secondary" type="button" id="apply-coupon">Apply</button>
            </div>
            <div id="coupon-msg" class="small mt-1"></div>`;
    });
});

function payWithPaystack() {
    const name    = document.getElementById('customer_name').value.trim();
    const email   = document.getElementById('customer_email').value.trim();
    const phone   = document.getElementById('customer_phone').value.trim();
    const address = document.getElementById('delivery_address').value.trim();
    const city    = document.getElementById('delivery_city').value.trim();
    const state   = document.getElementById('delivery_state').value;
    const btn     = document.getElementById('pay-btn');

    if (!name)    { showToast('Please enter your full name.', 'error'); return; }
    if (!email)   { showToast('Please enter your email address.', 'error'); return; }
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) { showToast('Please enter a valid email address.', 'error'); return; }
    if (!address || !city || !state) { showToast('Please fill in your complete delivery address.', 'error'); return; }

    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Opening payment...';

    const handler = PaystackPop.setup({
        key:      '{{ config("services.paystack.key") }}',
        email:    email,
        amount:   paystackAmount,
        currency: 'NGN',
        ref:      'ORD-' + Date.now() + '-' + Math.floor(Math.random() * 9999),
        metadata: {
            custom_fields: [
                { display_name: 'Name',    variable_name: 'name',        value: name },
                { display_name: 'Phone',   variable_name: 'phone',       value: phone },
                { display_name: 'Address', variable_name: 'address',     value: address },
                { display_name: 'City',    variable_name: 'city',        value: city },
                { display_name: 'State',   variable_name: 'state',       value: state },
                { display_name: 'Coupon',  variable_name: 'coupon_code', value: '{{ session("coupon.code") ?? "" }}' },
                { display_name: 'Discount',variable_name: 'discount',    value: String(discount) },
            ],
            user_id: '{{ auth()->id() ?? "" }}',
        },
        callback: function(response) {
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Verifying payment...';
            window.location.href = '{{ route("payment.verify") }}?reference=' + response.reference;
        },
        onClose: function() {
            btn.disabled = false;
            btn.innerHTML = '<i class="fa fa-lock me-2"></i>Pay <span id="pay-amount">₦' + formatNum(currentTotal) + '</span>';
            showToast('Payment window closed.', 'warning');
        }
    });

    handler.openIframe();
}
</script>
@endsection
