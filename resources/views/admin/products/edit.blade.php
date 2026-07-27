@extends('admin.layouts.app')

@section('title', 'Edit Product')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">Edit Product</h4>
    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary btn-sm">← Back</a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-4">
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name', $product->name) }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-semibold">Price (₦) <span class="text-danger">*</span></label>
                    <input type="number" name="price" class="form-control @error('price') is-invalid @enderror"
                           value="{{ old('price', $product->price) }}" step="0.01" min="0" required>
                    @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-semibold">Stock</label>
                    <input type="number" name="stock" class="form-control @error('stock') is-invalid @enderror"
                           value="{{ old('stock', $product->stock ?? 0) }}" min="0">
                    @error('stock')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Category</label>
                    <input type="text" name="category" class="form-control @error('category') is-invalid @enderror"
                           value="{{ old('category', $product->category) }}" placeholder="e.g. accessories, toys, decor">
                    @error('category')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Product Image</label>

                    {{-- Current image --}}
                    @if($product->image)
                    <div class="mb-2 d-flex align-items-center gap-3" id="currentImageWrap">
                        <img src="{{ asset($product->image) }}" alt="{{ $product->name }}"
                             class="rounded border" style="height:80px; width:80px; object-fit:cover;">
                        <div>
                            <p class="mb-1 small text-muted">Current image</p>
                            <div class="form-check">
                                <input type="checkbox" name="remove_image" value="1"
                                       class="form-check-input" id="removeImage">
                                <label class="form-check-label small text-danger" for="removeImage">
                                    Remove image
                                </label>
                            </div>
                        </div>
                    </div>
                    @endif

                    <input type="file" name="image" id="imageInput"
                           class="form-control @error('image') is-invalid @enderror"
                           accept="image/jpeg,image/png,image/webp">
                    <small class="text-muted">Leave blank to keep current · JPG, PNG or WebP · Max 2MB</small>
                    @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror

                    {{-- New image preview --}}
                    <div class="mt-2" id="imagePreviewWrap" style="display:none;">
                        <p class="small text-muted mb-1">New image preview:</p>
                        <img id="imagePreview" src="" alt="Preview"
                             class="rounded border" style="max-height:160px; max-width:100%; object-fit:cover;">
                    </div>
                </div>

                <div class="col-12">
                    <label class="form-label fw-semibold">Description</label>
                    <textarea name="description" class="form-control @error('description') is-invalid @enderror"
                              rows="3">{{ old('description', $product->description) }}</textarea>
                    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="mt-4 d-flex gap-2">
                <button class="btn btn-primary px-4"><i class="fa fa-save me-1"></i> Update Product</button>
                <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.getElementById('imageInput').addEventListener('change', function () {
    const file = this.files[0];
    if (!file) return;
    const wrap = document.getElementById('imagePreviewWrap');
    const img  = document.getElementById('imagePreview');
    img.src = URL.createObjectURL(file);
    wrap.style.display = 'block';
});
</script>
@endsection
