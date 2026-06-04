<?php

namespace App\Livewire\Frontend\Checkout;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Darryldecode\Cart\Facades\CartFacade as Cart;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\AddressBook;
use App\Models\State;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Invoice;
use App\Services\CartService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

#[Layout('components.layouts.frontend')]
class Index extends Component
{
    public $cartItems = [];
    public $cartTotal = 0;
    public $cartItemsWithStock = [];
    public $hasOutOfStockItems = false;

    // User Addresses
    public $addresses = [];
    public $selectedAddressId = null;

    // Modal state for adding a new address
    public $showAddressModal = false;

    // New address form fields
    public $new_address_title;
    public $new_address1;
    public $new_address2;
    public $new_city;
    public $new_state;
    public $new_pincode;
    public $new_country = 'India';
    public $new_is_default = false;

    // Form fields based on Order model
    public $billing_name;
    public $billing_address1;
    public $billing_address2;
    public $billing_city;
    public $billing_state;
    public $billing_pincode;
    public $billing_country = 'India';

    public $shipping_name;
    public $shipping_address1;
    public $shipping_address2;
    public $shipping_city;
    public $shipping_state;
    public $shipping_pincode;
    public $shipping_country = 'India';

    public $use_billing_for_shipping = true;

    // Payment Method
    public $payment_mode = 'ONLINE'; // Default to ONLINE

    protected $rules = [
        'billing_name' => 'required',
        'billing_address1' => 'required',
        'billing_city' => 'required',
        'billing_state' => 'required',
        'billing_pincode' => 'required',
        'billing_country' => 'required',
        'shipping_name' => 'required_if:use_billing_for_shipping,false',
        'shipping_address1' => 'required_if:use_billing_for_shipping,false',
        'shipping_city' => 'required_if:use_billing_for_shipping,false',
        'shipping_state' => 'required_if:use_billing_for_shipping,false',
        'shipping_pincode' => 'required_if:use_billing_for_shipping,false',
        'shipping_country' => 'required_if:use_billing_for_shipping,false',
        'new_address_title' => 'required_if:showAddressModal,true',
        'new_address1' => 'required_if:showAddressModal,true',
        'new_city' => 'required_if:showAddressModal,true',
        'new_state' => 'required_if:showAddressModal,true',
        'new_pincode' => 'required_if:showAddressModal,true',
    ];

    public function mount()
    {
        if (!Auth::check()) {
            session()->put('url.intended', route('checkout'));
        }
        $this->loadCart();
        if (Auth::check()) {
            $this->loadAddresses();
            $this->billing_name = Auth::user()->name;
        }
    }

    public function loadAddresses()
    {
        $this->addresses = AddressBook::where('user_id', Auth::id())->get();
        
        if ($this->addresses->count() > 0) {
            $defaultAddress = $this->addresses->where('is_default', 1)->first() ?: $this->addresses->first();
            $this->selectAddress($defaultAddress->id);
        }
    }

    public function selectAddress($id)
    {
        $this->selectedAddressId = $id;
        $address = AddressBook::where('id', $id)->where('user_id', Auth::id())->first();
        
        if ($address) {
            $this->billing_address1 = $address->address1;
            $this->billing_address2 = $address->address2;
            $this->billing_city = $address->city;
            $this->billing_state = $address->state;
            $this->billing_pincode = $address->pincode;
            $this->billing_country = $address->country;

            if ($this->use_billing_for_shipping) {
                $this->syncShippingWithBilling();
            }
        }
    }

    public function openAddressModal()
    {
        $this->reset(['new_address_title', 'new_address1', 'new_address2', 'new_city', 'new_state', 'new_pincode', 'new_is_default']);
        $this->showAddressModal = true;
    }

    public function closeAddressModal()
    {
        $this->showAddressModal = false;
    }

    public function saveNewAddress()
    {
        $this->validate([
            'new_address_title' => 'required',
            'new_address1' => 'required',
            'new_city' => 'required',
            'new_state' => 'required',
            'new_pincode' => 'required',
        ]);

        $address = AddressBook::create([
            'user_id' => Auth::id(),
            'title' => $this->new_address_title,
            'address1' => $this->new_address1,
            'address2' => $this->new_address2,
            'city' => $this->new_city,
            'state' => $this->new_state,
            'pincode' => $this->new_pincode,
            'country' => $this->new_country,
            'is_default' => $this->new_is_default,
        ]);

        if ($this->new_is_default) {
            AddressBook::where('user_id', Auth::id())
                ->where('id', '!=', $address->id)
                ->update(['is_default' => 0]);
        }

        $this->loadAddresses();
        $this->selectAddress($address->id);
        $this->closeAddressModal();
        
        session()->flash('success', 'Address added successfully!');
    }

    public function updatedUseBillingForShipping()
    {
        if ($this->use_billing_for_shipping) {
            $this->syncShippingWithBilling();
        } else {
            $this->shipping_name = '';
            $this->shipping_address1 = '';
            $this->shipping_address2 = '';
            $this->shipping_city = '';
            $this->shipping_state = '';
            $this->shipping_pincode = '';
            $this->shipping_country = 'India';
        }
    }

    private function syncShippingWithBilling()
    {
        $this->shipping_name = $this->billing_name;
        $this->shipping_address1 = $this->billing_address1;
        $this->shipping_address2 = $this->billing_address2;
        $this->shipping_city = $this->billing_city;
        $this->shipping_state = $this->billing_state;
        $this->shipping_pincode = $this->billing_pincode;
        $this->shipping_country = $this->billing_country; // Assuming user has phone
    }

