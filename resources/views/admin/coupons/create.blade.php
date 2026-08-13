@extends('admin.layouts.app')

@section('title', 'New Coupon')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0"><i class="fa fa-tag me-2 text-danger"></i>New Coupon</h4>
    <a href="{{ route('admin.coupons.index') }}" class="btn btn-outline-secondary btn-sm">← Back</a>
</div>

<div class="card shadow-sm border-0" style="max-width:560px;">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.coupons.store') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label fw-semibold">Coupon Code</label>
                <input type="text" name="code" class="form-control text-uppercase @error('code') is-invalid @enderror"
                       value="{{ old('code') }}" placeholder="e.g. SAVE20" required>
                @error('code')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="row g-3 mb-3">
                <div class="col-6">
                    <label class="form-label fw-semibold">Discount Type</label>
                    <select name="type" class="form-select @error('type') is-invalid @enderror" required>
                        <option value="percent" {{ old('type') === 'percent' ? 'selected' : '' }}>Percentage (%)</option>
                        <option value="fixed"   {{ old('type') === 'fixed'   ? 'selected' : '' }}>Fixed Amount (₦)</option>
                    </select>
                    @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-6">
                    <label class="form-label fw-semibold">Value</label>
                    <input type="number" name="value" class="form-control @error('value') is-invalid @enderror"
                           value="{{ old('value') }}" min="1" step="0.01" placeholder="e.g. 20" required>
                    @error('value')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-6">
                    <label class="form-label fw-semibold">Min Order Amount (₦)</label>
                    <input type="number" name="min_order" class="form-control" value="{{ old('min_order', 0) }}" min="0">
                    <div class="form-text">0 = no minimum</div>
                </div>
                <div class="col-6">
                    <label class="form-label fw-semibold">Max Uses</label>
                    <input type="number" name="max_uses" class="form-control" value="{{ old('max_uses', 0) }}" min="0">
                    <div class="form-text">0 = unlimited</div>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold">Expiry Date <span class="text-muted fw-normal">(optional)</span></label>
                <input type="date" name="expires_at" class="form-control @error('expires_at') is-invalid @enderror"
                       value="{{ old('expires_at') }}">
                @error('expires_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <button type="submit" class="btn btn-danger px-4">
                <i class="fa fa-save me-1"></i> Create Coupon
            </button>
        </form>
    </div>
</div>
@endsection
