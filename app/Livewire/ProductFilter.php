<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class ProductFilter extends Component
{
    use WithPagination;

    public string $search    = '';
    public string $category  = 'all';
    public string $sort      = 'latest';
    public string $min_price = '';
    public string $max_price = '';

    protected $queryString = [
        'search'    => ['except' => ''],
        'category'  => ['except' => 'all'],
        'sort'      => ['except' => 'latest'],
        'min_price' => ['except' => ''],
        'max_price' => ['except' => ''],
    ];

    public function updatingSearch(): void    { $this->resetPage(); }
    public function updatingCategory(): void  { $this->resetPage(); }
    public function updatingSort(): void      { $this->resetPage(); }
    public function updatingMinPrice(): void  { $this->resetPage(); }
    public function updatingMaxPrice(): void  { $this->resetPage(); }

    public function clearFilters(): void
    {
        $this->reset(['search', 'category', 'sort', 'min_price', 'max_price']);
        $this->resetPage();
    }

    public function render()
    {
        $query = Product::query();

        if ($this->search) {
            $term = $this->search;
            $query->where(fn($q) => $q->where('name', 'like', "%$term%")
                                      ->orWhere('description', 'like', "%$term%"));
        }

        if ($this->category && $this->category !== 'all') {
            $query->where('category', $this->category);
        }

        if ($this->min_price !== '') {
            $query->where('price', '>=', $this->min_price);
        }

        if ($this->max_price !== '') {
            $query->where('price', '<=', $this->max_price);
        }

        match($this->sort) {
            'price_asc'  => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            'name'       => $query->orderBy('name', 'asc'),
            default      => $query->latest(),
        };

        $products   = $query->paginate(12);
        $categories = Product::select('category')->distinct()->pluck('category')->filter()->values();

        return view('livewire.product-filter', compact('products', 'categories'));
    }
}
