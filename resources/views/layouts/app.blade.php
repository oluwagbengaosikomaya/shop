<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>@yield('title', 'The Gift Shop')</title>

    <link href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/main.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/fa/css/all.min.css') }}" rel="stylesheet">
    
    <style>
        /* Mobile Responsive Styles */
        @media (max-width: 768px) {
            .navbar .container {
                flex-wrap: wrap;
            }
            .navbar-brand {
                font-size: 1rem;
            }
            .navbar .d-flex {
                flex-wrap: wrap;
                gap: 0.5rem !important;
                justify-content: center;
                width: 100%;
                margin-top: 0.5rem;
            }
            .btn-sm {
                font-size: 0.75rem;
                padding: 0.25rem 0.5rem;
            }
            h1 {
                font-size: 1.5rem;
            }
            h2 {
                font-size: 1.3rem;
            }
            .card-body {
                padding: 0.75rem;
            }
            .table-responsive {
                font-size: 0.85rem;
            }
        }
    </style>
</head>

<body>

<header>
  <div class="navbar navbar-dark shadow-sm custom">
    <div class="container">
      <a href="{{ route('shop.index') }}" class="navbar-brand d-flex align-items-center">
        <i class="fa fa-gift"></i>&nbsp;&nbsp;
        <strong>The Gift Shop</strong>
      </a>

      <div class="d-flex align-items-center gap-3">
        @auth
          @if(auth()->user()->is_admin)
            <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-outline-light">
              <i class="fa fa-dashboard"></i> <span class="d-none d-md-inline">Admin</span>
            </a>
          @else
            <a href="{{ route('user.dashboard') }}" class="btn btn-sm btn-outline-light">
              <i class="fa fa-list"></i> <span class="d-none d-md-inline">My Orders</span>
            </a>
          @endif
          <span class="text-white d-none d-sm-inline">{{ auth()->user()->name }}</span>
          <form method="POST" action="{{ route('logout') }}" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-sm btn-outline-light">
              <i class="fa fa-sign-out"></i> <span class="d-none d-md-inline">Logout</span>
            </button>
          </form>
        @else
          <a href="{{ route('admin.login.form') }}" class="btn btn-sm btn-danger">
            <i class="fa fa-shield"></i> <span class="d-none d-md-inline">Admin</span>
          </a>
          <a href="{{ route('register') }}" class="btn btn-sm btn-outline-light">
            <i class="fa fa-user-plus"></i> <span class="d-none d-md-inline">Register</span>
          </a>
          <a href="{{ route('login') }}" class="btn btn-sm btn-outline-light">
            <i class="fa fa-sign-in"></i> <span class="d-none d-md-inline">Login</span>
          </a>
        @endauth

        <a href="{{ route('cart.index') }}" class="btn text-white position-relative">
          <span class="fa fa-shopping-cart"></span>
          @if($cartCount > 0)
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
              {{ $cartCount }}
              <span class="visually-hidden">items in cart</span>
            </span>
          @endif
        </a>
      </div>
    </div>
  </div>
</header>

<main>
    @yield('content')
</main>

<footer class="py-5 custom text-white">
  <div class="container">
    <p class="float-end mb-1">
      <a href="#" class="text-white">Back to top</a>
    </p>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
