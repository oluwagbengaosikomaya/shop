@extends('layouts.app')

@section('title', 'The Gift Shop')

@section('content')

{{-- Hero Banner --}}
<div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
  <div class="carousel-indicators">
    <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active"></button>
    <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1"></button>
    <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2"></button>
  </div>
  <div class="carousel-inner">
    <div class="carousel-item active">
      <div class="d-flex align-items-center justify-content-center custom text-white" style="height:320px;">
        <div class="text-center px-3">
          <h1 class="fw-bold display-5">Gifts That Speak From The Heart</h1>
          <p class="lead">Every moment, show your loved ones that you care.</p>
          <a href="#shopnow" class="btn btn-light btn-lg me-2">Shop Now</a>
          <a href="#shopnow" class="btn btn-outline-light btn-lg">Browse Gifts</a>
        </div>
      </div>
    </div>
    <div class="carousel-item">
      <div class="d-flex align-items-center justify-content-center bg-dark text-white" style="height:320px;">
        <div class="text-center px-3">
          <h1 class="fw-bold display-5">Valentine's Collection</h1>
          <p class="lead">Surprise your special someone with something unforgettable.</p>
          <a href="#shopnow" class="btn btn-danger btn-lg">View Collection</a>
        </div>
      </div>
    </div>
    <div class="carousel-item">
      <div class="d-flex align-items-center justify-content-center" style="height:320px; background:#2c3e50;">
        <div class="text-center px-3 text-white">
          <h1 class="fw-bold display-5">Free Delivery on Orders Over ₦10,000</h1>
          <p class="lead">Shop more, save more. Limited time offer.</p>
          <a href="#shopnow" class="btn btn-warning btn-lg">Shop Now</a>
        </div>
      </div>
    </div>
  </div>
  <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
    <span class="carousel-control-prev-icon"></span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
    <span class="carousel-control-next-icon"></span>
  </button>
</div>

{{-- Search & Filter Bar --}}
<div class="bg-white shadow-sm py-3 sticky-top" style="top:0; z-index:100;" id="shopnow">
  <div class="container">
    <form method="GET" action="{{ route('shop.index') }}" class="row g-2 align-items-end">
      <div class="col-12 col-md-4">
        <div class="input-group">
          <span class="input-group-text"><i class="fa fa-search"></i></span>
          <input type="text" name="search" class="form-control" placeholder="Search gifts..." value="{{ request('search') }}">
        </div>
      </div>
      <div class="col-6 col-md-2">
        <select name="category" class="form-select">
          <option value="all">All Categories</option>
          @foreach($categories as $cat)
            <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ ucfirst($cat) }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-6 col-md-2">
        <select name="sort" class="form-select">
          <option value="latest" {{ request('sort','latest') == 'latest' ? 'selected' : '' }}>Newest</option>
          <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price: Low → High</option>
          <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price: High → Low</option>
          <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name A–Z</option>
        </select>
      </div>
      <div class="col-4 col-md-1">
        <input type="number" name="min_price" class="form-control" placeholder="Min ₦" value="{{ request('min_price') }}">
      </div>
      <div class="col-4 col-md-1">
        <input type="number" name="max_price" class="form-control" placeholder="Max ₦" value="{{ request('max_price') }}">
      </div>
      <div class="col-4 col-md-1">
        <button type="submit" class="btn btn-danger w-100">Filter</button>
      </div>
      <div class="col-12 col-md-1">
        <a href="{{ route('shop.index') }}" class="btn btn-outline-secondary w-100">Clear</a>
      </div>
    </form>
  </div>
</div>

{{-- Products Grid --}}
<div class="container py-4">
  @if(request()->hasAny(['search','category','sort','min_price','max_price']))
    <p class="text-muted small mb-3">
      Showing {{ $products->total() }} result(s)
      @if(request('search')) for "<strong>{{ request('search') }}</strong>"@endif
    </p>
  @endif

  <div class="row g-3">
    @forelse ($products as $product)
      <div class="col-6 col-md-4 col-lg-3">
        <div class="card shadow-sm h-100 product-card border-0">
          <div class="position-relative">
            <img src="{{ asset($product->image) }}" class="card-img-top" alt="{{ $product->name }}" style="height:200px; object-fit:cover;">
            @if($product->isOutOfStock())
              <span class="position-absolute top-0 start-0 badge bg-secondary m-2 badge-stock">Out of Stock</span>
            @elseif($product->isLowStock())
              <span class="position-absolute top-0 start-0 badge bg-warning text-dark m-2 badge-stock">Only {{ $product->stock }} left!</span>
            @else
              <span class="position-absolute top-0 start-0 badge bg-success m-2 badge-stock">In Stock</span>
            @endif
            <button class="position-absolute top-0 end-0 btn btn-sm btn-light m-2 rounded-circle quick-view-btn"
                    data-id="{{ $product->id }}"
                    data-name="{{ $product->name }}"
                    data-price="{{ number_format($product->price) }}"
                    data-desc="{{ $product->description }}"
                    data-image="{{ asset($product->image) }}"
                    data-stock="{{ $product->stock }}"
                    data-category="{{ $product->category }}"
                    title="Quick View">
              <i class="fa fa-eye"></i>
            </button>
          </div>
          <div class="card-body text-center d-flex flex-column">
            <p class="card-text small fw-semibold mb-1">{{ $product->name }}</p>
            <p class="fw-bold text-danger mb-2">₦{{ number_format($product->price) }}</p>
            <div class="mt-auto">
              @if($product->isOutOfStock())
                <button class="btn btn-sm btn-secondary w-100" disabled>Out of Stock</button>
              @else
                <button class="btn btn-sm btn-danger w-100 add-to-cart-btn" data-id="{{ $product->id }}">
                  <i class="fa fa-cart-plus me-1"></i>Add to Cart
                </button>
              @endif
            </div>
          </div>
        </div>
      </div>
    @empty
      <div class="col-12 text-center py-5">
        <i class="fa fa-search fa-3x text-muted mb-3"></i>
        <p class="lead text-muted">No products found. <a href="{{ route('shop.index') }}">Clear filters</a></p>
      </div>
    @endforelse
  </div>

  {{-- Pagination --}}
  <div class="d-flex justify-content-center mt-4">
    {{ $products->links() }}
  </div>
