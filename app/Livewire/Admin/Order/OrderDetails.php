<?php

namespace App\Livewire\Admin\Order;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Order;

#[Layout('components.layouts.admin-app')]
class OrderDetails extends Component
{
    public $order;

    public function mount($id)
    {
        $this->order = Order::with(['order_details.product.product_images', 'order_details.product_variant.productImages', 'user_details'])->findOrFail($id);
    }

    public function updateStatus($newStatus)
    {
        $this->order->update(['status' => $newStatus]);
        session()->flash('message', 'Order status updated successfully.');
    }

    public function render()
    {
        return view('livewire.admin.order.order-details');
    }
}
