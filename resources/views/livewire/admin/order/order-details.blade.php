<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <flux:button variant="ghost" icon="arrow-left" :href="route('admin.order.index')" wire:navigate />
            <div>
                <flux:heading size="xl" level="1">Order #{{ $order->order_number }}</flux:heading>
                <flux:subheading>{{ $order->created_at->format('F d, Y at h:i A') }}</flux:subheading>
            </div>
        </div>
        <div class="flex gap-3">
            <flux:dropdown>
                <flux:button icon-trailing="chevron-down">
                    {{ ucfirst($order->status) }}
                </flux:button>
                <flux:menu>
                    <flux:menu.item wire:click="updateStatus('pending')">Pending</flux:menu.item>
                    <flux:menu.item wire:click="updateStatus('processing')">Processing</flux:menu.item>
                    <flux:menu.item wire:click="updateStatus('shipped')">Shipped</flux:menu.item>
                    <flux:menu.item wire:click="updateStatus('delivered')">Delivered</flux:menu.item>
                    <flux:menu.item wire:click="updateStatus('cancelled')" variant="danger">Cancelled</flux:menu.item>
                </flux:menu>
            </flux:dropdown>
            <flux:button variant="primary" icon="printer">Print Invoice</flux:button>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-xl text-sm font-medium">
            {{ session('message') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <!-- Items -->
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl overflow-hidden shadow-sm">
                <div class="p-4 border-b border-zinc-100 dark:border-zinc-800 bg-zinc-50/50 dark:bg-zinc-800/50">
                    <flux:heading size="sm">Order Items</flux:heading>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-zinc-50 dark:bg-zinc-800/50 border-b border-zinc-200 dark:border-zinc-800 text-xs font-bold uppercase tracking-wider text-zinc-500">
                                <th class="px-6 py-4">Product</th>
                                <th class="px-6 py-4">Price</th>
                                <th class="px-6 py-4">Qty</th>
                                <th class="px-6 py-4 text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                            @foreach ($order->order_details as $item)
                                <tr class="hover:bg-zinc-50/50 dark:hover:bg-zinc-800/50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-lg bg-zinc-100 overflow-hidden shrink-0">
                                            @php
                                                 $productImage = null;
                                                 if ($item->product_variant && $item->product_variant->productImages->first()) {
                                                     $productImage = $item->product_variant->productImages->first()->image_name;
                                                 } elseif ($item->product) {
                                                     if ($item->product->image) {
                                                         $productImage = $item->product->image;
                                                     } elseif ($item->product->product_images->first()) {
                                                         $productImage = $item->product->product_images->first()->image_name;
                                                     }
                                                 }
                                             @endphp
                                            @if($productImage)
                                                <img src="{{ asset('storage/' . $productImage) }}" class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-zinc-400">
                                                    <flux:icon name="photo" size="sm" />
                                                </div>
                                            @endif
                                        </div>
                                            <div class="flex flex-col">
                                                <span class="font-medium text-zinc-900 dark:text-white">{{ $item->product->product_name ?? 'N/A' }}</span>
                                                @if($item->product_variant)
                                                    <span class="text-xs text-zinc-500">Variant: {{ $item->product_variant->variant_name }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-zinc-600 dark:text-zinc-400">
                                        ₹{{ number_format($item->price, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-zinc-600 dark:text-zinc-400">
                                        {{ $item->quantity }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right font-bold text-zinc-900 dark:text-white">
                                        ₹{{ number_format($item->line_total, 2) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="p-6 border-t border-zinc-100 dark:border-zinc-800 space-y-2">
                    <div class="flex justify-end gap-8 text-sm">
                        <span class="text-zinc-500">Subtotal:</span>
                        <span class="font-medium w-24 text-right">₹{{ number_format($order->order_amount - $order->shipping_amount - $order->tax_amount, 2) }}</span>
                    </div>
                    <div class="flex justify-end gap-8 text-sm">
                        <span class="text-zinc-500">Shipping:</span>
                        <span class="font-medium w-24 text-right">₹{{ number_format($order->shipping_amount, 2) }}</span>
                    </div>
                    <div class="flex justify-end gap-8 text-sm">
                        <span class="text-zinc-500">Tax:</span>
                        <span class="font-medium w-24 text-right">₹{{ number_format($order->tax_amount, 2) }}</span>
                    </div>
                    <div class="flex justify-end gap-8 text-lg font-bold pt-2 border-t border-zinc-100 dark:border-zinc-800">
                        <span>Total:</span>
                        <span class="w-24 text-right text-sky-600">₹{{ number_format($order->order_amount, 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Payment Info -->
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl p-6 shadow-sm space-y-4">
                <flux:heading size="sm">Payment Information</flux:heading>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-zinc-500">Method</p>
                        <p class="font-medium uppercase">{{ $order->payment_mode }}</p>
                    </div>
                    <div>
                        <p class="text-zinc-500">Status</p>
                        <flux:badge color="emerald" size="sm">Paid</flux:badge>
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <!-- Customer Details -->
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl p-6 shadow-sm space-y-4">
                <flux:heading size="sm">Customer</flux:heading>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-zinc-100 flex items-center justify-center font-bold text-sm uppercase text-zinc-700">
                        {{ $order->user_details->initials() ?? 'C' }}
                    </div>
                    <div class="flex flex-col">
                        <span class="font-medium text-zinc-900 dark:text-white">{{ $order->billing_name }}</span>
                        <span class="text-xs text-zinc-500">{{ $order->user_details->email ?? '' }}</span>
                    </div>
                </div>
                <div class="pt-2 border-t border-zinc-100 dark:border-zinc-800">
                    <p class="text-xs text-zinc-500 mb-1">Phone</p>
                    <p class="text-sm font-medium">{{ $order->shipping_phone }}</p>
                </div>
            </div>

            <!-- Shipping Address -->
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl p-6 shadow-sm space-y-4">
                <flux:heading size="sm">Shipping Address</flux:heading>
                <div class="text-sm space-y-1 text-zinc-700 dark:text-zinc-300">
                    <p class="font-bold text-zinc-900 dark:text-white">{{ $order->shipping_name }}</p>
                    <p>{{ $order->shipping_address1 }}</p>
                    @if($order->shipping_address2)
                        <p>{{ $order->shipping_address2 }}</p>
                    @endif
                    <p>{{ $order->shipping_city }}, {{ $order->shipping_state }} - {{ $order->shipping_pincode }}</p>
                    <p>{{ $order->shipping_country }}</p>
                </div>
            </div>

            <!-- Billing Address -->
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl p-6 shadow-sm space-y-4">
                <flux:heading size="sm">Billing Address</flux:heading>
                <div class="text-sm space-y-1 text-zinc-700 dark:text-zinc-300">
                    <p class="font-bold text-zinc-900 dark:text-white">{{ $order->billing_name }}</p>
                    <p>{{ $order->billing_address1 }}</p>
                    @if($order->billing_address2)
                        <p>{{ $order->billing_address2 }}</p>
                    @endif
                    <p>{{ $order->billing_city }}, {{ $order->billing_state }} - {{ $order->billing_pincode }}</p>
                    <p>{{ $order->billing_country }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
