<?php

namespace App\Services;

use App\Models\Cart as CartModel;
use Darryldecode\Cart\Facades\CartFacade as Cart;
use Illuminate\Support\Facades\Auth;

class CartService
{
    /**
     * Sync the current session cart to the database for the authenticated user.
     */
    public function syncSessionToDb()
    {
        if (!Auth::check()) {
            return;
        }

        $userId = Auth::id();
        $items = Cart::getContent();

        // 1. Get IDs of items currently in session
        $sessionItemIds = $items->map(fn($item) => $item->id)->toArray();

        // 2. Remove items from DB that are NO LONGER in the session cart
        // This ensures the DB matches the session perfectly (e.g. after removals)
        CartModel::where('user_id', $userId)
            ->where(function($q) use ($sessionItemIds) {
                foreach ($sessionItemIds as $id) {
                    $isVariant = str_starts_with($id, 'v_');
                    $actualId = str_replace(['v_', 'p_'], '', $id);
                    
                    if ($isVariant) {
                        $q->where('product_variant_id', '!=', $actualId);
                    } else {
                        $q->where('product_id', '!=', $actualId)->orWhereNotNull('product_variant_id');
                    }
                }
            })->delete();

        // 3. Update or Create items from session to DB
        foreach ($items as $item) {
            $productId = $item->attributes->product_id;
            $variantId = $item->attributes->variant_id;

            CartModel::updateOrCreate(
                [
                    'user_id' => $userId,
                    'product_id' => $productId,
                    'product_variant_id' => $variantId,
                ],
                [
                    'variant_details' => json_encode($item->attributes->options),
                    'price' => $item->price,
                    'discounted_price' => $item->price,
                    'quantity' => $item->quantity,
                    'line_total' => $item->getPriceSum(),
                ]
            );
        }
    }

    /**
     * Restore items from the database to the session cart upon login.
     */
    public function restoreFromDbToSession()
    {
        if (!Auth::check()) {
            return;
        }

        $userId = Auth::id();
        $dbItems = CartModel::where('user_id', $userId)->get();

        foreach ($dbItems as $dbItem) {
            $product = $dbItem->product;
            $variant = $dbItem->variant;

            if (!$product) continue;

            $id = ($variant ? 'v_' : 'p_') . ($variant ? $variant->id : $product->id);
            
            // Check if item already exists in session cart
            $existingItem = Cart::get($id);
            
            if ($existingItem) {
                 // The user wants to SUM the guest session quantity with the saved DB quantity.
                 // Example: DB had 2, Guest added 1 -> Final result should be 3.
                 Cart::update($id, [
                     'quantity' => [
                         'relative' => false,
                         'value' => $existingItem->quantity + $dbItem->quantity
                     ]
                 ]);
             } else {
                Cart::add([
                    'id' => $id,
                    'name' => $product->product_name . ($variant ? ' - ' . $variant->variant_name : ''),
                    'price' => $dbItem->price,
                    'quantity' => $dbItem->quantity,
                    'attributes' => [
                        'image' => $variant ? ($variant->productImages->first()?->image_name ?? $product->image) : $product->image,
                        'options' => json_decode($dbItem->variant_details, true),
                        'product_id' => $product->id,
                        'variant_id' => $variant ? $variant->id : null,
                    ]
                ]);
            }
        }
        
        // After restoring, sync back to DB to handle any items that were in session before login
        $this->syncSessionToDb();
    }

    /**
     * Remove an item from the database cart.
     */
    public function removeFromDb($id)
    {
        if (!Auth::check()) return;

        $isVariant = str_starts_with($id, 'v_');
        $actualId = str_replace(['v_', 'p_'], '', $id);

        CartModel::where('user_id', Auth::id())
            ->when($isVariant, function($q) use ($actualId) {
                $q->where('product_variant_id', $actualId);
            }, function($q) use ($actualId) {
                $q->where('product_id', $actualId)->whereNull('product_variant_id');
            })
            ->delete();
    }

    /**
     * Clear the database cart for the current user.
     */
    public function clearDbCart()
    {
        if (!Auth::check()) {
            return;
        }

        CartModel::where('user_id', Auth::id())->delete();
    }
}
