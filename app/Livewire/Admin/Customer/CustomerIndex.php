<?php

namespace App\Livewire\Admin\Customer;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;
use App\Models\User;
use Illuminate\Support\Facades\DB;

#[Layout('components.layouts.admin-app')]
class CustomerIndex extends Component
{
    use WithPagination;

    public $search = '';

    protected $queryString = ['search'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $customers = User::query()
            ->where('user_type', '!=', 'admin') // Include both 'customer' and 'patient' (legacy)
            ->withCount('orders')
            ->withSum('orders', 'order_amount')
            ->when($this->search, function ($query) {
                $query->where(function($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%');
                });
            })
            ->latest()
            ->paginate(10);

        return view('livewire.admin.customer.customer-index', [
            'customers' => $customers
        ]);
    }
}
