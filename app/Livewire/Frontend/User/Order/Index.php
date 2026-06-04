<?php

namespace App\Livewire\Frontend\User\Order;

use Livewire\Component;
use App\Models\Order;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;

#[Layout('components.layouts.account')]
class Index extends Component
{
    public function render()
    {
        $orders = Order::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.frontend.user.order.index', [
            'orders' => $orders
        ]);
    }
}
