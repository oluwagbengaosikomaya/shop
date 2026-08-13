@extends('admin.layouts.app')

@section('title', 'Coupons')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0"><i class="fa fa-tag me-2 text-danger"></i>Coupons</h4>
    <a href="{{ route('admin.coupons.create') }}" class="btn btn-danger btn-sm">
        <i class="fa fa-plus me-1"></i> New Coupon
    </a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Code</th>
                        <th>Type</th>
                        <th>Value</th>
                        <th>Min Order</th>
                        <th>Uses</th>
                        <th>Expires</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($coupons as $coupon)
                    <tr>
                        <td class="fw-bold font-monospace">{{ $coupon->code }}</td>
                        <td><span class="badge bg-secondary">{{ ucfirst($coupon->type) }}</span></td>
                        <td>
                            @if($coupon->type === 'percent')
                                {{ $coupon->value }}%
                            @else
                                ₦{{ number_format($coupon->value) }}
                            @endif
                        </td>
                        <td>{{ $coupon->min_order > 0 ? '₦' . number_format($coupon->min_order) : '—' }}</td>
                        <td>
                            {{ $coupon->used_count }}
                            @if($coupon->max_uses > 0)
                                / {{ $coupon->max_uses }}
                            @else
                                <span class="text-muted small">/ ∞</span>
                            @endif
                        </td>
                        <td class="small text-muted">
                            {{ $coupon->expires_at ? $coupon->expires_at->format('d M Y') : '—' }}
                            @if($coupon->expires_at && $coupon->expires_at->isPast())
                                <span class="badge bg-danger ms-1">Expired</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-{{ $coupon->active ? 'success' : 'secondary' }}">
                                {{ $coupon->active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="text-end">
                            <form method="POST" action="{{ route('admin.coupons.toggle', $coupon) }}" class="d-inline">
                                @csrf
                                <button class="btn btn-sm btn-outline-{{ $coupon->active ? 'warning' : 'success' }}" title="{{ $coupon->active ? 'Deactivate' : 'Activate' }}">
                                    <i class="fa fa-{{ $coupon->active ? 'pause' : 'play' }}"></i>
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.coupons.destroy', $coupon) }}" class="d-inline"
                                  onsubmit="return confirm('Delete coupon {{ $coupon->code }}?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="fa fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">No coupons yet. <a href="{{ route('admin.coupons.create') }}">Create one</a>.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-3">{{ $coupons->links() }}</div>
@endsection
