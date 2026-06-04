<?php

namespace App\Livewire\Admin\Order;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;
use App\Models\Order;

#[Layout('components.layouts.admin-app')]
class OrderIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $status = '';

    protected $queryString = ['search', 'status'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updateStatus($orderId, $newStatus)
    {
        Order::find($orderId)->update(['status' => $newStatus]);
        session()->flash('message', 'Order status updated successfully.');
    }

    public function delete($id)
    {
        Order::find($id)->delete();
        session()->flash('message', 'Order deleted successfully.');
    }

    public function render()
    {
        $orders = Order::query()
            ->with(['user_details'])
            ->when($this->search, function ($query) {
                $query->where('order_number', 'like', '%' . $this->search . '%')
                    ->orWhere('billing_name', 'like', '%' . $this->search . '%');
            })
            ->when($this->status, function ($query) {
                $query->where('status', $this->status);
            })
            ->latest()
            ->paginate(10);

        return view('livewire.admin.order.order-index', [
            'orders' => $orders
        ]);
    }
}
