@extends('admin.layouts.app')

@section('title', 'Products')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">Products</h4>
    <a href="{{ route('admin.products.create') }}" class="btn btn-danger btn-sm">
        <i class="fa fa-plus me-1"></i> Add Product
    </a>
</div>

<div class="card shadow-sm border-0 p-3 mb-4">
    <form method="GET" class="row g-2 align-items-end">
        <div class="col-md-5">
            <input type="text" name="search" class="form-control" placeholder="Search products..." value="{{ request('search') }}">
        </div>
        <div class="col-md-3">
            <select name="sort" class="form-select">
                <option value="">Sort by...</option>
                <option value="name"  {{ request('sort') == 'name'  ? 'selected' : '' }}>Name A–Z</option>
                <option value="price" {{ request('sort') == 'price' ? 'selected' : '' }}>Price</option>
                <option value="stock" {{ request('sort') == 'stock' ? 'selected' : '' }}>Stock</option>
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-secondary w-100">Filter</button>
        </div>
        <div class="col-md-2">
            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary w-100">Clear</a>
        </div>
    </form>
</div>

<div class="card shadow-sm border-0">
    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th class="ps-3">#</th>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                <tr>
                    <td class="ps-3 text-muted small">{{ $product->id }}</td>
                    <td>
                        <img src="{{ asset($product->image ?: 'assets/images/no-image.png') }}"
                             alt="{{ $product->name }}"
                             class="rounded border"
                             style="width:56px; height:56px; object-fit:cover;">
                    </td>
                    <td class="fw-semibold">{{ $product->name }}</td>
                    <td>
                        @if($product->category)
                            <span class="badge bg-secondary">{{ $product->category }}</span>
                        @else
                            <span class="text-muted small">—</span>
                        @endif
                    </td>
                    <td>₦{{ number_format($product->price) }}</td>
                    <td>
                        @if($product->isOutOfStock())
                            <span class="badge bg-danger">Out of Stock</span>
                        @elseif($product->isLowStock())
                            <span class="badge bg-warning text-dark">{{ $product->stock }} left</span>
                        @else
                            <span class="badge bg-success">{{ $product->stock }}</span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fa fa-edit"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.products.destroy', $product) }}">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"
                                        onclick="return confirm('Delete {{ addslashes($product->name) }}?')">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">No products found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="d-flex justify-content-center mt-4">
    {{ $products->links() }}
</div>
@endsection
