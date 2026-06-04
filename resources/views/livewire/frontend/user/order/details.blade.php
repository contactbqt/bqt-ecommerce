<section class="w-full">
    <div class="relative mb-6 w-full flex items-center justify-between">
        <div>
            <div class="flex items-center gap-3">
                <a href="{{ route('user.orders') }}" class="w-8 h-8 flex items-center justify-center rounded-full bg-slate-100 text-slate-500 hover:bg-slate-200 hover:text-slate-900 transition-colors">
                    <flux:icon name="arrow-left" class="size-4" />
                </a>
                <flux:heading size="xl" level="1">Order Details</flux:heading>
            </div>
            <flux:subheading size="lg" class="mt-2 mb-6">#{{ $order->order_number }} • {{ \Carbon\Carbon::parse($order->order_date)->format('F d, Y - h:i A') }}</flux:subheading>
        </div>
        
        <div class="hidden sm:block">
            @php
                $status = strtolower($order->status);
                $statusConfig = match($status) {
                    'delivered' => ['color' => 'emerald', 'icon' => 'check-circle', 'text' => 'Delivered'],
                    'shipped' => ['color' => 'indigo', 'icon' => 'truck', 'text' => 'Shipped'],
                    'cancelled' => ['color' => 'red', 'icon' => 'x-circle', 'text' => 'Cancelled'],
                    'pending' => ['color' => 'amber', 'icon' => 'clock', 'text' => 'Pending'],
                    'processing' => ['color' => 'sky', 'icon' => 'arrow-path', 'text' => 'Processing'],
                    default => ['color' => 'slate', 'icon' => 'information-circle', 'text' => ucfirst($order->status)]
                };
            @endphp
            <span class="inline-flex items-center gap-2 px-4 py-2 bg-{{ $statusConfig['color'] }}-50 text-{{ $statusConfig['color'] }}-700 text-sm font-black rounded-xl border border-{{ $statusConfig['color'] }}-100 uppercase tracking-widest">
                <flux:icon name="{{ $statusConfig['icon'] }}" class="size-4" /> {{ $statusConfig['text'] }}
            </span>
        </div>
    </div>
    
    <flux:separator variant="subtle" class="mb-6" />

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content: Items -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
                <h2 class="text-lg font-black text-slate-900 mb-6 uppercase tracking-tighter">Items Ordered</h2>
                
                <div class="space-y-6">
                    @foreach($order->order_details as $detail)
                        <div class="flex gap-4 items-start {{ !$loop->last ? 'pb-6 border-b border-slate-100' : '' }}">
                            <div class="w-20 h-20 bg-slate-50 rounded-xl border border-slate-100 flex-shrink-0 overflow-hidden flex items-center justify-center">
                                @php
                                    $itemImage = null;
                                    $itemName = 'Unknown Product';
                                    
                                    if ($detail->product_variant_id && $detail->product_varient) {
                                        $itemImage = $detail->product_varient->product->image ?? null;
                                        $itemName = $detail->product_varient->product_name ?? ($detail->product_varient->product->product_name ?? 'Unknown Product');
                                    } elseif ($detail->product) {
                                        $itemImage = $detail->product->image ?? null;
                                        $itemName = $detail->product->product_name ?? 'Unknown Product';
                                    }
                                @endphp

                                @if($itemImage)
                                    <img src="{{ asset('storage/' . $itemImage) }}" alt="{{ $itemName }}" class="w-full h-full object-cover">
                                @else
                                    <flux:icon name="photo" class="size-8 text-slate-300" />
                                @endif
                            </div>
                            
                            <div class="flex-1 min-w-0">
                                <h3 class="text-base font-bold text-slate-900 truncate">
                                    {{ $itemName }}
                                </h3>
                                @if($detail->product_variant_id && $detail->product_varient && $detail->product_varient->attributes)
                                    <p class="text-xs text-slate-500 mt-1">
                                        @foreach($detail->product_varient->attributes as $attr)
                                            @if($attr->attributeValue && $attr->attributeValue->attributes)
                                                <span class="inline-block bg-slate-100 px-2 py-0.5 rounded text-slate-600 mr-1 border border-slate-200">
                                                    <span class="font-bold">{{ $attr->attributeValue->attributes->attribute_name }}:</span> {{ $attr->attributeValue->value_name }}
                                                </span>
                                            @endif
                                        @endforeach
                                    </p>
                                @endif
                                <div class="mt-2 flex items-center justify-between">
                                    <p class="text-sm font-medium text-slate-600">Qty: <span class="font-black text-slate-900">{{ $detail->quantity }}</span></p>
                                    <p class="text-sm font-black text-slate-900">₹{{ number_format($detail->line_total, 2) }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Order Summary -->
            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
                <h2 class="text-lg font-black text-slate-900 mb-6 uppercase tracking-tighter">Order Summary</h2>
                
                <div class="space-y-4 text-sm">
                    <div class="flex justify-between items-center text-slate-600">
                        <span>Subtotal</span>
                        <span class="font-bold text-slate-900">₹{{ number_format($order->order_amount - $order->shipping_amount - $order->tax_amount, 2) }}</span>
                    </div>
                    <div class="flex justify-between items-center text-slate-600">
                        <span>Shipping</span>
                        <span class="font-bold text-slate-900">{{ $order->shipping_amount > 0 ? '₹'.number_format($order->shipping_amount, 2) : 'Free' }}</span>
                    </div>
                    <div class="flex justify-between items-center text-slate-600">
                        <span>Tax</span>
                        <span class="font-bold text-slate-900">₹{{ number_format($order->tax_amount, 2) }}</span>
                    </div>
                    <div class="pt-4 border-t border-slate-100 flex justify-between items-center">
                        <span class="text-base font-black text-slate-900 uppercase tracking-widest">Total</span>
                        <span class="text-2xl font-black text-sky-600">₹{{ number_format($order->order_amount, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar: Details -->
        <div class="space-y-6">
            <!-- Shipping Info -->
            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-full bg-indigo-50 flex items-center justify-center">
                        <flux:icon name="map-pin" class="size-5 text-indigo-600" />
                    </div>
                    <h2 class="text-sm font-black text-slate-900 uppercase tracking-widest">Shipping Address</h2>
                </div>
                <div class="pl-13 text-sm text-slate-600 space-y-1">
                    <p class="font-bold text-slate-900">{{ $order->shipping_name }}</p>
                    <p>{{ $order->shipping_address1 }}</p>
                    @if($order->shipping_address2) <p>{{ $order->shipping_address2 }}</p> @endif
                    <p>{{ $order->shipping_city }}, {{ $order->shipping_state }} {{ $order->shipping_pincode }}</p>
                    <p>{{ $order->shipping_country }}</p>
                    @if($order->shipping_phone)
                        <p class="pt-2 font-medium"><flux:icon name="phone" class="size-3 inline-block mr-1" /> {{ $order->shipping_phone }}</p>
                    @endif
                </div>
            </div>

            <!-- Billing Info -->
            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-full bg-emerald-50 flex items-center justify-center">
                        <flux:icon name="document-text" class="size-5 text-emerald-600" />
                    </div>
                    <h2 class="text-sm font-black text-slate-900 uppercase tracking-widest">Billing Address</h2>
                </div>
                <div class="pl-13 text-sm text-slate-600 space-y-1">
                    <p class="font-bold text-slate-900">{{ $order->billing_name }}</p>
                    <p>{{ $order->billing_address1 }}</p>
                    @if($order->billing_address2) <p>{{ $order->billing_address2 }}</p> @endif
                    <p>{{ $order->billing_city }}, {{ $order->billing_state }} {{ $order->billing_pincode }}</p>
                    <p>{{ $order->billing_country }}</p>
                </div>
            </div>

            <!-- Payment Info -->
            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-full bg-sky-50 flex items-center justify-center">
                        <flux:icon name="credit-card" class="size-5 text-sky-600" />
                    </div>
                    <h2 class="text-sm font-black text-slate-900 uppercase tracking-widest">Payment Details</h2>
                </div>
                <div class="pl-13 text-sm">
                    <p class="font-bold text-slate-900 uppercase tracking-widest text-[11px] mb-1">Method</p>
                    <p class="text-slate-600 font-medium mb-4">{{ $order->payment_mode === 'COD' ? 'Cash on Delivery' : 'Online Payment' }}</p>
                    
                    @if($order->txn_details)
                        <p class="font-bold text-slate-900 uppercase tracking-widest text-[11px] mb-1">Transaction ID</p>
                        <p class="text-slate-600 font-mono text-xs">{{ $order->txn_details }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>