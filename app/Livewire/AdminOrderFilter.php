<?php

namespace App\Livewire;

use App\Mail\OrderStatusUpdated;
use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AdminOrderFilter extends Component
{
    use WithPagination;

    public string $search     = '';
    public string $status     = '';
    public string $bulkStatus = 'pending';
    public array  $selected   = [];
    public bool   $selectAll  = false;

    public function updatingSearch(): void { $this->resetPage(); }
    public function updatingStatus(): void { $this->resetPage(); }

    public function updatedSelectAll(bool $value): void
    {
        $this->selected = $value
            ? $this->getQuery()->pluck('id')->map(fn($id) => (string) $id)->toArray()
            : [];
    }

    public function updateStatus(int $id, string $status): void
    {
        $order = Order::findOrFail($id);
        $prev  = $order->status;
        $order->update(['status' => $status]);

        if ($prev !== $status && $order->customer_email) {
            try { Mail::to($order->customer_email)->send(new OrderStatusUpdated($order)); }
            catch (\Exception $e) { Log::error('Order email failed: ' . $e->getMessage()); }
        }

        session()->flash('success', "Order #{$id} updated to {$status}.");
    }

    public function bulkUpdate(): void
    {
        if (empty($this->selected)) return;

        Order::whereIn('id', $this->selected)->update(['status' => $this->bulkStatus]);

        session()->flash('success', count($this->selected) . ' orders updated to ' . $this->bulkStatus . '.');
        $this->selected  = [];
        $this->selectAll = false;
    }

    private function getQuery()
    {
        return Order::query()
            ->when($this->status, fn($q) => $q->where('status', $this->status))
            ->when($this->search, fn($q) => $q->where('customer_name', 'like', "%{$this->search}%")
                                              ->orWhere('customer_email', 'like', "%{$this->search}%"))
            ->latest();
    }

    public function render()
    {
        return view('livewire.admin-order-filter', [
            'orders' => $this->getQuery()->paginate(15),
        ]);
    }
}
