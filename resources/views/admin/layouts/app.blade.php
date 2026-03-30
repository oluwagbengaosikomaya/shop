<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @media (max-width: 768px) {
            .d-flex { flex-direction: column !important; }
            .bg-dark { width: 100% !important; min-height: auto !important; }
            .flex-fill { padding: 1rem !important; }
            .table { font-size: 0.85rem; }
        }
    </style>
</head>
<body>

<div class="d-flex">
    <div class="bg-dark text-white p-3" style="width: 220px; min-height: 100vh;">
        <h5>Admin Panel</h5>
        <hr>
        <a href="{{ route('admin.dashboard') }}" class="text-white d-block mb-2">Dashboard</a>
        <a href="{{ route('admin.products.index') }}" class="text-white d-block mb-2">Products</a>
        <a href="{{ route('admin.orders.index') }}" class="text-white d-block mb-2">Orders</a>
        <a href="{{ route('admin.admins.index') }}" class="text-white d-block mb-2">Admin Users</a>
        <hr>
        <a href="{{ route('shop.index') }}" class="text-white d-block mb-2">
            <i class="bi bi-shop"></i> View Shop
        </a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-sm btn-outline-light w-100">
                Logout
            </button>
        </form>
    </div>

    <div class="flex-fill p-4">
        @yield('content')
    </div>
</div>

</body>
</html>
