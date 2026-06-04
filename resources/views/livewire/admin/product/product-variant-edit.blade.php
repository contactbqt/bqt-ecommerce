<div class="relative mb-6 w-full">
    <flux:heading size="xl" level="1">{{ __('Manage Variants & Pricing') }}</flux:heading>
    <flux:subheading size="lg" class="mb-6">{{ __('Update price, stock, and status for existing variants.') }}</flux:subheading>
    <flux:separator variant="subtle" />



    <div class="p-6 bg-white rounded-xl shadow-md">
        <!-- Navigation Tabs -->
        <div class="relative z-20">
            <x-product-variant-navigation :product-id="$productId" :product="$product" active-step="variants" />
        </div>

        <div class="mt-6">
            <x-product-info-header :product="$product" />
            <div class="flex items-center justify-between mb-6">
                <h4 class="text-lg font-bold text-gray-900">Existing Variants</h4>
                <flux:button href="{{ route('admin.product.variants.create', $productId) }}" variant="ghost" size="sm" icon="plus">
                    Add New Combination
                </flux:button>
            </div>

            <div class="overflow-x-auto border border-gray-200 rounded-lg min-h-[400px]">
                <table class="min-w-full divide-y divide-gray-200 bg-white">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Variant Name</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product Name</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Offer Price</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKU</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($variantInputs as $id => $variant)
                            <tr wire:key="variant-{{ $id }}">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $variant['name'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <flux:input type="text" wire:model="variantInputs.{{ $id }}.product_name" placeholder="Optional" />
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <flux:input type="number" wire:model="variantInputs.{{ $id }}.price" step="0.01" />
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <flux:input type="number" wire:model="variantInputs.{{ $id }}.offer_price" step="0.01" />
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <flux:input type="text" wire:model="variantInputs.{{ $id }}.sku_code" />
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <flux:input type="number" wire:model="variantInputs.{{ $id }}.stock_qty" />
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <flux:dropdown>
                                        <flux:button variant="ghost" size="sm" icon-trailing="chevron-down" 
                                            class="{{ ($variantInputs[$id]['status'] ?? 0) == 1 ? 'text-emerald-700 bg-emerald-50 border-emerald-200' : 'text-amber-700 bg-amber-50 border-amber-200' }}">
                                            {{ ($variantInputs[$id]['status'] ?? 0) == 1 ? 'Active' : 'Inactive' }}
                                        </flux:button>

                                        <flux:menu class="min-w-[100px]">
                                            <flux:menu.item wire:click="$set('variantInputs.{{ $id }}.status', 1)">
                                                <div class="flex items-center gap-2">
                                                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                                    Active
                                                </div>
                                            </flux:menu.item>
                                            <flux:menu.item wire:click="$set('variantInputs.{{ $id }}.status', 0)">
                                                <div class="flex items-center gap-2">
                                                    <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                                                    Inactive
                                                </div>
                                            </flux:menu.item>
                                        </flux:menu>
                                    </flux:dropdown>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <flux:button wire:click="deleteVariant({{ $id }})" variant="danger" size="xs" icon="trash" wire:confirm="Are you sure you want to delete this variant?"></flux:button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">
                                    No variants found. Click "Create Combinations" to generate some.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="flex mt-6">
                <flux:spacer />
                <flux:button wire:click="updateVariants" variant="primary" color="sky" wire:loading.attr="disabled" wire:target="updateVariants">
                    <span wire:loading.remove wire:target="updateVariants">Update All Variants</span>
                    <span wire:loading wire:target="updateVariants">Saving...</span>
                </flux:button>
            </div>
        </div>
    </div>
</div>
