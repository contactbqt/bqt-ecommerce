<div class="relative mb-6 w-full">
    <flux:heading size="xl" level="1">{{ __('Product Attributes') }}</flux:heading>
    <flux:subheading size="lg" class="mb-6">{{ __('Manage product attributes.') }}
    </flux:subheading>
    <flux:separator variant="subtle" />


    <div class="p-6 bg-white rounded-xl shadow-md">
        <x-product-variant-navigation :product-id="$productId" :product="$product" active-step="attributes" />
        <div class="mt-6">
            <h3 class="text-lg font-semibold mb-4">Product Attributes</h3>
            <div class="bg-gray-50 p-6 rounded-lg shadow-sm">
                <!-- Product Info Header -->
                <x-product-info-header :product="$product" />

                <!-- Attributes Selection -->
                <div>
                    <h4 class="text-base font-semibold text-gray-800 mb-6 flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                        </svg>
                        Filter Attributes
                    </h4>
                    
                    <div class="space-y-6">
                        @forelse($attributeCategoryList as $item)
                            <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
                                <div class="flex items-center justify-between mb-4">
                                    <h5 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                                        {{ $item->attribute->attribute_name }}
                                    </h5>
                                    @php
                                        $categoryAttributeValues = $item->attributeValueCategories->map(fn($avc) => $avc->attributeValue)->filter();
                                    @endphp
                                    @if($product_type !== 'single' && $categoryAttributeValues->isNotEmpty())
                                        <label class="inline-flex items-center gap-2 cursor-pointer select-none">
                                            <input type="checkbox" 
                                                   wire:click="toggleSelectAll({{ $item->attribute->id }}, {{ json_encode($categoryAttributeValues->pluck('id')) }})"
                                                   @if(count(array_intersect($categoryAttributeValues->pluck('id')->toArray(), $selectedAttributes)) === $categoryAttributeValues->count()) checked @endif
                                                   class="w-4 h-4 text-blue-600 bg-white border-gray-300 rounded focus:ring-blue-500 focus:ring-2 transition-colors">
                                            <span class="text-xs font-medium text-gray-500 hover:text-gray-700">Select All</span>
                                        </label>
                                    @endif
                                </div>

                                @if($product_type === 'single')
                                    @include('livewire.admin.product.partials._attributes-single', ['item' => $item, 'categoryAttributeValues' => $categoryAttributeValues])
                                @else
                                    @include('livewire.admin.product.partials._attributes-variant', ['item' => $item, 'categoryAttributeValues' => $categoryAttributeValues])
                                @endif
                            </div>
                        @empty
                            <div class="text-center py-12 bg-white rounded-lg border-2 border-dashed border-gray-200">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                </svg>
                                <p class="mt-2 text-sm text-gray-500">No attributes available for this category.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
            
            <div class="flex mt-6 gap-3">
                <flux:spacer />
                <flux:button wire:click="saveProductAttributes(false)" variant="outline" class="px-6 shadow-sm" wire:loading.attr="disabled" wire:target="saveProductAttributes(false)">
                    <span wire:loading.remove wire:target="saveProductAttributes(false)">Save & Stay</span>
                    <span wire:loading wire:target="saveProductAttributes(false)">Saving...</span>
                </flux:button>
                <flux:button wire:click="saveProductAttributes(true)" variant="primary" color="sky" class="px-6 shadow-sm" wire:loading.attr="disabled" wire:target="saveProductAttributes(true)">
                    <span wire:loading.remove wire:target="saveProductAttributes(true)">Save & Continue</span>
                    <span wire:loading wire:target="saveProductAttributes(true)">Saving...</span>
                </flux:button>
            </div>
        </div>
    </div>
</div>
