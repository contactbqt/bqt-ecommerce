<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <flux:heading size="xl" level="1">Customer Management</flux:heading>
            <flux:subheading>View and monitor your customer base and their purchasing activity.</flux:subheading>
        </div>
    </div>

    <div class="flex flex-col md:flex-row gap-4">
        <div class="flex-1">
            <flux:input wire:model.live="search" icon="magnifying-glass" placeholder="Search customers by name or email..." />
        </div>
    </div>

    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-zinc-50 dark:bg-zinc-800/50 border-b border-zinc-200 dark:border-zinc-800">
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-zinc-500">Customer</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-zinc-500">Email</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-zinc-500 text-center">Total Orders</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-zinc-500 text-right">Total Purchase</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-zinc-500">Joined</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                    @forelse ($customers as $customer)
                        <tr class="hover:bg-zinc-50/50 dark:hover:bg-zinc-800/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-sky-100 text-sky-700 flex items-center justify-center font-bold text-xs uppercase">
                                        {{ $customer->initials() }}
                                    </div>
                                    <span class="font-medium text-zinc-900 dark:text-white">{{ $customer->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-zinc-600 dark:text-zinc-400">
                                {{ $customer->email }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center font-medium text-zinc-900 dark:text-white">
                                <flux:badge color="zinc" size="sm">{{ $customer->orders_count }}</flux:badge>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right font-bold text-sky-600">
                                ₹{{ number_format($customer->orders_sum_order_amount ?? 0, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-zinc-600 dark:text-zinc-400">
                                {{ $customer->created_at->format('M d, Y') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-12 text-zinc-500">
                                No customers found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-zinc-100 dark:border-zinc-800">
            {{ $customers->links() }}
        </div>
    </div>
</div>
