<?php

namespace App\Livewire\Frontend\Cart;

use Livewire\Component;
use Livewire\Attributes\On;
use Darryldecode\Cart\Facades\CartFacade as Cart;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Services\CartService;
use Illuminate\Support\Facades\Auth;

class CartWidget extends Component
{
    public $cartCount = 0;

    public function mount(CartService $cartService)
    {
        // If user just logged in, restore their cart
        if (Auth::check() && !session()->has('cart_restored')) {
            $cartService->restoreFromDbToSession();
            session()->put('cart_restored', true);
        }
        $this->updateCartCount();
    }

    #[On('cartUpdated')]
    public function updateCartCount()
    {
        $this->cartCount = Cart::getTotalQuantity();
    }

    #[On('addToCart')]
    public function addToCart($productId, $variantId = null, $quantity = 1, $redirectToCart = false, CartService $cartService)
    {
        $product = Product::with(['product_images'])->find($productId);
        if (!$product) return;

        $variant = null;
        if ($product->product_type === 'variant') {
            if (!$variantId) {
                session()->flash('error', 'Please select a variant first.');
                return;
            }
            $variant = ProductVariant::with(['productImages', 'attributes.attributeValue.attributes'])->find($variantId);
        }

        $id = $variant ? $variant->id : $product->id;
        $name = $product->product_name;
        $price = $variant ? ($variant->offer_price > 0 ? $variant->offer_price : $variant->price) : ($product->offer_price > 0 ? $product->offer_price : $product->price);
        
        $mainImage = $product->image;
        if ($variant && $variant->productImages->count() > 0) {
            $mainImage = $variant->productImages->first()->image_name;
        } elseif ($product->product_images->count() > 0) {
            $mainImage = $product->product_images->first()->image_name;
        }

        $options = [];
        if ($variant) {
            $name .= ' - ' . $variant->variant_name;
            foreach ($variant->attributes as $attr) {
                if ($attr->attributeValue && $attr->attributeValue->attributes) {
                    $options[$attr->attributeValue->attributes->attribute_name] = $attr->attributeValue->value_name;
                }
            }
        }

        Cart::add([
            'id' => ($variant ? 'v_' : 'p_') . $id,
            'name' => $name,
            'price' => $price,
            'quantity' => $quantity,
            'attributes' => [
                'image' => $mainImage,
                'options' => $options,
                'product_id' => $product->id,
                'variant_id' => $variant ? $variant->id : null,
            ]
        ]);

        // Sync to DB if logged in
        $cartService->syncSessionToDb();

        $this->updateCartCount();
        
        // Notify other components if needed
        $this->dispatch('cartUpdated');
        
        session()->flash('success', 'Product added to cart successfully!');
        if ($redirectToCart) {
            return redirect()->route('cart');
        }
    }

    #[On('updateQuantity')]
    public function updateQuantity($id, $action, CartService $cartService)
    {
        $item = Cart::get($id);
        if (!$item) return;

        if ($action === 'increase') {
            Cart::update($id, [
                'quantity' => 1
            ]);
        } elseif ($action === 'decrease') {
            if ($item->quantity > 1) {
                Cart::update($id, [
                    'quantity' => -1
                ]);
            } else {
                $this->removeItem($id, $cartService);
            }
        }

        // Sync to DB if logged in
        $cartService->syncSessionToDb();

        $this->updateCartCount();
        $this->dispatch('cartUpdated');
    }

    #[On('removeItem')]
    public function removeItem($id, CartService $cartService)
    {
        Cart::remove($id);
        $cartService->removeFromDb($id);

        $this->updateCartCount();
        $this->dispatch('cartUpdated');
        session()->flash('success', 'Item removed from cart.');
    }

    #[On('clearCart')]
    public function clearCart(CartService $cartService)
    {
        Cart::clear();
        $cartService->clearDbCart();
        $this->updateCartCount();
        $this->dispatch('cartUpdated');
    }

    public function render()
    {
        return view('livewire.frontend.cart.cart-widget');
    }
}
