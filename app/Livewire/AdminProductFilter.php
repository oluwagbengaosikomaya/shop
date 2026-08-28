<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class AdminProductFilter extends Component
{
    use WithPagination;

    public string $search = '';
    public string $sort   = '';

    public function updatingSearch(): void { $this->resetPage(); }
    public function updatingSort(): void   { $this->resetPage(); }

    public function deleteProduct(int $id): void
    {
        Product::findOrFail($id)->delete();
        session()->flash('success', 'Product deleted.');
    }

    public function render()
    {
        $query = Product::query();

        if ($this->search) {
            $query->where('name', 'like', "%{$this->search}%");
        }

        match($this->sort) {
            'name'  => $query->orderBy('name'),
            'price' => $query->orderBy('price'),
            'stock' => $query->orderBy('stock'),
            default => $query->latest(),
        };

        return view('livewire.admin-product-filter', [
            'products' => $query->paginate(15),
        ]);
    }
}
