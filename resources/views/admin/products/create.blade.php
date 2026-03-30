@extends('admin.layouts.app')

@section('content')
<h4 class="mb-3">Add Product</h4>

<form action="{{ route('admin.products.store') }}"
      method="POST"
      enctype="multipart/form-data">
    @csrf

    <div class="mb-3">
        <label>Name</label>
        <input type="text" name="name" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Price</label>
        <input type="number" name="price" class="form-control" step="0.01" required>
    </div>

    <div class="mb-3">
        <label>Stock</label>
        <input type="number" name="stock" class="form-control" min="0" value="0">
    </div>

    <div class="mb-3">
        <label>Description</label>
        <textarea name="description" class="form-control" rows="3"></textarea>
    </div>

    <div class="mb-3">
        <label>Product Image</label>
        <input type="file" name="image" class="form-control">
    </div>

    <button class="btn btn-success">Save</button>
    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Cancel</a>
</form>
@endsection
