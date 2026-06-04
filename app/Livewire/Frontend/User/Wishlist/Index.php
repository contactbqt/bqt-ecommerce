<?php

namespace App\Livewire\Frontend\User\Wishlist;

use Livewire\Component;
use App\Models\Wishlist;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.account')]
class Index extends Component
{
    public function addToCart($productId, $variantId = null)
    {
        $this->dispatch('addToCart', 
            productId: $productId, 
            variantId: $variantId > 0 ? $variantId : null, 
            quantity: 1,
            redirectToCart: false
        );
    }

    public function remove($id)
    {
        $wishlistItem = Wishlist::where('user_id', Auth::id())->findOrFail($id);
        $wishlistItem->delete();

        session()->flash('success', 'Item removed from wishlist.');
        $this->dispatch('wishlistUpdated');
    }

    public function render()
    {
        $wishlistItems = Wishlist::where('user_id', Auth::id())
            ->with([
                'product.product_categories.categories',
                'product_variant.product.product_categories.categories',
                'product_variant.productImages'
            ])
            ->orderBy('id', 'desc')
            ->get()
            ->map(function($item) {
                // If variant_id is null, it's a single product
                if ($item->product_variant_id && $item->product_variant) {
                    $variant = $item->product_variant;
                    $product = $variant->product;
                    $item->product_name = $product->product_name . ' - ' . $variant->variant_name;
                    $item->price = $variant->price;
                    $item->offer_price = $variant->offer_price > 0 ? $variant->offer_price : null;
                    $item->is_out_of_stock = $variant->stock_qty <= 0;
                    $item->slug = $product->slug;
                    $item->category_slug = $product->product_categories->first() && $product->product_categories->first()->categories ? $product->product_categories->first()->categories->slug : 'general';
                    $item->image = $variant->productImages->first() ? $variant->productImages->first()->image_name : $product->image;
                    $item->product_id = $product->id;
                    $item->variant_id = $variant->id;
                } elseif ($item->product) {
                    $product = $item->product;
                    $item->product_name = $product->product_name;
                    $item->price = $product->price;
                    $item->offer_price = $product->offer_price > 0 ? $product->offer_price : null;
                    $item->is_out_of_stock = $product->stock_qty <= 0;
                    $item->slug = $product->slug;
                    $item->category_slug = $product->product_categories->first() && $product->product_categories->first()->categories ? $product->product_categories->first()->categories->slug : 'general';
                    $item->image = $product->image;
                    $item->product_id = $product->id;
                    $item->variant_id = 0;
                }
                return $item;
            });

        return view('livewire.frontend.user.wishlist.index', [
            'wishlistItems' => $wishlistItems
        ]);
    }
}
