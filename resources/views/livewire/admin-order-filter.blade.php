<div>
    @if(session('success'))
        <div class="alert alert-success py-2">{{ session('success') }}</div>
    @endif

    {{-- Filters --}}
    <div class="card shadow-sm border-0 p-3 mb-4">
        <div class="row g-2 align-items-end">
            <div class="col-md-5">
                <input type="text" wire:model.live.debounce.300ms="search"
                       class="form-control" placeholder="Search by name or email...">
            </div>
            <div class="col-md-4">
                <select wire:model.live="status" class="form-select">
                    <option value="">All Statuses</option>
                    @foreach(['pending','processing','shipped','completed','cancelled'] as $s)
                        <option value="{{ $s }}">{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <button wire:click="$set('search',''); $set('status','')"
                        class="btn btn-outline-secondary w-100">Clear</button>
            </div>
        </div>
    </div>

    {{-- Bulk Update --}}
    <div class="d-flex align-items-center gap-2 mb-3">
        <select wire:model="bulkStatus" class="form-select w-auto">
            @foreach(['pending','processing','shipped','completed','cancelled'] as $s)
                <option value="{{ $s }}">{{ ucfirst($s) }}</option>
            @endforeach
        </select>
        <button wire:click="bulkUpdate" class="btn btn-primary btn-sm"
                @if(empty($selected)) disabled @endif>
            Bulk Update
        </button>
        <span class="text-muted small">{{ count($selected) }} selected</span>
    </div>

    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th><input type="checkbox" wire:model.live="selectAll"></th>
                        <th>Order</th>
                        <th>Customer</th>
                        <th>Email</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr>
                        <td>
                            <input type="checkbox" wire:model.live="selected"
                                   value="{{ $order->id }}">
                        </td>
                        <td class="fw-semibold">#{{ $order->id }}</td>
                        <td>{{ $order->customer_name }}</td>
                        <td class="text-muted small">{{ $order->customer_email }}</td>
                        <td>₦{{ number_format($order->total) }}</td>
                        <td>
                            <select wire:change="updateStatus({{ $order->id }}, $event.target.value)"
                                    class="form-select form-select-sm w-auto border-0 bg-transparent fw-semibold text-{{ $order->statusColor() }}">
                                @foreach(['pending','processing','shipped','completed','cancelled'] as $s)
                                    <option value="{{ $s }}" {{ $order->status === $s ? 'selected' : '' }}>
                                        {{ ucfirst($s) }}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                        <td class="text-muted small">{{ $order->created_at->format('d M Y') }}</td>
                        <td>
                            <a href="{{ route('admin.orders.show', $order) }}"
                               class="btn btn-sm btn-outline-primary">View</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">No orders found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $orders->links() }}
    </div>
</div>
