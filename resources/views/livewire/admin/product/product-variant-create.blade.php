<div class="relative mb-6 w-full">
    <flux:heading size="xl" level="1">{{ __('Create Combinations') }}</flux:heading>
    <flux:subheading size="lg" class="mb-6">{{ __('Generate new variant combinations for this product.') }}</flux:subheading>
    <flux:separator variant="subtle" />



    <div class="p-6 bg-white rounded-xl shadow-md">
        <!-- Navigation Tabs -->
        <x-product-variant-navigation :product-id="$productId" :product="$product" active-step="combinations" />

        <div class="mt-6">
            <h3 class="text-lg font-semibold mb-4">Select Attributes for New Combinations</h3>
            
            <div class="bg-gray-50 p-6 rounded-lg shadow-sm">
                <!-- Product Summary -->
                <x-product-info-header :product="$product">
                    Select attributes to generate NEW unique combinations. Attributes already used in existing variants are disabled.
                </x-product-info-header>

                <!-- Attributes Selection -->
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
                                @if($categoryAttributeValues->isNotEmpty())
                                    <label class="inline-flex items-center gap-2 cursor-pointer select-none">
                                        <input type="checkbox" 
                                               wire:click="toggleSelectAll({{ $item->attribute->id }}, {{ json_encode($categoryAttributeValues->pluck('id')) }})"
                                               class="w-4 h-4 text-blue-600 bg-white border-gray-300 rounded focus:ring-blue-500 focus:ring-2 transition-colors">
                                        <span class="text-xs font-medium text-gray-500 hover:text-gray-700">Select All Available</span>
                                    </label>
                                @endif
                            </div>
                            <div class="flex flex-wrap gap-3">
                                @if($categoryAttributeValues->isNotEmpty())
                                    @foreach($categoryAttributeValues as $valueItem)
                                        @php
                                            $isUsed = in_array($valueItem->id, $usedAttributeValues);
                                        @endphp
                                        <label wire:key="attr-val-{{ $valueItem->id }}" class="inline-flex items-center gap-2 px-3 py-2 border rounded-lg transition-all {{ $isUsed ? 'bg-gray-100 border-gray-200 opacity-60 cursor-not-allowed' : 'bg-gray-50 border-gray-200 cursor-pointer hover:bg-gray-100 hover:border-gray-300' }}">
                                            <input type="checkbox" 
                                                   wire:model.live="selectedVariantAttributes"
                                                   value="{{ $valueItem->id }}"
                                                   {{ $isUsed ? 'disabled checked' : '' }}
                                                   class="w-4 h-4 text-blue-600 bg-white border-gray-300 rounded focus:ring-blue-500 focus:ring-2 transition-colors">
                                            <span class="text-sm text-gray-700">
                                                {{ $valueItem->value_name }}
                                                @if($isUsed) <span class="text-xs text-gray-500 font-semibold">(Used)</span> @endif
                                            </span>
                                        </label>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-6 text-gray-500">No variant attributes found.</div>
                    @endforelse
                </div>
            </div>
            
            <div class="flex mt-6">
                <flux:spacer />
                <flux:button wire:click="generateCombinations" variant="primary" color="sky" wire:loading.attr="disabled" wire:target="generateCombinations">
                    <span wire:loading.remove wire:target="generateCombinations">Generate & Save Combinations</span>
                    <span wire:loading wire:target="generateCombinations">Processing...</span>
                </flux:button>
            </div>
        </div>
    </div>
</div>
