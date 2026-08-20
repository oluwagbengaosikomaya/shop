@extends('layouts.app')

@section('title', 'The Gift Shop')

@section('content')

{{-- Hero Banner --}}
<style>
  .hero-slide {
    height: 480px;
    background-size: cover;
    background-position: center;
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  .hero-slide::before {
    content: '';
    position: absolute;
    inset: 0;
    background: rgba(0,0,0,.52);
  }
  .hero-slide .hero-content {
    position: relative;
    z-index: 1;
    text-align: center;
    padding: 0 1rem;
  }
  @media(max-width:576px){ .hero-slide { height: 320px; } }
</style>

<div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
  <div class="carousel-indicators">
    <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active"></button>
    <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1"></button>
    <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2"></button>
  </div>
  <div class="carousel-inner">

    <div class="carousel-item active">
      <div class="hero-slide" style="background-image:url('https://images.unsplash.com/photo-1549465220-1a8b9238cd48?w=1400&auto=format&fit=crop')">
        <div class="hero-content text-white">
          <p class="text-uppercase fw-semibold mb-2" style="letter-spacing:3px;font-size:.85rem;">The Gift Shop Nigeria</p>
          <h1 class="fw-bold display-4 mb-3">Gifts That Speak<br>From The Heart</h1>
          <p class="lead mb-4">Every moment, show your loved ones that you care.</p>
          <a href="#shopnow" class="btn btn-danger btn-lg me-2 px-4">Shop Now</a>
          <a href="#shopnow" class="btn btn-outline-light btn-lg px-4">Browse Gifts</a>
        </div>
      </div>
    </div>

    <div class="carousel-item">
      <div class="hero-slide" style="background-image:url('https://images.unsplash.com/photo-1518199266791-5375a83190b7?w=1400&auto=format&fit=crop')">
        <div class="hero-content text-white">
          <p class="text-uppercase fw-semibold mb-2" style="letter-spacing:3px;font-size:.85rem;">Special Collection</p>
          <h1 class="fw-bold display-4 mb-3">Valentine's<br>Collection</h1>
          <p class="lead mb-4">Surprise your special someone with something unforgettable.</p>
          <a href="#shopnow" class="btn btn-danger btn-lg px-4">View Collection</a>
        </div>
      </div>
    </div>

    <div class="carousel-item">
      <div class="hero-slide" style="background-image:url('https://images.unsplash.com/photo-1607344645866-009c320b63e0?w=1400&auto=format&fit=crop')">
        <div class="hero-content text-white">
          <p class="text-uppercase fw-semibold mb-2" style="letter-spacing:3px;font-size:.85rem;">Limited Offer</p>
          <h1 class="fw-bold display-4 mb-3">Free Delivery on<br>Orders Over ₦10,000</h1>
          <p class="lead mb-4">Shop more, save more. Don't miss out.</p>
          <a href="#shopnow" class="btn btn-warning btn-lg px-4 text-dark fw-bold">Shop Now</a>
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

{{-- Shop by Occasion --}}
<div class="container py-5">
  <div class="text-center mb-4">
    <h4 class="fw-bold">Shop by Occasion</h4>
    <p class="text-muted">Find the perfect gift for every moment</p>
  </div>
  <div class="row g-3">
    @php
      $occasions = [
        ['label'=>'Birthday',    'emoji'=>'🎂', 'cat'=>'birthday',   'img'=>'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=600&auto=format&fit=crop'],
        ['label'=>'Valentine',   'emoji'=>'❤️',  'cat'=>'valentine',  'img'=>'https://images.unsplash.com/photo-1518199266791-5375a83190b7?w=600&auto=format&fit=crop'],
        ['label'=>'Wedding',     'emoji'=>'💍', 'cat'=>'wedding',    'img'=>'https://images.unsplash.com/photo-1519741497674-611481863552?w=600&auto=format&fit=crop'],
        ['label'=>'Baby Shower', 'emoji'=>'🍼', 'cat'=>'baby',       'img'=>'https://images.unsplash.com/photo-1515488042361-ee00e0ddd4e4?w=600&auto=format&fit=crop'],
        ['label'=>'Christmas',   'emoji'=>'🎄', 'cat'=>'christmas',  'img'=>'https://images.unsplash.com/photo-1512389142860-9c449e58a543?w=600&auto=format&fit=crop'],
        ['label'=>'Just Because','emoji'=>'🎁', 'cat'=>'all',        'img'=>'https://images.unsplash.com/photo-1549465220-1a8b9238cd48?w=600&auto=format&fit=crop'],
      ];
    @endphp
    @foreach($occasions as $occ)
    <div class="col-6 col-md-4 col-lg-2">
      <a href="{{ route('shop.index', ['category' => $occ['cat']]) }}" class="text-decoration-none">
        <div class="rounded-3 overflow-hidden position-relative" style="height:140px;">
          <img src="{{ $occ['img'] }}" alt="{{ $occ['label'] }}" style="width:100%;height:100%;object-fit:cover;">
          <div style="position:absolute;inset:0;background:rgba(0,0,0,.45);"></div>
          <div style="position:absolute;inset:0;display:flex;flex-direction:column;align-items:center;justify-content:center;">
            <span style="font-size:1.8rem;">{{ $occ['emoji'] }}</span>
            <span class="text-white fw-bold small mt-1">{{ $occ['label'] }}</span>
          </div>
        </div>
      </a>
    </div>
    @endforeach
  </div>
</div>

{{-- Why Choose Us --}}
<div style="background:#fff;">
  <div class="container py-5">
    <div class="row g-4 align-items-center">
      <div class="col-md-6">
        <img src="https://images.unsplash.com/photo-1607344645866-009c320b63e0?w=800&auto=format&fit=crop"
             alt="Gift giving" class="img-fluid rounded-4 shadow" style="max-height:400px;width:100%;object-fit:cover;">
      </div>
      <div class="col-md-6">
        <p class="text-danger fw-semibold text-uppercase mb-2" style="letter-spacing:2px;font-size:.8rem;">Why Shop With Us</p>
        <h3 class="fw-bold mb-4">We Make Gift Giving<br>Effortless & Memorable</h3>
        <div class="d-flex flex-column gap-3">
          <div class="d-flex gap-3">
            <div class="bg-danger bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width:44px;height:44px;">
              <i class="fa fa-heart text-danger"></i>
            </div>
            <div>
              <p class="fw-semibold mb-1">Curated with Love</p>
              <p class="text-muted small mb-0">Every product is hand-picked to ensure it brings joy to the recipient.</p>
            </div>
          </div>
          <div class="d-flex gap-3">
            <div class="bg-danger bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width:44px;height:44px;">
              <i class="fa fa-truck text-danger"></i>
            </div>
            <div>
              <p class="fw-semibold mb-1">Fast & Reliable Delivery</p>
              <p class="text-muted small mb-0">We deliver across Nigeria with care and speed, right to your door.</p>
            </div>
          </div>
          <div class="d-flex gap-3">
            <div class="bg-danger bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width:44px;height:44px;">
              <i class="fa fa-gift text-danger"></i>
            </div>
            <div>
              <p class="fw-semibold mb-1">Beautiful Gift Wrapping</p>
              <p class="text-muted small mb-0">Every order comes elegantly wrapped and ready to present.</p>
            </div>
          </div>
          <div class="d-flex gap-3">
            <div class="bg-danger bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width:44px;height:44px;">
              <i class="fa fa-shield-alt text-danger"></i>
            </div>
            <div>
              <p class="fw-semibold mb-1">100% Secure Payments</p>
              <p class="text-muted small mb-0">Pay safely with Paystack — Nigeria's most trusted payment gateway.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

{{-- Trust Badges --}}
<div class="bg-white border-bottom" id="shopnow">
  <div class="container py-3">
    <div class="row g-2 text-center">
      <div class="col-6 col-md-3">
        <div class="d-flex align-items-center justify-content-center gap-2">
          <i class="fa fa-truck text-danger fa-lg"></i>
          <div class="text-start">
            <p class="mb-0 fw-semibold small">Free Delivery</p>
            <p class="mb-0 text-muted" style="font-size:.75rem;">On orders over ₦10,000</p>
          </div>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="d-flex align-items-center justify-content-center gap-2">
          <i class="fa fa-shield-alt text-danger fa-lg"></i>
          <div class="text-start">
            <p class="mb-0 fw-semibold small">Secure Payment</p>
            <p class="mb-0 text-muted" style="font-size:.75rem;">Powered by Paystack</p>
          </div>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="d-flex align-items-center justify-content-center gap-2">
          <i class="fa fa-gift text-danger fa-lg"></i>
          <div class="text-start">
            <p class="mb-0 fw-semibold small">Gift Wrapping</p>
            <p class="mb-0 text-muted" style="font-size:.75rem;">On every order</p>
          </div>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="d-flex align-items-center justify-content-center gap-2">
          <i class="fa fa-headset text-danger fa-lg"></i>
          <div class="text-start">
            <p class="mb-0 fw-semibold small">24/7 Support</p>
            <p class="mb-0 text-muted" style="font-size:.75rem;">Always here to help</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

{{-- Search & Filter Bar --}}
<div class="bg-white shadow-sm py-3 sticky-top" style="top:0; z-index:100;">
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

  {{-- Section Header + Category Pills --}}
  <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
    <div>
      <h5 class="fw-bold mb-0">
        @if(request()->hasAny(['search','category','sort','min_price','max_price']))
          {{ $products->total() }} result(s)@if(request('search')) for "<strong>{{ request('search') }}</strong>"@endif
        @else
          🛍️ Our Products
        @endif
      </h5>
    </div>
    <div class="d-flex flex-wrap gap-2">
      <a href="{{ route('shop.index', array_merge(request()->except('category'), ['category' => 'all'])) }}"
         class="btn btn-sm rounded-pill {{ !request('category') || request('category') === 'all' ? 'btn-danger' : 'btn-outline-secondary' }}">
        All
      </a>
      @foreach($categories as $cat)
        <a href="{{ route('shop.index', array_merge(request()->except('category'), ['category' => $cat])) }}"
           class="btn btn-sm rounded-pill {{ request('category') === $cat ? 'btn-danger' : 'btn-outline-secondary' }}">
          {{ ucfirst($cat) }}
        </a>
      @endforeach
    </div>
  </div>

  <div class="row g-3" id="products-grid">
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
          <a href="{{ route('shop.show', $product) }}" class="stretched-link" style="position:absolute;inset:0;z-index:0;"></a>
          <div class="card-body text-center d-flex flex-column" style="position:relative;z-index:1;">
            <p class="card-text small fw-semibold mb-1">{{ $product->name }}</p>
            <p class="fw-bold text-danger mb-1">₦{{ number_format($product->price) }}</p>
            @php $avg = $product->avgRating(); @endphp
            @if($avg > 0)
            <div class="mb-2" style="font-size:.75rem;">
              <span class="text-warning">
                @for($i = 1; $i <= 5; $i++)
                  <i class="fa{{ $i <= round($avg) ? 's' : 'r' }} fa-star"></i>
                @endfor
              </span>
              <span class="text-muted">({{ $avg }})</span>
            </div>
            @else
            <div class="mb-2" style="font-size:.75rem; color:#ccc;">
              <i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i>
            </div>
            @endif
            <div class="mt-auto d-flex gap-1">
              @if($product->isOutOfStock())
                <button class="btn btn-sm btn-secondary w-100" disabled>Out of Stock</button>
              @else
                <button class="btn btn-sm btn-danger flex-fill add-to-cart-btn" data-id="{{ $product->id }}">
                  <i class="fa fa-cart-plus me-1"></i>Add to Cart
                </button>
                <a href="{{ route('shop.show', $product) }}" class="btn btn-sm btn-outline-secondary" title="View Details">
                  <i class="fa fa-eye"></i>
                </a>
              @endif
            </div>
          </div>
        </div>
      </div>
    @empty
      <div class="col-12 text-center py-5">
        <div style="font-size:4rem;">🎁</div>
        <h5 class="fw-bold mt-3">No gifts found</h5>
        <p class="text-muted">We couldn't find what you're looking for. Try a different search or browse all gifts.</p>
        <a href="{{ route('shop.index') }}" class="btn btn-danger px-4 mt-1">Browse All Gifts</a>
      </div>
    @endforelse
  </div>

  {{-- Pagination --}}
  <div class="d-flex justify-content-center mt-4">
    {{ $products->links() }}
  </div>
</div>

{{-- Testimonials --}}
<div style="background:#fff;" class="py-5 mt-2">
  <div class="container">
    <div class="text-center mb-4">
      <h4 class="fw-bold">What Our Customers Say</h4>
      <p class="text-muted">Real reviews from happy gift givers</p>
    </div>
    <div class="row g-4">
      <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100 p-4">
          <div class="text-warning mb-2">
            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
          </div>
          <p class="text-muted fst-italic mb-3">&ldquo;I ordered a birthday hamper and it arrived beautifully wrapped. My mum was in tears! Will definitely order again.&rdquo;</p>
          <div class="d-flex align-items-center gap-2 mt-auto">
            <div class="rounded-circle bg-danger text-white d-flex align-items-center justify-content-center fw-bold" style="width:38px;height:38px;flex-shrink:0;">A</div>
            <div>
              <p class="mb-0 fw-semibold small">Adaeze O.</p>
              <p class="mb-0 text-muted" style="font-size:.75rem;">Lagos, Nigeria</p>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100 p-4">
          <div class="text-warning mb-2">
            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
          </div>
          <p class="text-muted fst-italic mb-3">&ldquo;Fast delivery and the packaging was premium. My girlfriend loved her Valentine's gift. The Gift Shop never disappoints!&rdquo;</p>
          <div class="d-flex align-items-center gap-2 mt-auto">
            <div class="rounded-circle bg-danger text-white d-flex align-items-center justify-content-center fw-bold" style="width:38px;height:38px;flex-shrink:0;">K</div>
            <div>
              <p class="mb-0 fw-semibold small">Kunle B.</p>
              <p class="mb-0 text-muted" style="font-size:.75rem;">Abuja, Nigeria</p>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100 p-4">
          <div class="text-warning mb-2">
            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="far fa-star"></i>
          </div>
          <p class="text-muted fst-italic mb-3">&ldquo;Ordered a baby shower gift set and it was perfect. The customer support was also very responsive. Highly recommend!&rdquo;</p>
          <div class="d-flex align-items-center gap-2 mt-auto">
            <div class="rounded-circle bg-danger text-white d-flex align-items-center justify-content-center fw-bold" style="width:38px;height:38px;flex-shrink:0;">F</div>
            <div>
              <p class="mb-0 fw-semibold small">Fatima M.</p>
              <p class="mb-0 text-muted" style="font-size:.75rem;">Port Harcourt, Nigeria</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

{{-- Newsletter Banner --}}
<div style="background:linear-gradient(135deg,#BF0A30 0%,#7a0620 100%);position:relative;overflow:hidden;">
  <div style="position:absolute;inset:0;opacity:.07;background-image:url('https://images.unsplash.com/photo-1549465220-1a8b9238cd48?w=1400&auto=format&fit=crop');background-size:cover;background-position:center;"></div>
  <div class="container py-5" style="position:relative;z-index:1;">
    <div class="row justify-content-center text-center">
      <div class="col-lg-6">
        <span style="font-size:2.5rem;">🎁</span>
        <h3 class="fw-bold text-white mt-2 mb-2">Get 10% Off Your First Order</h3>
        <p class="text-white-50 mb-4">Subscribe to our newsletter for exclusive deals, new arrivals and gift ideas.</p>
        <form class="d-flex gap-2 justify-content-center flex-wrap" onsubmit="handleNewsletterSubmit(event)">
          <input type="email" class="form-control" placeholder="Enter your email address" style="max-width:320px;" required>
          <button type="submit" class="btn btn-warning fw-bold text-dark px-4">Subscribe</button>
        </form>
        <p class="text-white-50 small mt-3 mb-0">No spam, unsubscribe anytime.</p>
      </div>
    </div>
  </div>
</div>

{{-- Scroll to Top FAB --}}
<button id="scroll-top-fab" onclick="window.scrollTo({top:0,behavior:'smooth'})" title="Back to top"
  style="position:fixed;bottom:96px;right:28px;z-index:1049;width:42px;height:42px;border-radius:50%;background:#fff;color:#BF0A30;border:2px solid #BF0A30;font-size:1rem;box-shadow:0 2px 10px rgba(0,0,0,.15);cursor:pointer;display:none;align-items:center;justify-content:center;">
  <i class="fa fa-chevron-up"></i>
</button>
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

// Newsletter
function handleNewsletterSubmit(e) {
    e.preventDefault();
    const input = e.target.querySelector('input[type=email]');
    showToast('Thanks for subscribing! Check your email for your 10% off code.', 'success');
    input.value = '';
}

// Scroll to top FAB visibility
const scrollFab = document.getElementById('scroll-top-fab');
window.addEventListener('scroll', () => {
    if (window.scrollY > 300) {
        scrollFab.style.display = 'flex';
    } else {
        scrollFab.style.display = 'none';
    }
});

// Filter loading state
document.querySelector('form[method="GET"]')?.addEventListener('submit', function() {
    document.getElementById('products-grid')?.classList.add('loading');
});
document.querySelectorAll('form[method="GET"] select').forEach(sel => {
    sel.addEventListener('change', function() {
        this.closest('form').submit();
    });
});

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
