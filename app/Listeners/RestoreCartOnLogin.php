<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use App\Services\CartService;

class RestoreCartOnLogin
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function handle(Login $event)
    {
        if (!session()->has('cart_restored')) {
            $this->cartService->restoreFromDbToSession();
            session()->put('cart_restored', true);
        }
    }
}
