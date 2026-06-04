<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="mb-8">
        <h1 class="text-4xl font-black text-slate-900 tracking-tight uppercase">Checkout</h1>
        <p class="text-slate-500 mt-2 font-medium">Complete your order by providing your details.</p>
    </div>

    @if (session()->has('error'))
        <div class="mb-8 p-6 bg-red-50 border-2 border-red-100 rounded-3xl shadow-sm animate-pulse-once">
            <div class="flex items-start gap-5">
                <div class="p-3 bg-red-100 rounded-2xl text-red-600 shrink-0 shadow-inner">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-xl font-black text-red-900 uppercase tracking-tight">Order could not be placed</h3>
                    <p class="text-red-700 mt-2 font-medium">{{ session('error') }}</p>
                </div>
            </div>
        </div>
    @endif

    @if(count($cartItemsWithStock) > 0)
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12">
            <!-- Left Column: Forms -->
            <div class="lg:col-span-7 space-y-8">
                @guest
                    <div class="bg-amber-50 border-2 border-amber-100 rounded-3xl p-8 shadow-sm">
                        <div class="flex items-start gap-5">
                            <div class="p-3 bg-amber-100 rounded-2xl text-amber-600 shrink-0 shadow-inner">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-black text-amber-900 uppercase tracking-tight">You are not logged in!</h3>
                                <p class="text-amber-700 mt-2 font-medium">Please log in to proceed with the checkout and use your saved addresses.</p>
                                <div class="mt-6">
                                    <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-8 py-3.5 border border-transparent text-sm font-black rounded-xl text-white bg-amber-600 hover:bg-amber-700 transition-all shadow-lg shadow-amber-200 uppercase tracking-widest active:scale-95">
                                        Login Now
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Address Selection -->
                    <div class="bg-white border border-slate-200 rounded-3xl p-6 md:p-8 shadow-sm">
                        <div class="flex items-center justify-between mb-8 pb-4 border-b border-slate-100">
                            <h2 class="text-xl font-black text-slate-900 uppercase tracking-tighter">1. Delivery Address</h2>
                            <button type="button" wire:click="openAddressModal" class="text-xs font-black text-sky-600 hover:text-sky-700 flex items-center gap-1.5 transition-colors uppercase tracking-widest">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4" />
                                </svg>
                                Add New Address
                            </button>
                        </div>
                        
                        @if(count($addresses) > 0)
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-8">
                                @foreach($addresses as $address)
                                    <label class="relative flex cursor-pointer rounded-2xl border-2 {{ $selectedAddressId == $address->id ? 'border-sky-500 bg-sky-50/50' : 'border-slate-100 bg-white hover:border-slate-200' }} p-6 transition-all shadow-sm group">
                                        <input type="radio" wire:model.live="selectedAddressId" wire:change="selectAddress({{ $address->id }})" value="{{ $address->id }}" class="sr-only" name="address_selection">
                                        <div class="flex flex-col h-full w-full">
                                            <div class="flex items-center justify-between mb-3">
                                                <span class="block text-[11px] font-black text-slate-400 uppercase tracking-[0.2em]">{{ $address->title }}</span>
                                                @if($address->is_default)
                                                    <span class="px-2 py-0.5 bg-sky-100 text-sky-700 text-[9px] font-black rounded-md uppercase tracking-tighter">Default</span>
                                                @endif
                                            </div>
                                            <div class="text-sm text-slate-600 leading-relaxed">
                                                <p class="font-black text-slate-900 mb-1.5">{{ Auth::user()->name }}</p>
                                                <p class="font-medium">{{ $address->address1 }}</p>
                                                @if($address->address2) <p class="font-medium">{{ $address->address2 }}</p> @endif
                                                <p class="font-medium">{{ $address->city }}, {{ $address->state }} - {{ $address->pincode }}</p>
                                                <p class="mt-3 text-slate-400 text-[10px] font-black uppercase tracking-widest">{{ $address->country }}</p>
                                            </div>
                                        </div>
                                        
                                        <div class="absolute top-6 right-6 h-6 w-6 rounded-full border-2 {{ $selectedAddressId == $address->id ? 'border-sky-500 bg-sky-500' : 'border-slate-200 bg-white group-hover:border-slate-300' }} flex items-center justify-center transition-colors shadow-sm">
                                            @if($selectedAddressId == $address->id)
                                                <svg class="h-3.5 w-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7" />
                                                </svg>
                                            @endif
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-12 bg-slate-50 rounded-3xl border-2 border-dashed border-slate-200 mb-8 px-6">
                                <div class="w-20 h-20 bg-white rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-sm border border-slate-100">
                                    <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </div>
                                <h4 class="text-lg font-black text-slate-900 uppercase tracking-tight mb-2">No addresses found</h4>
                                <p class="text-slate-500 font-medium mb-8 max-w-xs mx-auto">Please add a delivery address to proceed with your order.</p>
                                <button type="button" wire:click="openAddressModal" class="inline-flex items-center px-10 py-4 bg-sky-600 text-white font-black rounded-2xl hover:bg-sky-700 transition-all shadow-xl shadow-sky-100 uppercase tracking-widest active:scale-95">
                                    Create First Address
                                </button>
                            </div>
                        @endif

                        <!-- Shipping Policy Toggle -->
                        <div class="bg-slate-50 rounded-3xl p-8 border border-slate-100 shadow-inner">
                            <h3 class="text-xs font-black text-slate-400 uppercase tracking-[0.2em] mb-6">Shipping Options</h3>
                            <label class="flex items-center gap-4 cursor-pointer group">
                                <div class="relative">
                                    <input type="checkbox" wire:model.live="use_billing_for_shipping" class="sr-only peer">
                                    <div class="w-12 h-7 bg-slate-200 rounded-full peer peer-checked:bg-sky-600 transition-colors shadow-inner"></div>
                                    <div class="absolute left-1 top-1 w-5 h-5 bg-white rounded-full transition-transform peer-checked:translate-x-5 shadow-sm"></div>
                                </div>
                                <span class="text-sm text-slate-700 font-black uppercase tracking-tight group-hover:text-slate-900 transition-colors">Ship to the same address</span>
                            </label>

                            @if(!$use_billing_for_shipping)
                                <div class="mt-10 pt-10 border-t border-slate-200 grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="col-span-full">
                                        <h4 class="text-sm font-black text-slate-900 uppercase tracking-widest mb-2">Alternate Shipping Details</h4>
                                        <p class="text-xs text-slate-400 font-medium">Please provide the name and address of the receiver.</p>
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 ml-1">Receiver Name</label>
                                        <input type="text" wire:model="shipping_name" class="w-full bg-white border-slate-200 rounded-xl focus:ring-sky-500 focus:border-sky-500 text-sm font-bold py-3.5 px-5 shadow-sm transition-all placeholder:text-slate-300">
                                    </div>
                                    <div class="col-span-full">
                                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 ml-1">Street Address</label>
                                        <input type="text" wire:model="shipping_address1" class="w-full bg-white border-slate-200 rounded-xl focus:ring-sky-500 focus:border-sky-500 text-sm font-bold py-3.5 px-5 shadow-sm transition-all placeholder:text-slate-300" placeholder="House number and street name">
                                    </div>
                                    <div class="col-span-full">
                                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 ml-1">Apartment, suite, etc. (optional)</label>
                                        <input type="text" wire:model="shipping_address2" class="w-full bg-white border-slate-200 rounded-xl focus:ring-sky-500 focus:border-sky-500 text-sm font-bold py-3.5 px-5 shadow-sm transition-all placeholder:text-slate-300">
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 ml-1">Town / City</label>
                                        <input type="text" wire:model="shipping_city" class="w-full bg-white border-slate-200 rounded-xl focus:ring-sky-500 focus:border-sky-500 text-sm font-bold py-3.5 px-5 shadow-sm transition-all placeholder:text-slate-300">
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 ml-1">Pincode</label>
                                        <input type="text" wire:model="shipping_pincode" class="w-full bg-white border-slate-200 rounded-xl focus:ring-sky-500 focus:border-sky-500 text-sm font-bold py-3.5 px-5 shadow-sm transition-all placeholder:text-slate-300">
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 ml-1">State</label>
                                        <select wire:model="shipping_state" class="w-full bg-white border-slate-200 rounded-xl focus:ring-sky-500 focus:border-sky-500 text-sm font-bold py-3.5 px-5 shadow-sm transition-all">
                                            <option value="">Select State</option>
                                            @foreach($states as $state)
                                                <option value="{{ $state->state_name }}">{{ $state->state_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 ml-1">Country</label>
                                        <input type="text" wire:model="shipping_country" readonly class="w-full bg-slate-100 border-slate-200 rounded-xl text-sm font-black text-slate-400 py-3.5 px-5 cursor-not-allowed">
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <div class="bg-white border border-slate-200 rounded-3xl p-6 md:p-8 shadow-sm">
                        <h2 class="text-xl font-black text-slate-900 uppercase tracking-tighter mb-8 pb-4 border-b border-slate-100">2. Payment Method</h2>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            @php $codEnabled = get_setting('COD_ENABLE'); @endphp
                            @if($codEnabled == '1' || $codEnabled === true)
                                <label class="relative flex cursor-pointer rounded-2xl border-2 {{ $payment_mode == 'COD' ? 'border-sky-500 bg-sky-50/50' : 'border-slate-100 bg-white hover:border-slate-200' }} p-6 transition-all shadow-sm group">
                                    <input type="radio" wire:model.live="payment_mode" value="COD" class="sr-only">
                                    <span class="flex items-center gap-5">
                                        <div class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center shadow-sm text-sky-600 border border-slate-100 group-hover:scale-110 transition-transform">
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                            </svg>
                                        </div>
                                        <span class="flex flex-col">
                                            <span class="block text-base font-black text-slate-900 uppercase tracking-tight">Cash on Delivery</span>
                                            <span class="text-xs text-slate-400 font-bold uppercase tracking-wider mt-0.5">Pay at your doorstep</span>
                                        </span>
                                    </span>
                                    <div class="absolute top-6 right-6 h-6 w-6 rounded-full border-2 {{ $payment_mode == 'COD' ? 'border-sky-500 bg-sky-500' : 'border-slate-200 bg-white group-hover:border-slate-300' }} flex items-center justify-center transition-colors shadow-sm">
                                        @if($payment_mode == 'COD')
                                            <svg class="h-3.5 w-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7" />
                                            </svg>
                                        @endif
                                    </div>
                                </label>
                            @endif

                            <label class="relative flex cursor-pointer rounded-2xl border-2 {{ $payment_mode == 'ONLINE' ? 'border-sky-500 bg-sky-50/50' : 'border-slate-100 bg-white hover:border-slate-200' }} p-6 transition-all shadow-sm group">
                                <input type="radio" wire:model.live="payment_mode" value="ONLINE" class="sr-only">
                                <span class="flex items-center gap-5">
                                    <div class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center shadow-sm text-sky-600 border border-slate-100 group-hover:scale-110 transition-transform">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                        </svg>
                                    </div>
                                    <span class="flex flex-col">
                                        <span class="block text-base font-black text-slate-900 uppercase tracking-tight">Pay Online</span>
                                        <span class="text-xs text-slate-400 font-bold uppercase tracking-wider mt-0.5">Razorpay / Cards / UPI</span>
                                    </span>
                                </span>
                                <div class="absolute top-6 right-6 h-6 w-6 rounded-full border-2 {{ $payment_mode == 'ONLINE' ? 'border-sky-500 bg-sky-500' : 'border-slate-200 bg-white group-hover:border-slate-300' }} flex items-center justify-center transition-colors shadow-sm">
                                    @if($payment_mode == 'ONLINE')
                                        <svg class="h-3.5 w-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7" />
                                        </svg>
                                    @endif
                                </div>
                            </label>
                        </div>
                    </div>
                @endguest
            </div>

            <!-- Right Column: Order Summary -->
            <div class="lg:col-span-5">
                <div class="bg-white border border-slate-200 rounded-[2.5rem] p-8 md:p-10 shadow-2xl shadow-slate-200/50 sticky top-24">
                    <h2 class="text-2xl font-black text-slate-900 mb-10 pb-5 border-b-2 border-slate-50 uppercase tracking-tighter">Order Summary</h2>

                    <!-- Cart Items -->
                    <div class="space-y-6 mb-10 max-h-[380px] overflow-y-auto pr-3 custom-scrollbar">
                        @foreach($cartItemsWithStock as $item)
                            <div class="flex gap-5 p-5 rounded-3xl border {{ $item['is_out_of_stock'] ? 'bg-red-50/60 border-red-300 ring-2 ring-red-100' : 'bg-slate-50/50 border-slate-100' }} transition-all duration-300 relative group">
                                @if($item['is_out_of_stock'])
                                    <div class="absolute -top-2 -right-2 z-10">
                                        <span class="px-3 py-1 bg-red-600 text-white text-[9px] font-black uppercase tracking-widest rounded-full shadow-lg shadow-red-200 animate-bounce-subtle">
                                            Out of Stock
                                        </span>
                                    </div>
                                @endif
                                <!-- Product Image -->
                                <div class="w-24 h-24 shrink-0 bg-white rounded-2xl border border-slate-200 overflow-hidden relative shadow-sm group">
                                    @if(isset($item['attributes']['image']) && $item['attributes']['image'])
                                        <img src="{{ asset('storage/' . $item['attributes']['image']) }}" alt="{{ $item['name'] }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500 {{ $item['is_out_of_stock'] ? 'grayscale opacity-50' : '' }}">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-slate-200 bg-slate-50">
                                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                    @endif
                                </div>

                                <!-- Product Info -->
                                <div class="flex-1 min-w-0 flex flex-col justify-center">
                                    <h4 class="text-sm font-bold text-slate-900 group-hover:text-sky-600 transition-colors line-clamp-1 {{ $item['is_out_of_stock'] ? 'line-through text-slate-400' : '' }}">{{ $item['name'] }}</h4>
                                    
                                    @if(isset($item['attributes']['options']) && count($item['attributes']['options']) > 0)
                                        <div class="flex flex-wrap gap-2 mt-2">
                                            @foreach($item['attributes']['options'] as $label => $value)
                                                <span class="text-[9px] font-black text-slate-400 bg-white border border-slate-100 px-2 py-0.5 rounded-lg uppercase tracking-wider">
                                                    {{ $label }}: {{ $value }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @endif

                                    <div class="flex items-center justify-between mt-4">
                                        <span class="text-[10px] font-black {{ $item['is_out_of_stock'] ? 'text-red-600 bg-red-100' : 'text-slate-500 bg-slate-200/50' }} px-2.5 py-1 rounded-lg uppercase tracking-widest transition-colors">Qty {{ $item['quantity'] }}</span>
                                        <p class="text-base font-black text-slate-900 tracking-tighter">₹{{ number_format($item['price'] * $item['quantity'], 2) }}</p>
                                    </div>

                                    @if($item['is_out_of_stock'])
                                        <p class="text-[10px] font-black text-red-600 mt-3 uppercase tracking-widest flex items-center gap-1.5 bg-white/50 p-2 rounded-xl border border-red-100">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                            </svg>
                                            Stock Alert: Only {{ $item['available_stock'] }} available
                                        </p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Totals -->
                    <div class="space-y-5 pt-8 border-t-2 border-dashed border-slate-100">
                        <div class="flex justify-between items-center text-slate-400 font-black text-[11px] uppercase tracking-[0.2em]">
                            <span>Subtotal</span>
                            <span class="text-slate-900 text-sm tracking-tight">₹{{ number_format($cartTotal, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center text-slate-400 font-black text-[11px] uppercase tracking-[0.2em]">
                            <span>Delivery</span>
                            <span class="text-emerald-600 text-xs tracking-widest">Free Express</span>
                        </div>
                        <div class="flex justify-between items-center pt-6 border-t border-slate-50">
                            <span class="text-lg font-black text-slate-900 uppercase tracking-tighter">Amount Payable</span>
                            <span class="text-3xl font-black text-slate-900 tracking-tighter">₹{{ number_format($cartTotal, 2) }}</span>
                        </div>
                    </div>

                    <!-- Action -->
                    <div class="mt-10">
                        @if($hasOutOfStockItems)
                            <div class="p-5 bg-red-50 text-red-600 text-[10px] font-black rounded-2xl mb-5 border border-red-100 text-center uppercase tracking-[0.15em] shadow-inner">
                                Some selected items are unavailable
                            </div>
                            <a href="{{ route('cart') }}" class="w-full block text-center bg-slate-900 text-white font-black py-5 rounded-2xl hover:bg-slate-800 transition-all shadow-2xl shadow-slate-200 active:scale-95 uppercase tracking-widest">
                                Return to Cart
                            </a>
                        @else
                            @auth
                                <button type="button" wire:click="placeOrder" class="w-full bg-sky-600 text-white font-black py-6 rounded-[1.5rem] hover:bg-sky-500 transition-all shadow-2xl shadow-sky-100 active:scale-[0.98] disabled:opacity-50 disabled:pointer-events-none uppercase tracking-[0.2em] text-sm">
                                    Complete Purchase
                                </button>
                                
                                @if (session()->has('error'))
                                    <div class="mt-5 p-4 bg-red-50 text-red-600 text-[10px] font-black rounded-xl border border-red-100 text-center uppercase tracking-widest">
                                        {{ session('error') }}
                                    </div>
                                @endif
                                @if ($errors->any())
                                    <div class="mt-5 p-5 bg-red-50 text-red-600 text-[10px] font-bold rounded-2xl border border-red-100 shadow-inner">
                                        <ul class="list-disc list-inside space-y-1.5 uppercase tracking-wide">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            @endauth
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="bg-white border border-slate-200 rounded-[3rem] p-16 text-center max-w-2xl mx-auto shadow-xl">
            <div class="w-24 h-24 bg-slate-50 rounded-[2rem] flex items-center justify-center mx-auto mb-8 shadow-inner border border-slate-100">
                <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
            </div>
            <h2 class="text-3xl font-black text-slate-900 mb-3 uppercase tracking-tighter">Your cart is empty</h2>
            <p class="text-slate-500 font-medium mb-10 text-lg">Looks like you haven't added anything to your cart yet.</p>
            <a href="{{ route('shop') }}" class="inline-flex bg-sky-600 text-white font-black px-12 py-4.5 rounded-2xl hover:bg-sky-500 transition-all shadow-2xl shadow-sky-100 active:scale-95 uppercase tracking-widest">
                Continue Shopping
            </a>
        </div>
    @endif

    <!-- Address Modal Popup -->
    @if($showAddressModal)
        <div class="fixed inset-0 z-[100] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-slate-900/80 backdrop-blur-md transition-opacity" aria-hidden="true" wire:click="closeAddressModal"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div class="inline-block align-bottom bg-white rounded-[2.5rem] text-left overflow-hidden shadow-[0_35px_60px_-15px_rgba(0,0,0,0.3)] transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full border border-slate-100">
                    <div class="bg-white px-10 pt-10 pb-8">
                        <div class="flex items-center justify-between mb-10 pb-5 border-b border-slate-100">
                            <div>
                                <h3 class="text-2xl font-black text-slate-900 uppercase tracking-tighter" id="modal-title">
                                    New Delivery Address
                                </h3>
                                <p class="text-xs text-slate-400 font-bold uppercase tracking-widest mt-1">Add a new location for your orders</p>
                            </div>
                            <button type="button" wire:click="closeAddressModal" class="text-slate-300 hover:text-slate-900 transition-all focus:outline-none p-2 bg-slate-50 rounded-xl hover:bg-slate-100">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="col-span-full">
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2.5 ml-1">Address Label</label>
                                <input type="text" wire:model="new_address_title" class="w-full bg-slate-50 border-slate-100 rounded-2xl focus:ring-sky-500 focus:border-sky-500 text-sm font-black py-4 px-6 transition-all shadow-inner placeholder:text-slate-300" placeholder="e.g. HOME, OFFICE, GYM">
                                @error('new_address_title') <span class="text-[10px] text-red-500 mt-2 font-black uppercase tracking-wider ml-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-span-full">
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2.5 ml-1">Street Address</label>
                                <input type="text" wire:model="new_address1" class="w-full bg-slate-50 border-slate-100 rounded-2xl focus:ring-sky-500 focus:border-sky-500 text-sm font-black py-4 px-6 transition-all shadow-inner placeholder:text-slate-300" placeholder="Flat / House No, Street Name">
                                @error('new_address1') <span class="text-[10px] text-red-500 mt-2 font-black uppercase tracking-wider ml-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-span-full">
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2.5 ml-1">Area / Landmark (Optional)</label>
                                <input type="text" wire:model="new_address2" class="w-full bg-slate-50 border-slate-100 rounded-2xl focus:ring-sky-500 focus:border-sky-500 text-sm font-black py-4 px-6 transition-all shadow-inner placeholder:text-slate-300">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2.5 ml-1">City</label>
                                <input type="text" wire:model="new_city" class="w-full bg-slate-50 border-slate-100 rounded-2xl focus:ring-sky-500 focus:border-sky-500 text-sm font-black py-4 px-6 transition-all shadow-inner placeholder:text-slate-300">
                                @error('new_city') <span class="text-[10px] text-red-500 mt-2 font-black uppercase tracking-wider ml-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2.5 ml-1">Pincode</label>
                                <input type="text" wire:model="new_pincode" class="w-full bg-slate-50 border-slate-100 rounded-2xl focus:ring-sky-500 focus:border-sky-500 text-sm font-black py-4 px-6 transition-all shadow-inner placeholder:text-slate-300">
                                @error('new_pincode') <span class="text-[10px] text-red-500 mt-2 font-black uppercase tracking-wider ml-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2.5 ml-1">State</label>
                                <select wire:model="new_state" class="w-full bg-slate-50 border-slate-100 rounded-2xl focus:ring-sky-500 focus:border-sky-500 text-sm font-black py-4 px-6 transition-all shadow-inner">
                                    <option value="">Select State</option>
                                    @foreach($states as $state)
                                        <option value="{{ $state->state_name }}">{{ $state->state_name }}</option>
                                    @endforeach
                                </select>
                                @error('new_state') <span class="text-[10px] text-red-500 mt-2 font-black uppercase tracking-wider ml-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2.5 ml-1">Country</label>
                                <input type="text" wire:model="new_country" readonly class="w-full bg-slate-100 border-slate-100 rounded-2xl text-sm font-black text-slate-400 py-4 px-6 cursor-not-allowed">
                            </div>
                            <div class="col-span-full pt-4">
                                <label class="flex items-center gap-4 cursor-pointer group">
                                    <div class="relative">
                                        <input type="checkbox" wire:model="new_is_default" class="sr-only peer">
                                        <div class="w-12 h-7 bg-slate-100 rounded-full peer peer-checked:bg-emerald-500 transition-colors shadow-inner"></div>
                                        <div class="absolute left-1 top-1 w-5 h-5 bg-white rounded-full transition-transform peer-checked:translate-x-5 shadow-md"></div>
                                    </div>
                                    <span class="text-xs text-slate-600 font-black uppercase tracking-widest group-hover:text-slate-900 transition-colors">Set as my primary address</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="bg-slate-50/50 px-10 py-8 flex flex-col sm:flex-row-reverse gap-4 border-t border-slate-50">
                        <button type="button" wire:click="saveNewAddress" class="w-full sm:w-auto inline-flex justify-center rounded-2xl border border-transparent shadow-[0_20px_40px_-10px_rgba(14,165,233,0.3)] px-12 py-5 bg-sky-600 text-sm font-black text-white hover:bg-sky-700 focus:outline-none transition-all uppercase tracking-[0.2em] active:scale-95">
                            Save & Use
                        </button>
                        <button type="button" wire:click="closeAddressModal" class="w-full sm:w-auto inline-flex justify-center rounded-2xl border border-slate-200 shadow-sm px-12 py-5 bg-white text-sm font-black text-slate-500 hover:bg-slate-50 hover:text-slate-900 focus:outline-none transition-all uppercase tracking-[0.2em] active:scale-95">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>