<?php

namespace App\Livewire\Frontend\Cart;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Darryldecode\Cart\Facades\CartFacade as Cart;

use Livewire\Attributes\On;

#[Layout('components.layouts.frontend')]
class Index extends Component
{
    public $cartItems = [];
    public $cartTotal = 0;

    public function mount()
    {
        $this->loadCart();
    }

    #[On('cartUpdated')]
    public function loadCart()
    {
        $this->cartItems = Cart::getContent()->toArray();
        $this->cartTotal = Cart::getTotal();
    }

    public function updateQuantity($id, $action)
    {
        $this->dispatch('updateQuantity', id: $id, action: $action);
    }

    public function removeItem($id)
    {
        $this->dispatch('removeItem', id: $id);
    }

    public function clearCart()
    {
        $this->dispatch('clearCart');
    }

    public function render()
    {
        return view('livewire.frontend.cart.index');
    }
}
