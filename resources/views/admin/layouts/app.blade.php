<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') — The Gift Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('assets/fa/css/all.min.css') }}" rel="stylesheet">
    <style>
        body { background: #f4f6f9; }
        .sidebar { width: 230px; min-height: 100vh; background: #1a1a2e; flex-shrink: 0; }
        .sidebar a { color: rgba(255,255,255,.75); text-decoration: none; padding: .5rem .75rem; border-radius: .375rem; display: flex; align-items: center; gap: .6rem; transition: background .15s, color .15s; }
        .sidebar a:hover, .sidebar a.active { background: rgba(255,255,255,.12); color: #fff; }
        .sidebar .nav-section { font-size: .7rem; text-transform: uppercase; letter-spacing: .08em; color: rgba(255,255,255,.35); padding: .75rem .75rem .25rem; }
        .stat-card { border: none; border-radius: .75rem; }
        .toast-container { z-index: 9999; }
        @media (max-width: 768px) {
            .sidebar { width: 100%; min-height: auto; }
            .admin-wrapper { flex-direction: column !important; }
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
</div>

<div class="d-flex admin-wrapper">
    {{-- Sidebar --}}
    <div class="sidebar p-3">
        <div class="d-flex align-items-center gap-2 mb-4 px-1">
            <i class="fa fa-gift text-danger fa-lg"></i>
            <span class="text-white fw-bold">Gift Shop Admin</span>
        </div>

        <div class="nav-section">Main</div>
        <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }} mb-1">
            <i class="fa fa-tachometer-alt fa-fw"></i> Dashboard
        </a>

        <div class="nav-section mt-3">Catalogue</div>
        <a href="{{ route('admin.products.index') }}" class="{{ request()->routeIs('admin.products.*') ? 'active' : '' }} mb-1">
            <i class="fa fa-box fa-fw"></i> Products
        </a>

        <div class="nav-section mt-3">Sales</div>
        <a href="{{ route('admin.orders.index') }}" class="{{ request()->routeIs('admin.orders.*') ? 'active' : '' }} mb-1">
            <i class="fa fa-shopping-bag fa-fw"></i> Orders
        </a>

        <div class="nav-section mt-3">Users</div>
        <a href="{{ route('admin.admins.index') }}" class="{{ request()->routeIs('admin.admins.*') ? 'active' : '' }} mb-1">
            <i class="fa fa-user-shield fa-fw"></i> Admin Users
        </a>

        <hr class="border-secondary mt-4">
        <a href="{{ route('shop.index') }}" class="mb-1" target="_blank">
            <i class="fa fa-store fa-fw"></i> View Shop
        </a>
        <form method="POST" action="{{ route('logout') }}" class="mt-1">
            @csrf
            <button type="submit" class="btn btn-sm btn-outline-danger w-100">
                <i class="fa fa-sign-out-alt me-1"></i> Logout
            </button>
        </form>
    </div>

    {{-- Main Content --}}
    <div class="flex-fill p-4" style="min-width:0;">
        @yield('content')
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.querySelectorAll('.toast').forEach(t => setTimeout(() => t.remove(), 4000));
</script>
@yield('scripts')
</body>
</html>
