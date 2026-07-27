@extends('layouts.app')

@section('title', $product->name . ' — The Gift Shop')

@section('content')
<div class="container py-5">
  <nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('shop.index') }}">Shop</a></li>
      @if($product->category)
        <li class="breadcrumb-item"><a href="{{ route('shop.index', ['category' => $product->category]) }}">{{ ucfirst($product->category) }}</a></li>
      @endif
      <li class="breadcrumb-item active">{{ $product->name }}</li>
    </ol>
  </nav>

  <div class="row g-4">
    <div class="col-md-5">
      <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="img-fluid rounded shadow" style="width:100%; max-height:420px; object-fit:cover;">
    </div>

    <div class="col-md-7">
      @if($product->category)
        <span class="badge bg-secondary mb-2">{{ ucfirst($product->category) }}</span>
      @endif
      <h1 class="fw-bold">{{ $product->name }}</h1>
      <h2 class="text-danger fw-bold mb-3">₦{{ number_format($product->price) }}</h2>

      @if($product->isOutOfStock())
        <span class="badge bg-danger fs-6 mb-3">Out of Stock</span>
      @elseif($product->isLowStock())
        <span class="badge bg-warning text-dark fs-6 mb-3">Only {{ $product->stock }} left!</span>
      @else
        <span class="badge bg-success fs-6 mb-3">{{ $product->stock }} in stock</span>
      @endif

      <p class="text-muted">{{ $product->description ?? 'No description available.' }}</p>

      @if(!$product->isOutOfStock())
        <div class="d-flex align-items-center gap-3 mb-4">
          <label class="fw-semibold">Quantity:</label>
          <div class="input-group" style="max-width:140px;">
            <button class="btn btn-outline-secondary" id="decQty" type="button">-</button>
            <input type="number" id="qty" class="form-control text-center" value="1" min="1" max="{{ $product->stock }}">
            <button class="btn btn-outline-secondary" id="incQty" type="button">+</button>
          </div>
        </div>
        <button class="btn btn-danger btn-lg px-5" id="addToCartBtn" data-id="{{ $product->id }}">
          <i class="fa fa-cart-plus me-2"></i>Add to Cart
        </button>
      @else
        <button class="btn btn-secondary btn-lg px-5" disabled>Out of Stock</button>
      @endif

      <a href="{{ route('cart.index') }}" class="btn btn-outline-secondary btn-lg ms-2">
        <i class="fa fa-shopping-cart me-1"></i>View Cart
      </a>
    </div>
  </div>

  {{-- Related Products --}}
  @if($related->isNotEmpty())
  <div class="mt-5">
    <h4 class="fw-bold mb-3">You Might Also Like</h4>
    <div class="row g-3">
      @foreach($related as $rel)
        <div class="col-6 col-md-3">
          <div class="card shadow-sm h-100 product-card border-0">
            <img src="{{ asset($rel->image) }}" class="card-img-top" alt="{{ $rel->name }}" style="height:160px; object-fit:cover;">
            <div class="card-body text-center">
              <p class="small fw-semibold mb-1">{{ $rel->name }}</p>
              <p class="text-danger fw-bold mb-2">₦{{ number_format($rel->price) }}</p>
              <a href="{{ route('shop.show', $rel) }}" class="btn btn-sm btn-outline-danger w-100">View</a>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>
  @endif

  {{-- Reviews --}}
  <div class="mt-5" id="reviews">
    <h4 class="fw-bold mb-1">Customer Reviews</h4>

    @if($reviews->count())
      @php $avg = round($reviews->avg('rating'), 1); @endphp
      <div class="d-flex align-items-center gap-2 mb-4">
        <span class="fs-4 fw-bold text-warning">{{ $avg }}</span>
        <span class="text-warning">
          @for($i = 1; $i <= 5; $i++)
            <i class="fa{{ $i <= round($avg) ? 's' : 'r' }} fa-star"></i>
          @endfor
        </span>
        <span class="text-muted small">({{ $reviews->count() }} {{ Str::plural('review', $reviews->count()) }})</span>
      </div>
    @else
      <p class="text-muted small mb-4">No reviews yet. Be the first!</p>
    @endif

    {{-- Leave a Review --}}
    @auth
      <div class="card border-0 shadow-sm mb-4" style="max-width:560px">
        <div class="card-body">
          <h6 class="fw-semibold mb-3">{{ $userReview ? 'Update Your Review' : 'Leave a Review' }}</h6>
          <form method="POST" action="{{ route('reviews.store', $product) }}">
            @csrf
            <div class="mb-3">
              <label class="form-label small fw-semibold">Rating</label>
              <div class="star-picker d-flex gap-2 fs-4" id="starPicker">
                @for($i = 1; $i <= 5; $i++)
                  <i class="fa{{ ($userReview && $userReview->rating >= $i) ? 's' : 'r' }} fa-star text-warning star-btn"
                     data-val="{{ $i }}" style="cursor:pointer"></i>
                @endfor
              </div>
              <input type="hidden" name="rating" id="ratingInput" value="{{ $userReview->rating ?? '' }}" required>
              @error('rating')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
              <label class="form-label small fw-semibold">Comment <span class="text-muted fw-normal">(optional)</span></label>
              <textarea name="body" class="form-control" rows="3" maxlength="1000" placeholder="Share your experience...">{{ old('body', $userReview->body ?? '') }}</textarea>
            </div>
            <button type="submit" class="btn btn-danger btn-sm">{{ $userReview ? 'Update Review' : 'Submit Review' }}</button>
          </form>
        </div>
      </div>
    @else
      <p class="small mb-4"><a href="{{ route('login') }}">Log in</a> to leave a review.</p>
    @endauth

    {{-- Review List --}}
    @foreach($reviews as $review)
      <div class="border-bottom pb-3 mb-3">
        <div class="d-flex justify-content-between align-items-start">
          <div>
            <span class="fw-semibold">{{ $review->user->name }}</span>
            <span class="text-warning ms-2">
              @for($i = 1; $i <= 5; $i++)
                <i class="fa{{ $i <= $review->rating ? 's' : 'r' }} fa-star"></i>
              @endfor
            </span>
          </div>
          <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
        </div>
        @if($review->body)
          <p class="mb-0 mt-1 small text-muted">{{ $review->body }}</p>
        @endif
      </div>
    @endforeach
  </div>
