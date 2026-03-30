@extends('admin.layouts.app')

@section('content')
<h4 class="mb-3">Edit Product</h4>

<form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label>Name</label>
        <input type="text" name="name"
               value="{{ $product->name }}"
               class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Price</label>
        <input type="number" name="price"
               value="{{ $product->price }}"
               class="form-control" step="0.01" required>
    </div>

    <div class="mb-3">
        <label>Stock</label>
        <input type="number" name="stock"
               value="{{ $product->stock ?? 0 }}"
               class="form-control" min="0">
    </div>

    <div class="mb-3">
        <label>Description</label>
        <textarea name="description" class="form-control" rows="3">{{ $product->description }}</textarea>
    </div>

    <div class="mb-3">
        <label>Product Image</label>
        <input type="file" name="image" class="form-control">

        @if($product->image)
            <img src="{{ asset($product->image) }}"
                 width="120" class="mt-2">
        @endif
    </div>

    <button class="btn btn-primary">Update</button>
    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Cancel</a>
</form>
@endsection
