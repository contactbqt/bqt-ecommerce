<div class="relative mb-6 w-full">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <flux:heading size="xl" level="1">{{ $category->category_name }}</flux:heading>
            <flux:subheading size="lg">Manage attributes associated with this category</flux:subheading>
        </div>
        <flux:button href="{{ route('admin.category.index') }}" variant="ghost" icon="arrow-left" wire:navigate>Back to Categories</flux:button>
    </div>

    <flux:separator variant="subtle" class="mb-10" />

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-8">
            <div class="flex items-center mb-8">
                <div class="bg-blue-50 p-2.5 rounded-xl mr-4">
                    <flux:icon.tag class="w-6 h-6 text-blue-500" />
                </div>
                <h3 class="text-xl font-bold text-slate-800 tracking-tight">Tag Attributes</h3>
            </div>

            <p class="text-sm text-slate-500 mb-8 max-w-2xl font-medium leading-relaxed">
                Select the available attributes for your products in the <span class="text-blue-600 font-bold underline decoration-blue-100 uppercase tracking-widest text-[10px]">{{ $category->category_name }}</span> category. This helps in defining product variants and filtering options on the storefront.
            </p>

            @if (session()->has('message'))
                <div class="mb-8 bg-emerald-50 border-emerald-200 text-emerald-800 p-4 rounded-xl border flex items-center shadow-sm animate-fade-in">
                    <flux:icon.check-circle class="w-5 h-5 mr-3 text-emerald-500" />
                    <span class="font-bold text-sm">{{ session('message') }}</span>
                </div>
            @endif

            <div class="space-y-12">
                <!-- Filter Attributes Section -->
                <div>
                    <div class="flex items-center gap-2 mb-4 pb-2 border-b border-slate-100">
                        <flux:icon.funnel class="w-4 h-4 text-slate-400" />
                        <h4 class="text-sm font-black uppercase tracking-widest text-slate-600">Filter Attributes</h4>
                        <span class="text-[10px] bg-slate-100 text-slate-500 px-2 py-0.5 rounded-full font-bold ml-2">{{ $allAttributes->where('is_filter', 1)->count() }}</span>
                    </div>
                    
                    <div class="space-y-1">
                        @forelse ($allAttributes->where('is_filter', 1) as $index => $attribute)
                            <div class="border-b border-gray-100 last:border-0 overflow-hidden">
                                <div class="flex items-center justify-between p-3 hover:bg-gray-50 transition-colors {{ in_array($attribute->id, $selectedFilterAttributes) ? 'bg-sky-50/30' : '' }}">
                                    <!-- Left Side: Serial, Checkbox & Name -->
                                    <div class="flex items-center gap-3">
                                        <span class="text-xs text-gray-400 font-mono w-4 pb-0.5">{{ $loop->iteration }}.</span>
                                        <input 
                                            type="checkbox" 
                                            wire:model.live="selectedFilterAttributes" 
                                            value="{{ $attribute->id }}" 
                                            class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                        />
                                        <span class="text-sm text-gray-700 font-bold">{{ $attribute->attribute_name }}</span>
                                    </div>
                                    
                                    <!-- Right Side: Dropdown -->
                                    <div class="flex items-center gap-3">
                                        <label class="text-xs text-gray-500 font-medium">Display type:</label>
                                        <select 
                                            wire:model="attributeDisplayTypes.{{ $attribute->id }}" 
                                            class="text-sm border-gray-200 rounded px-2 py-1 bg-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none"
                                        >
                                            <option value="text">Text</option>
                                            <option value="color">Color</option>
                                            <option value="image">Image</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Nested Values -->
                                @if (in_array((string)$attribute->id, $selectedFilterAttributes) || in_array($attribute->id, $selectedFilterAttributes))
                                    @if ($attribute->attribute_values->isNotEmpty())
                                        <div class="pl-[3.25rem] pr-6 pb-5 pt-2 animate-fade-in text-left">
                                            <div class="px-6 py-6 bg-slate-50/50 border border-slate-200 shadow-inner rounded-xl flex flex-col justify-start">
                                                <div class="flex items-center justify-between mb-5 border-b border-slate-200 pb-3">
                                                    <div class="text-[11px] font-black uppercase tracking-widest text-slate-500">Select Accessible Values</div>
                                                </div>
                                                <div class="grid gap-3 md:gap-4" style="grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));">
                                                    @foreach($attribute->attribute_values as $value)
                                                        <label class="flex items-center gap-3 p-3 lg:px-4 bg-white cursor-pointer group rounded-lg border border-slate-200 hover:border-sky-300 hover:shadow-sm transition-all {{ in_array((string)$value->id, $selectedFilterAttributeValues) ? 'border-sky-300 bg-sky-50/20' : '' }}">
                                                            <input 
                                                                type="checkbox" 
                                                                wire:model="selectedFilterAttributeValues" 
                                                                value="{{ $value->id }}" 
                                                                class="w-4 h-4 flex-none rounded border-slate-300 text-sky-500 focus:ring-sky-500 transition-colors"
                                                            />
                                                            <span class="text-sm text-slate-700 font-semibold group-hover:text-slate-900 transition-colors truncate" title="{{ $value->value_name }}">{{ $value->value_name }}</span>
                                                        </label>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="pl-12 pr-6 pb-4 pt-1 animate-fade-in text-xs text-slate-400 italic">
                                            No values found for this attribute.
                                        </div>
                                    @endif
                                @endif
                            </div>
                        @empty
                            <div class="p-4 text-center text-slate-400 text-xs italic">No filter attributes available</div>
                        @endforelse
                    </div>
                </div>

                <!-- Variant Attributes Section -->
                <div>
                    <div class="flex items-center gap-2 mb-4 pb-2 border-b border-slate-100">
                        <flux:icon.swatch class="w-4 h-4 text-slate-400" />
                        <h4 class="text-sm font-black uppercase tracking-widest text-slate-600">Variant Attributes</h4>
                        <span class="text-[10px] bg-slate-100 text-slate-500 px-2 py-0.5 rounded-full font-bold ml-2">{{ $allAttributes->where('is_variant', 1)->count() }}</span>
                    </div>

                    <div class="space-y-1">
                        @forelse ($allAttributes->where('is_variant', 1) as $index => $attribute)
                            <div class="border-b border-gray-100 last:border-0 overflow-hidden">
                                <div class="flex items-center justify-between p-3 hover:bg-gray-50 transition-colors {{ in_array($attribute->id, $selectedVariantAttributes) ? 'bg-sky-50/30' : '' }}">
                                    <!-- Left Side: Serial, Checkbox & Name -->
                                    <div class="flex items-center gap-3">
                                        <span class="text-xs text-gray-400 font-mono w-4 pb-0.5">{{ $loop->iteration }}.</span>
                                        <input 
                                            type="checkbox" 
                                            wire:model.live="selectedVariantAttributes" 
                                            value="{{ $attribute->id }}" 
                                            class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                        />
                                        <span class="text-sm text-gray-700 font-bold">{{ $attribute->attribute_name }}</span>
                                    </div>
                                    
                                    <!-- Right Side: Dropdown -->
                                    <div class="flex items-center gap-3">
                                        <label class="text-xs text-gray-500 font-medium">Display type:</label>
                                        <select 
                                            wire:model="attributeDisplayTypes.{{ $attribute->id }}" 
                                            class="text-sm border-gray-200 rounded px-2 py-1 bg-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none"
                                        >
                                            <option value="text">Text</option>
                                            <option value="color">Color</option>
                                            <option value="image">Image</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Nested Values -->
                                @if (in_array((string)$attribute->id, $selectedVariantAttributes) || in_array($attribute->id, $selectedVariantAttributes))
                                    @if ($attribute->attribute_values->isNotEmpty())
                                        <div class="pl-[3.25rem] pr-6 pb-5 pt-2 animate-fade-in text-left">
                                            <div class="px-6 py-6 bg-slate-50/50 border border-slate-200 shadow-inner rounded-xl flex flex-col justify-start">
                                                <div class="flex items-center justify-between mb-5 border-b border-slate-200 pb-3">
                                                    <div class="text-[11px] font-black uppercase tracking-widest text-slate-500">Select Accessible Values</div>
                                                </div>
                                                <div class="grid gap-3 md:gap-4" style="grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));">
                                                    @foreach($attribute->attribute_values as $value)
                                                        <label class="flex items-center gap-3 p-3 lg:px-4 bg-white cursor-pointer group rounded-lg border border-slate-200 hover:border-sky-300 hover:shadow-sm transition-all {{ in_array((string)$value->id, $selectedVariantAttributeValues) ? 'border-sky-300 bg-sky-50/20' : '' }}">
                                                            <input 
                                                                type="checkbox" 
                                                                wire:model="selectedVariantAttributeValues" 
                                                                value="{{ $value->id }}" 
                                                                class="w-4 h-4 flex-none rounded border-slate-300 text-sky-500 focus:ring-sky-500 transition-colors"
                                                            />
                                                            <span class="text-sm text-slate-700 font-semibold group-hover:text-slate-900 transition-colors truncate" title="{{ $value->value_name }}">{{ $value->value_name }}</span>
                                                        </label>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="pl-12 pr-6 pb-4 pt-1 animate-fade-in text-xs text-slate-400 italic">
                                            No values found for this attribute.
                                        </div>
                                    @endif
                                @endif
                            </div>
                        @empty
                            <div class="p-4 text-center text-slate-400 text-xs italic">No variant attributes available</div>
                        @endforelse
                    </div>
                </div>
            </div>

            @if($allAttributes->count() === 0)
                <div class="py-20 text-center bg-slate-50 rounded-3xl border-2 border-dashed border-slate-200">
                    <flux:icon.tag class="mx-auto h-12 w-12 text-slate-200 mb-4" />
                    <p class="text-slate-500 font-bold">No attributes found in the system</p>
                    <flux:button variant="ghost" icon="plus" color="sky" class="mt-4" href="{{ route('admin.attribute.index') }}" wire:navigate>Create First Attribute</flux:button>
                </div>
            @endif
        </div>

        <div class="bg-slate-50/80 px-8 py-5 border-t border-slate-200 flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Total Selection:</span>
                <span class="text-[10px] font-black bg-blue-500 text-white px-2 py-0.5 rounded-full">{{ count($selectedFilterAttributes) + count($selectedVariantAttributes) }}</span>
            </div>
            
            <flux:button wire:click="save" variant="primary" color="sky" size="sm" class="px-12 shadow-lg shadow-sky-100">
                <span wire:loading.remove wire:target="save">Save Association</span>
                <span wire:loading wire:target="save">Updating...</span>
            </flux:button>
        </div>
    </div>
</div>
