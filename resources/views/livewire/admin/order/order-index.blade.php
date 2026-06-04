<div class="space-y-6">
    <div>
        <flux:heading size="xl" level="1">Order Management</flux:heading>
        <flux:subheading>Track and manage your customer orders.</flux:subheading>
    </div>

    <div class="flex flex-col md:flex-row gap-4">
        <div class="flex-1">
            <flux:input wire:model.live="search" icon="magnifying-glass" placeholder="Search by order # or customer name..." />
        </div>
        <div class="w-full md:w-48">
            <flux:select wire:model.live="status" placeholder="All Statuses">
                <flux:select.option value="">All Statuses</flux:select.option>
                <flux:select.option value="pending">Pending</flux:select.option>
                <flux:select.option value="processing">Processing</flux:select.option>
                <flux:select.option value="shipped">Shipped</flux:select.option>
                <flux:select.option value="delivered">Delivered</flux:select.option>
                <flux:select.option value="cancelled">Cancelled</flux:select.option>
            </flux:select>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-xl text-sm font-medium">
            {{ session('message') }}
        </div>
    @endif

    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-zinc-50 dark:bg-zinc-800/50 border-b border-zinc-200 dark:border-zinc-800">
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-zinc-500">Order #</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-zinc-500">Customer</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-zinc-500">Date</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-zinc-500">Total</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-zinc-500">Status</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-zinc-500 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                    @forelse ($orders as $order)
                        <tr class="hover:bg-zinc-50/50 dark:hover:bg-zinc-800/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="font-bold text-sky-600">#{{ $order->order_number }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <span class="font-medium text-zinc-900 dark:text-white">{{ $order->billing_name }}</span>
                                    <span class="text-xs text-zinc-500">{{ $order->user_details->email ?? '' }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-zinc-600 dark:text-zinc-400">
                                {{ $order->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap font-bold text-zinc-900 dark:text-white">
                                ₹{{ number_format($order->order_amount, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $color = match(strtolower($order->status)) {
                                        'pending' => 'amber',
                                        'processing' => 'sky',
                                        'shipped' => 'indigo',
                                        'delivered' => 'emerald',
                                        'cancelled' => 'red',
                                        default => 'zinc'
                                    };
                                @endphp
                                <flux:badge :color="$color" size="sm" inset="top bottom">
                                    {{ ucfirst($order->status) }}
                                </flux:badge>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <div class="flex justify-end gap-2">
                                    <flux:button variant="ghost" icon="eye" size="sm" :href="route('admin.order.details', $order->id)" wire:navigate />
                                    
                                    <flux:dropdown>
                                        <flux:button variant="ghost" icon="ellipsis-horizontal" size="sm" />
                                        <flux:menu>
                                            <flux:menu.item wire:click="updateStatus({{ $order->id }}, 'pending')">Set to Pending</flux:menu.item>
                                            <flux:menu.item wire:click="updateStatus({{ $order->id }}, 'processing')">Set to Processing</flux:menu.item>
                                            <flux:menu.item wire:click="updateStatus({{ $order->id }}, 'shipped')">Set to Shipped</flux:menu.item>
                                            <flux:menu.item wire:click="updateStatus({{ $order->id }}, 'delivered')">Set to Delivered</flux:menu.item>
                                            <flux:menu.item wire:click="updateStatus({{ $order->id }}, 'cancelled')" variant="danger">Cancel Order</flux:menu.item>
                                        </flux:menu>
                                    </flux:dropdown>
    
                                    <flux:button variant="ghost" icon="trash" size="sm" wire:click="delete({{ $order->id }})" wire:confirm="Are you sure you want to delete this order?" />
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-12 text-zinc-500">
                                No orders found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-zinc-100 dark:border-zinc-800">
            {{ $orders->links() }}
        </div>
    </div>
</div>
