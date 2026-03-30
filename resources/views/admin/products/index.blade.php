@extends('admin.layouts.app')

@section('content')
<div class="d-flex justify-content-between mb-3">
    <h4>Products</h4>
    <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
        + Add Product
    </a>
</div>

<div class="card p-3 mb-3">
    <form method="GET" class="row g-2">
        <div class="col-md-4">
            <input type="text" name="search" class="form-control" placeholder="Search products..." value="{{ request('search') }}">
        </div>
        <div class="col-md-3">
            <select name="sort" class="form-select">
                <option value="">Sort by...</option>
                <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name</option>
                <option value="price" {{ request('sort') == 'price' ? 'selected' : '' }}>Price</option>
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

<table class="table table-bordered bg-white">
    <thead>
        <tr>
            <th>#</th>
            <th>Image</th>
            <th>Name</th>
            <th>Price</th>
            <th>Stock</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($products as $product)
        <tr>
            <td>{{ $product->id }}</td>
            <td>
                <img src="{{ asset($product->image ?: 'assets/images/no-image.png') }}" width="50" alt="">
            </td>
            <td>{{ $product->name }}</td>
            <td>₦{{ number_format($product->price) }}</td>
            <td>{{ $product->stock ?? 'N/A' }}</td>
            <td class="d-flex gap-2">
                <a href="{{ route('admin.products.edit', $product) }}"
                   class="btn btn-sm btn-warning">Edit</a>

                <form method="POST"
                      action="{{ route('admin.products.destroy', $product) }}">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-danger"
                            onclick="return confirm('Delete product?')">
                        Delete
                    </button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="d-flex justify-content-center">
    {{ $products->links() }}
</div>
@endsection
