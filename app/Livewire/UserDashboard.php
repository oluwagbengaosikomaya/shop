<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;

class UserDashboard extends Component
{
    use WithPagination;

    public function render()
    {
        return view('livewire.user-dashboard', [
            'orders' => auth()->user()->orders()->with('items')->latest()->paginate(10),
        ]);
    }
}
