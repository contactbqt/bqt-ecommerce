<?php

namespace App\Livewire\Frontend\ThankYou;

use Illuminate\Http\Request;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.frontend')]
class Index extends Component
{
    public $orderId;
    public $order_no;

    public function mount(Request $request)
    {
        // Get the order ID from the request
        $orderno = $request->input('order');
        $order = \App\Models\Order::where('order_number', $orderno)->first();
        if (!$order) {
            throw new \Exception('Order not found');
        }
        $this->orderId = $order->id;
        $this->order_no = $orderno;
    }

    public function render()
    {
        return view('livewire.frontend.thank-you.index');
    }
}
