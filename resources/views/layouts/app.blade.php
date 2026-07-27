<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="The Gift Shop — thoughtful gifts for every occasion.">
    <title>@yield('title', 'The Gift Shop')</title>

    <link href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/main.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/fa/css/all.min.css') }}" rel="stylesheet">

    <style>
        body { background-color: #f8f9fa; }
        .navbar-brand i { font-size: 1.2rem; }
        .toast-container { z-index: 9999; }
        .product-card { transition: transform .2s, box-shadow .2s; }
        .product-card:hover { transform: translateY(-4px); box-shadow: 0 8px 24px rgba(0,0,0,.12) !important; }
        .badge-stock { font-size: .7rem; }
        .footer-links a { color: rgba(255,255,255,.7); text-decoration: none; }
        .footer-links a:hover { color: #fff; }
        @media (max-width: 768px) {
            .navbar-brand { font-size: 1rem; }
            .btn-sm { font-size: .75rem; padding: .25rem .5rem; }
            h1 { font-size: 1.5rem; }
        }
    </style>
    @yield('head')
</head>

<body>

{{-- Toast Container --}}
<div class="toast-container position-fixed top-0 end-0 p-3">
    @if(session('success'))
    <div class="toast align-items-center text-bg-success border-0 show" role="alert">
        <div class="d-flex">
            <div class="toast-body"><i class="fa fa-check-circle me-2"></i>{{ session('success') }}</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
    @endif
    @if(session('error'))
    <div class="toast align-items-center text-bg-danger border-0 show" role="alert">
        <div class="d-flex">
            <div class="toast-body"><i class="fa fa-exclamation-circle me-2"></i>{{ session('error') }}</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
    @endif
    @if(session('info'))
    <div class="toast align-items-center text-bg-info border-0 show" role="alert">
        <div class="d-flex">
            <div class="toast-body"><i class="fa fa-info-circle me-2"></i>{{ session('info') }}</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
    @endif
    <div id="js-toast-container"></div>
</div>

<header>
  <div class="navbar navbar-dark shadow-sm custom">
    <div class="container">
      <a href="{{ route('shop.index') }}" class="navbar-brand d-flex align-items-center gap-2">
        <i class="fa fa-gift"></i>
        <strong>The Gift Shop</strong>
      </a>

      <div class="d-flex align-items-center gap-2">
        @auth
          @if(auth()->user()->is_admin)
            <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-outline-light">
              <i class="fa fa-tachometer-alt"></i> <span class="d-none d-md-inline">Admin</span>
            </a>
          @else
            <a href="{{ route('user.dashboard') }}" class="btn btn-sm btn-outline-light">
              <i class="fa fa-list"></i> <span class="d-none d-md-inline">My Orders</span>
            </a>
            <a href="{{ route('user.profile') }}" class="btn btn-sm btn-outline-light">
              <i class="fa fa-user"></i> <span class="d-none d-md-inline">Profile</span>
            </a>
          @endif
          <span class="text-white d-none d-sm-inline small">{{ auth()->user()->name }}</span>
          <form method="POST" action="{{ route('logout') }}" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-sm btn-outline-light">
              <i class="fa fa-sign-out-alt"></i> <span class="d-none d-md-inline">Logout</span>
            </button>
          </form>
        @else
          <a href="{{ route('register') }}" class="btn btn-sm btn-outline-light">
            <i class="fa fa-user-plus"></i> <span class="d-none d-md-inline">Register</span>
          </a>
          <a href="{{ route('login') }}" class="btn btn-sm btn-outline-light">
            <i class="fa fa-sign-in-alt"></i> <span class="d-none d-md-inline">Login</span>
          </a>
        @endauth

        <a href="{{ route('cart.index') }}" class="btn text-white position-relative">
          <i class="fa fa-shopping-cart"></i>
          <span id="cart-badge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-warning text-dark {{ ($cartCount ?? 0) > 0 ? '' : 'd-none' }}">
            {{ $cartCount ?? 0 }}
          </span>
        </a>
      </div>
    </div>
  </div>
</header>

<main>
    @yield('content')
</main>

<footer class="py-5 custom text-white mt-5">
  <div class="container">
    <div class="row">
      <div class="col-md-4 mb-4">
        <h5><i class="fa fa-gift me-2"></i>The Gift Shop</h5>
        <p class="small text-white-50">Every moment, you can still show your loved ones that you care. Thoughtful gifts for every occasion.</p>
      </div>
      <div class="col-md-4 mb-4 footer-links">
        <h6 class="text-uppercase fw-bold mb-3">Quick Links</h6>
        <div class="d-flex flex-column gap-1">
          <a href="{{ route('shop.index') }}">Shop</a>
          <a href="{{ route('cart.index') }}">Cart</a>
          @auth
            <a href="{{ route('user.dashboard') }}">My Orders</a>
            @if(!auth()->user()->is_admin)
            <a href="{{ route('user.profile') }}">My Profile</a>
            @endif
          @else
            <a href="{{ route('login') }}">Login</a>
            <a href="{{ route('register') }}">Register</a>
          @endauth
        </div>
      </div>
      <div class="col-md-4 mb-4 footer-links">
        <h6 class="text-uppercase fw-bold mb-3">Contact</h6>
        <p class="small text-white-50 mb-1"><i class="fa fa-envelope me-2"></i>hello@thegiftshop.ng</p>
        <p class="small text-white-50 mb-1"><i class="fa fa-phone me-2"></i>+234 800 000 0000</p>
        <p class="small text-white-50"><i class="fa fa-map-marker-alt me-2"></i>Lagos, Nigeria</p>
        <div class="d-flex gap-3 mt-3">
          <a href="#"><i class="fab fa-instagram fa-lg"></i></a>
          <a href="#"><i class="fab fa-twitter fa-lg"></i></a>
          <a href="#"><i class="fab fa-facebook fa-lg"></i></a>
        </div>
      </div>
    </div>
    <hr class="border-secondary">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
      <small class="text-white-50">&copy; {{ date('Y') }} The Gift Shop. All rights reserved.</small>
      <a href="#" class="text-white-50 small">Back to top ↑</a>
    </div>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Global toast helper
function showToast(message, type = 'success') {
    const colors = { success: 'text-bg-success', error: 'text-bg-danger', warning: 'text-bg-warning', info: 'text-bg-info' };
    const icons  = { success: 'fa-check-circle', error: 'fa-exclamation-circle', warning: 'fa-exclamation-triangle', info: 'fa-info-circle' };
    const el = document.createElement('div');
    el.className = `toast align-items-center ${colors[type] || colors.success} border-0 show mb-2`;
    el.setAttribute('role', 'alert');
    el.innerHTML = `<div class="d-flex"><div class="toast-body"><i class="fa ${icons[type] || icons.success} me-2"></i>${message}</div><button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button></div>`;
    document.getElementById('js-toast-container').appendChild(el);
    setTimeout(() => el.remove(), 4000);
}

function updateCartBadge(count) {
    const badge = document.getElementById('cart-badge');
    if (!badge) return;
    if (count > 0) { badge.textContent = count; badge.classList.remove('d-none'); }
    else { badge.classList.add('d-none'); }
}

// Auto-dismiss flash toasts
document.querySelectorAll('.toast').forEach(t => {
    setTimeout(() => { if (t.parentNode) t.remove(); }, 4000);
});
</script>
@yield('scripts')
</body>
</html>
