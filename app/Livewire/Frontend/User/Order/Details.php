<?php

namespace App\Livewire\Frontend\User\Order;

use Livewire\Component;
use App\Models\Order;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;

#[Layout('components.layouts.account')]
class Details extends Component
{
    public $order;

    public function mount($id)
    {
        $this->order = Order::with([
            'order_details.product',
            'order_details.product_varient.product',
            'order_details.product_varient.attributes.attributeValue.attributes'
        ])->where('user_id', Auth::id())->findOrFail($id);
    }

    public function render()
    {
        return view('livewire.frontend.user.order.details');
    }
}