</div>

{{-- Product Quick View Modal --}}
<div class="modal fade" id="quickViewModal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header border-0">
        <h5 class="modal-title fw-bold" id="modalProductName"></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="row g-3">
          <div class="col-md-5">
            <img id="modalProductImage" src="" alt="" class="img-fluid rounded" style="max-height:300px; object-fit:cover; width:100%;">
          </div>
          <div class="col-md-7">
            <span id="modalProductCategory" class="badge bg-secondary mb-2"></span>
            <h4 class="text-danger fw-bold" id="modalProductPrice"></h4>
            <p id="modalProductDesc" class="text-muted"></p>
            <p id="modalProductStock" class="small"></p>
            <div class="d-flex align-items-center gap-2 mb-3">
              <label class="fw-semibold">Qty:</label>
              <div class="input-group" style="max-width:120px;">
                <button class="btn btn-outline-secondary btn-sm" id="modalDecQty" type="button">-</button>
                <input type="number" id="modalQty" class="form-control form-control-sm text-center" value="1" min="1">
                <button class="btn btn-outline-secondary btn-sm" id="modalIncQty" type="button">+</button>
              </div>
            </div>
            <div class="d-flex gap-2">
              <button class="btn btn-danger flex-fill" id="modalAddToCart">
                <i class="fa fa-cart-plus me-1"></i>Add to Cart
              </button>
              <a id="modalViewDetail" href="#" class="btn btn-outline-secondary">
                <i class="fa fa-eye me-1"></i>Full Details
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection

@section('scripts')
<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

// Add to cart (product card button)
document.querySelectorAll('.add-to-cart-btn').forEach(btn => {
    btn.addEventListener('click', function () {
        const id  = this.dataset.id;
        const btn = this;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';

        fetch(`/cart/add/${id}`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken, 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify({})
        })
        .then(r => r.json())
        .then(data => {
            if (data.error) {
                showToast(data.error, 'error');
            } else {
                showToast(data.success, 'success');
                updateCartBadge(data.cartCount);
            }
        })
        .catch(() => showToast('Something went wrong.', 'error'))
        .finally(() => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fa fa-cart-plus me-1"></i>Add to Cart';
        });
    });
});

// Quick View Modal
let currentModalProductId = null;
let currentModalStock     = 0;

document.querySelectorAll('.quick-view-btn').forEach(btn => {
    btn.addEventListener('click', function () {
        currentModalProductId = this.dataset.id;
        currentModalStock     = parseInt(this.dataset.stock);

        document.getElementById('modalProductName').textContent     = this.dataset.name;
        document.getElementById('modalProductPrice').textContent    = '₦' + this.dataset.price;
        document.getElementById('modalProductDesc').textContent     = this.dataset.desc || 'No description available.';
        document.getElementById('modalProductImage').src            = this.dataset.image;
        document.getElementById('modalProductCategory').textContent = this.dataset.category || 'General';
        document.getElementById('modalViewDetail').href             = `/product/${currentModalProductId}`;
        document.getElementById('modalQty').value                   = 1;
        document.getElementById('modalQty').max                     = currentModalStock;

        const stockEl = document.getElementById('modalProductStock');
        if (currentModalStock <= 0) {
            stockEl.innerHTML = '<span class="text-danger">Out of Stock</span>';
            document.getElementById('modalAddToCart').disabled = true;
        } else if (currentModalStock <= 3) {
            stockEl.innerHTML = `<span class="text-warning">Only ${currentModalStock} left in stock!</span>`;
            document.getElementById('modalAddToCart').disabled = false;
        } else {
            stockEl.innerHTML = `<span class="text-success">${currentModalStock} in stock</span>`;
            document.getElementById('modalAddToCart').disabled = false;
        }

        new bootstrap.Modal(document.getElementById('quickViewModal')).show();
    });
});

document.getElementById('modalDecQty').addEventListener('click', () => {
    const input = document.getElementById('modalQty');
    input.value = Math.max(1, parseInt(input.value) - 1);
});
document.getElementById('modalIncQty').addEventListener('click', () => {
    const input = document.getElementById('modalQty');
    input.value = Math.min(currentModalStock, parseInt(input.value) + 1);
});

document.getElementById('modalAddToCart').addEventListener('click', function () {
    const qty = parseInt(document.getElementById('modalQty').value);
    this.disabled = true;
    this.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Adding...';

    const addNext = (remaining) => {
        if (remaining <= 0) {
            showToast('Added to cart!', 'success');
            bootstrap.Modal.getInstance(document.getElementById('quickViewModal')).hide();
            this.disabled = false;
            this.innerHTML = '<i class="fa fa-cart-plus me-1"></i>Add to Cart';
            return;
        }
        fetch(`/cart/add/${currentModalProductId}`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken, 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify({})
        })
        .then(r => r.json())
        .then(data => {
            if (data.error) {
                showToast(data.error, 'error');
                this.disabled = false;
                this.innerHTML = '<i class="fa fa-cart-plus me-1"></i>Add to Cart';
            } else {
                updateCartBadge(data.cartCount);
                addNext(remaining - 1);
            }
        });
    };

    addNext(qty);
});
</script>
@endsection
