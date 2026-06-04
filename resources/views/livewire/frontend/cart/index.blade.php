<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h1 class="text-3xl font-black text-slate-900 mb-8">Your Cart</h1>

    @if(count($cartItems) > 0)
        <div class="flex flex-col lg:flex-row gap-12 items-start">
            <!-- Cart Items -->
            <div class="w-full lg:flex-1 space-y-6">
                <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
                    <ul role="list" class="divide-y divide-slate-200">
                        @foreach($cartItems as $item)
                            <li class="flex p-6 sm:p-8">
                                <div class="h-24 w-24 sm:h-32 sm:w-32 shrink-0 overflow-hidden rounded-xl border border-slate-200 bg-slate-50">
                                    @if(isset($item['attributes']['image']) && $item['attributes']['image'])
                                        <img src="{{ asset('storage/' . $item['attributes']['image']) }}" alt="{{ $item['name'] }}" class="h-full w-full object-cover object-center">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-slate-300">
                                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                    @endif
                                </div>

                                <div class="ml-6 flex flex-1 flex-col">
                                    <div>
                                        <div class="flex justify-between text-base font-bold text-slate-900">
                                            <h3>
                                                <a href="{{ route('shop') }}">{{ $item['name'] }}</a>
                                            </h3>
                                            <p class="ml-4">₹{{ number_format($item['price'] * $item['quantity'], 2) }}</p>
                                        </div>
                                        @if(isset($item['attributes']['options']) && is_array($item['attributes']['options']))
                                            <div class="mt-1 flex flex-wrap gap-2 text-sm text-slate-500">
                                                @foreach($item['attributes']['options'] as $optName => $optValue)
                                                    <span class="bg-slate-100 px-2 py-0.5 rounded text-xs font-medium">{{ $optName }}: {{ $optValue }}</span>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex flex-1 items-end justify-between text-sm">
                                        <div class="flex items-center border border-slate-200 rounded-lg">
                                            <button wire:click="updateQuantity('{{ $item['id'] }}', 'decrease')" class="px-3 py-1 text-slate-500 hover:text-slate-900 hover:bg-slate-50 transition-colors rounded-l-lg focus:outline-none">-</button>
                                            <span class="px-3 py-1 font-bold text-slate-900 border-x border-slate-200">{{ $item['quantity'] }}</span>
                                            <button wire:click="updateQuantity('{{ $item['id'] }}', 'increase')" class="px-3 py-1 text-slate-500 hover:text-slate-900 hover:bg-slate-50 transition-colors rounded-r-lg focus:outline-none">+</button>
                                        </div>

                                        <div class="flex">
                                            <button wire:click="removeItem('{{ $item['id'] }}')" type="button" class="font-bold text-sky-600 hover:text-sky-500 flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                Remove
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
                
                <div class="flex justify-end">
                    <button wire:click="clearCart" class="text-sm font-bold text-slate-500 hover:text-red-600 transition-colors">
                        Clear Cart
                    </button>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="w-full lg:w-96 bg-slate-50 rounded-2xl p-6 sm:p-8 border border-slate-200 sticky top-24">
                <h2 class="text-lg font-black text-slate-900 mb-6">Order Summary</h2>

                <div class="flow-root">
                    <dl class="-my-4 text-sm divide-y divide-slate-200">
                        <div class="py-4 flex items-center justify-between">
                            <dt class="text-slate-600">Subtotal</dt>
                            <dd class="font-bold text-slate-900">₹{{ number_format($cartTotal, 2) }}</dd>
                        </div>
                        <div class="py-4 flex items-center justify-between">
                            <dt class="text-slate-600">Shipping</dt>
                            <dd class="font-bold text-emerald-600">Free</dd>
                        </div>
                        <div class="py-4 flex items-center justify-between">
                            <dt class="text-base font-black text-slate-900">Total</dt>
                            <dd class="text-base font-black text-slate-900">₹{{ number_format($cartTotal, 2) }}</dd>
                        </div>
                    </dl>
                </div>

                <div class="mt-8">
                    <a href="{{ route('checkout') }}" class="w-full inline-flex justify-center bg-slate-900 border border-transparent rounded-xl shadow-sm py-4 px-4 text-base font-black text-white hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-900 focus:ring-offset-slate-50 transition-colors">
                        Checkout
                    </a>
                </div>
                <div class="mt-4 text-center">
                    <a href="{{ route('shop') }}" class="text-sm font-bold text-sky-600 hover:text-sky-500 transition-colors">
                        or Continue Shopping &rarr;
                    </a>
                </div>
            </div>
        </div>
    @else
        <div class="text-center py-20 bg-white rounded-2xl border border-slate-200">
            <svg class="mx-auto h-24 w-24 text-slate-300 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
            </svg>
            <h3 class="text-xl font-black text-slate-900 mb-2">Your cart is empty</h3>
            <p class="text-slate-500 mb-8 max-w-sm mx-auto">Looks like you haven't added anything to your cart yet. Browse our products and find something you love!</p>
            <a href="{{ route('shop') }}" class="inline-flex items-center justify-center px-8 py-3 border border-transparent text-base font-black rounded-xl text-white bg-sky-600 hover:bg-sky-500 transition-colors">
                Start Shopping
            </a>
        </div>
    @endif
</div>