    public function loadCart()
    {
        $cartContent = Cart::getContent();
        $this->cartTotal = Cart::getTotal();
        $this->hasOutOfStockItems = false;
        $this->cartItemsWithStock = [];

        foreach ($cartContent as $item) {
            $isOutOfStock = false;
            $stockQty = 0;
            
            // Get attributes safely from Darryldecode\Cart item
            $productId = $item->attributes->product_id ?? null;
            $variantId = $item->attributes->variant_id ?? null;

            if ($variantId) {
                $variant = ProductVariant::find($variantId);
                if ($variant) {
                    $stockQty = (int)$variant->stock_qty;
                    if ($stockQty < (int)$item->quantity) {
                        $isOutOfStock = true;
                        $this->hasOutOfStockItems = true;
                    }
                } else {
                    $isOutOfStock = true;
                    $this->hasOutOfStockItems = true;
                }
            } elseif ($productId) {
                $product = Product::find($productId);
                if ($product) {
                    $stockQty = (int)$product->stock_qty;
                    if ($stockQty < (int)$item->quantity) {
                        $isOutOfStock = true;
                        $this->hasOutOfStockItems = true;
                    }
                } else {
                    $isOutOfStock = true;
                    $this->hasOutOfStockItems = true;
                }
            }

            // Manually build the array to be 100% sure of keys and values
            $this->cartItemsWithStock[] = [
                'id' => $item->id,
                'name' => $item->name,
                'price' => $item->price,
                'quantity' => $item->quantity,
                'attributes' => $item->attributes->toArray(),
                'is_out_of_stock' => $isOutOfStock,
                'available_stock' => $stockQty,
            ];
        }
        
        $this->cartItems = $this->cartItemsWithStock;
    }

    public function placeOrder(CartService $cartService)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Re-validate stock right before placing order
        $this->loadCart();

        if ($this->hasOutOfStockItems) {
            $outOfStockProductNames = [];
            foreach ($this->cartItemsWithStock as $item) {
                if ($item['is_out_of_stock']) {
                    $outOfStockProductNames[] = $item['name'] . " (Available: " . $item['available_stock'] . ")";
                }
            }
            
            $errorMsg = 'Insufficient stock for the following items: ' . implode(', ', $outOfStockProductNames) . '. Please adjust your cart.';
            session()->flash('error', $errorMsg);
            return;
        }

        $this->validate();

        if ($this->use_billing_for_shipping) {
            $this->syncShippingWithBilling();
        }

        DB::beginTransaction();

        try {
            $orderNumber = 'ORD-' . strtoupper(Str::random(8));
            
            $order = Order::create([
                'user_id' => Auth::id(),
                'order_number' => $orderNumber,
                'order_date' => now(),
                'shipping_amount' => 0, // Implement shipping logic if needed
                'tax_amount' => 0, // Implement tax logic if needed
                'order_amount' => $this->cartTotal,
                'billing_name' => $this->billing_name,
                'billing_address1' => $this->billing_address1,
                'billing_address2' => $this->billing_address2,
                'billing_city' => $this->billing_city,
                'billing_state' => $this->billing_state,
                'billing_pincode' => $this->billing_pincode,
                'billing_country' => $this->billing_country,
                'shipping_name' => $this->shipping_name,
                'shipping_address1' => $this->shipping_address1,
                'shipping_address2' => $this->shipping_address2,
                'shipping_city' => $this->shipping_city,
                'shipping_state' => $this->shipping_state,
                'shipping_pincode' => $this->shipping_pincode,
                'shipping_country' => $this->shipping_country,
                'status' => 'pending',
                'payment_mode' => $this->payment_mode,
                'txn_details' => null,
            ]);

            foreach ($this->cartItemsWithStock as $item) {
                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $item['attributes']['product_id'] ?? null,
                    'product_variant_id' => $item['attributes']['variant_id'] ?? null,
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'line_total' => $item['price'] * $item['quantity'],
                    'status' => 'Pending',
                ]);

                // Reduce Stock
                if (isset($item['attributes']['variant_id']) && $item['attributes']['variant_id']) {
                    ProductVariant::where('id', $item['attributes']['variant_id'])->decrement('stock_qty', $item['quantity']);
                } else {
                    Product::where('id', $item['attributes']['product_id'])->decrement('stock_qty', $item['quantity']);
                }
            }

            Invoice::create([
                'invoice_number' => 'INV-' . strtoupper(Str::random(8)),
                'invoice_date' => now(),
                'order_id' => $order->id,
                'invoice_amount' => $this->cartTotal,
            ]);

            DB::commit();

            Cart::clear();
            $cartService->clearDbCart(); // Clear persistent cart after purchase
            $this->dispatch('cartUpdated');

            if ($this->payment_mode === 'ONLINE') {
                // Razorpay Logic Integration here
                // For now, redirecting to thank you with order ID
                return redirect()->route('thankyou', ['order' => $order->order_number]);
            } else {
                return redirect()->route('thankyou', ['order' => $order->order_number]);
            }

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Something went wrong while placing your order. ' . $e->getMessage());
        }
    }

    public function render()
    {
        $this->loadCart();
        $states = State::all();
        return view('livewire.frontend.checkout.index', [
            'states' => $states
        ]);
    }
}