</div>
@endsection

@section('scripts')
<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
const maxStock  = {{ $product->stock }};

document.getElementById('decQty')?.addEventListener('click', () => {
    const input = document.getElementById('qty');
    input.value = Math.max(1, parseInt(input.value) - 1);
});
document.getElementById('incQty')?.addEventListener('click', () => {
    const input = document.getElementById('qty');
    input.value = Math.min(maxStock, parseInt(input.value) + 1);
});

document.getElementById('addToCartBtn')?.addEventListener('click', function () {
    const qty = parseInt(document.getElementById('qty').value);
    const id  = this.dataset.id;
    this.disabled = true;
    this.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Adding...';

    const addNext = (remaining) => {
        if (remaining <= 0) {
            showToast('Added to cart!', 'success');
            this.disabled = false;
            this.innerHTML = '<i class="fa fa-cart-plus me-2"></i>Add to Cart';
            return;
        }
        fetch(`/cart/add/${id}`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken, 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify({})
        })
        .then(r => r.json())
        .then(data => {
            if (data.error) {
                showToast(data.error, 'error');
                this.disabled = false;
                this.innerHTML = '<i class="fa fa-cart-plus me-2"></i>Add to Cart';
            } else {
                updateCartBadge(data.cartCount);
                addNext(remaining - 1);
            }
        });
    };

    addNext(qty);
});
// Star picker
const stars = document.querySelectorAll('.star-btn');
const ratingInput = document.getElementById('ratingInput');
stars.forEach(star => {
    star.addEventListener('click', () => {
        const val = parseInt(star.dataset.val);
        ratingInput.value = val;
        stars.forEach((s, i) => {
            s.classList.toggle('fas', i < val);
            s.classList.toggle('far', i >= val);
        });
    });
    star.addEventListener('mouseover', () => {
        const val = parseInt(star.dataset.val);
        stars.forEach((s, i) => {
            s.classList.toggle('fas', i < val);
            s.classList.toggle('far', i >= val);
        });
    });
    star.addEventListener('mouseout', () => {
        const val = parseInt(ratingInput.value) || 0;
        stars.forEach((s, i) => {
            s.classList.toggle('fas', i < val);
            s.classList.toggle('far', i >= val);
        });
    });
});
</script>
@endsection
