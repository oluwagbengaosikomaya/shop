<div>
  {{-- Search & Filter Bar --}}
  <div class="bg-white shadow-sm py-3 sticky-top" style="top:0; z-index:100;">
    <div class="container">
      <div class="row g-2 align-items-end">
        <div class="col-12 col-md-4">
          <div class="input-group">
            <span class="input-group-text"><i class="fa fa-search"></i></span>
            <input type="text" wire:model.live.debounce.400ms="search" class="form-control" placeholder="Search gifts...">
          </div>
        </div>
        <div class="col-6 col-md-2">
          <select wire:model.live="category" class="form-select">
            <option value="all">All Categories</option>
            @foreach($categories as $cat)
              <option value="{{ $cat }}">{{ ucfirst($cat) }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-6 col-md-2">
          <select wire:model.live="sort" class="form-select">
            <option value="latest">Newest</option>
            <option value="price_asc">Price: Low → High</option>
            <option value="price_desc">Price: High → Low</option>
            <option value="name">Name A–Z</option>
          </select>
        </div>
        <div class="col-4 col-md-1">
          <input type="number" wire:model.live.debounce.400ms="min_price" class="form-control" placeholder="Min ₦">
        </div>
        <div class="col-4 col-md-1">
          <input type="number" wire:model.live.debounce.400ms="max_price" class="form-control" placeholder="Max ₦">
        </div>
        <div class="col-4 col-md-2">
          <button wire:click="clearFilters" class="btn btn-outline-secondary w-100">Clear</button>
        </div>
      </div>
    </div>
  </div>

  {{-- Products Grid --}}
  <div class="container py-4">

    {{-- Section Header + Category Pills --}}
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
      <div>
        <h5 class="fw-bold mb-0">
          @if($search || ($category && $category !== 'all') || $min_price || $max_price)
            {{ $products->total() }} result(s)
            @if($search) for "<strong>{{ $search }}</strong>" @endif
          @else
            🛍️ Our Products
          @endif
        </h5>
      </div>
      <div class="d-flex flex-wrap gap-2">
        <button wire:click="$set('category', 'all')"
                class="btn btn-sm rounded-pill {{ !$category || $category === 'all' ? 'btn-danger' : 'btn-outline-secondary' }}">
          All
        </button>
        @foreach($categories as $cat)
          <button wire:click="$set('category', '{{ $cat }}')"
                  class="btn btn-sm rounded-pill {{ $category === $cat ? 'btn-danger' : 'btn-outline-secondary' }}">
            {{ ucfirst($cat) }}
          </button>
        @endforeach
      </div>
    </div>

    <div class="row g-3" id="products-grid" wire:loading.class="loading">
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
          <button wire:click="clearFilters" class="btn btn-danger px-4 mt-1">Browse All Gifts</button>
        </div>
      @endforelse
    </div>

    {{-- Loading overlay --}}
    <div wire:loading class="text-center py-3">
      <div class="spinner-border text-danger" role="status"></div>
    </div>

    {{-- Pagination --}}
    <div class="d-flex justify-content-center mt-4">
      {{ $products->links() }}
    </div>
  </div>
</div>

@script
<script>
  // Re-bind add-to-cart and quick-view after Livewire updates the DOM
  document.addEventListener('livewire:navigated', bindButtons);
  document.addEventListener('livewire:updated', bindButtons);
  document.addEventListener('DOMContentLoaded', bindButtons);

  function bindButtons() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    document.querySelectorAll('.add-to-cart-btn').forEach(btn => {
      if (btn.dataset.bound) return;
      btn.dataset.bound = '1';
      btn.addEventListener('click', function () {
        const id = this.dataset.id;
        this.disabled = true;
        this.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';
        fetch(`/cart/add/${id}`, {
          method: 'POST',
          headers: { 'X-CSRF-TOKEN': csrfToken, 'Content-Type': 'application/json', 'Accept': 'application/json' },
          body: JSON.stringify({})
        })
        .then(r => r.json())
        .then(data => {
          if (data.error) { showToast(data.error, 'error'); }
          else { showToast(data.success, 'success'); updateCartBadge(data.cartCount); }
        })
        .catch(() => showToast('Something went wrong.', 'error'))
        .finally(() => {
          this.disabled = false;
          this.innerHTML = '<i class="fa fa-cart-plus me-1"></i>Add to Cart';
        });
      });
    });

    document.querySelectorAll('.quick-view-btn').forEach(btn => {
      if (btn.dataset.bound) return;
      btn.dataset.bound = '1';
      btn.addEventListener('click', function () {
        window.currentModalProductId = this.dataset.id;
        window.currentModalStock     = parseInt(this.dataset.stock);
        document.getElementById('modalProductName').textContent     = this.dataset.name;
        document.getElementById('modalProductPrice').textContent    = '₦' + this.dataset.price;
        document.getElementById('modalProductDesc').textContent     = this.dataset.desc || 'No description available.';
        document.getElementById('modalProductImage').src            = this.dataset.image;
        document.getElementById('modalProductCategory').textContent = this.dataset.category || 'General';
        document.getElementById('modalViewDetail').href             = `/product/${window.currentModalProductId}`;
        document.getElementById('modalQty').value                   = 1;
        document.getElementById('modalQty').max                     = window.currentModalStock;
        const stockEl = document.getElementById('modalProductStock');
        if (window.currentModalStock <= 0) {
          stockEl.innerHTML = '<span class="text-danger">Out of Stock</span>';
          document.getElementById('modalAddToCart').disabled = true;
        } else if (window.currentModalStock <= 3) {
          stockEl.innerHTML = `<span class="text-warning">Only ${window.currentModalStock} left!</span>`;
          document.getElementById('modalAddToCart').disabled = false;
        } else {
          stockEl.innerHTML = `<span class="text-success">${window.currentModalStock} in stock</span>`;
          document.getElementById('modalAddToCart').disabled = false;
        }
        new bootstrap.Modal(document.getElementById('quickViewModal')).show();
      });
    });
  }
</script>
@endscript
