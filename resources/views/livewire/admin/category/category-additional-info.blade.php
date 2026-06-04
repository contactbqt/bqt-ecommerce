<div class="relative mb-6 w-full">
    <div class="flex items-center justify-between mb-6">
        <div>
            <flux:heading size="xl" level="1">{{ __('Product Details Template') }}</flux:heading>
            <flux:subheading size="lg">Category: <span class="font-bold text-blue-600 italic">{{ $category->category_name }}</span></flux:subheading>
        </div>
        <flux:button href="{{ route('admin.category.index') }}" variant="ghost" icon="chevron-left">Back to Categories</flux:button>
    </div>

    <flux:separator variant="subtle" class="mb-8" />

    @if (session()->has('message'))
        <div class="mb-6 bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-r-lg shadow-sm flex items-center">
            <svg class="w-5 h-5 text-emerald-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <p class="text-emerald-800 font-medium">{{ session('message') }}</p>
        </div>
    @endif

    <div class="space-y-8">
        @foreach($sections as $sIdx => $section)
            <div class="p-8 bg-white rounded-xl shadow-md border border-gray-200 relative group">
                <div class="flex items-start justify-between mb-8">
                    <div class="flex-1 max-w-2xl">
                        <flux:input 
                            wire:model="sections.{{ $sIdx }}.title" 
                            label="SECTION TITLE" 
                            badge="Required"
                            placeholder="e.g., Fabric & CARE, Size & Fit, Product Highlights" 
                            class="font-semibold"
                        />
                    </div>
                    <flux:button 
                        wire:click="removeSection({{ $sIdx }})" 
                        variant="ghost" 
                        color="red" 
                        icon="trash" 
                        size="sm" 
                        class="ml-4 opacity-0 group-hover:opacity-100 transition-opacity" 
                        title="Remove Section"
                    />
                </div>

                <div class="space-y-4">
                    <div class="flex items-center justify-between mb-4">
                        <flux:label class="uppercase tracking-wider font-bold text-gray-400 text-xs">Field Keys</flux:label>
                        <flux:button 
                            wire:click="addKey({{ $sIdx }})" 
                            variant="subtle" 
                            size="sm" 
                            icon="plus"
                            color="sky"
                        >Add More</flux:button>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($section['keys'] as $kIdx => $key)
                            <div class="flex items-start gap-2 animate-slide-in">
                                <div class="flex-1">
                                    <flux:input 
                                        wire:model="sections.{{ $sIdx }}.keys.{{ $kIdx }}" 
                                        placeholder="Key {{ $kIdx + 1 }}" 
                                    />
                                </div>
                                @if(count($section['keys']) > 1)
                                    <flux:button 
                                        wire:click="removeKey({{ $sIdx }}, {{ $kIdx }})" 
                                        variant="ghost" 
                                        color="red" 
                                        icon="x-mark" 
                                        size="sm" 
                                        style="margin-top: 10px;"
                                    />
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach

        <!-- Add New Section Button -->
        <div class="flex justify-center py-4">
            <flux:button 
                wire:click="addSection" 
                variant="subtle" 
                icon="plus" 
                class="w-full max-w-xs border-dashed"
                color="sky"
            >Add New Section</flux:button>
        </div>

        <!-- Save Changes -->
        <flux:separator variant="subtle" class="my-8" />
        <div class="flex justify-end">
            <flux:button 
                wire:click="save" 
                variant="primary" 
                color="sky" 
                class="px-12"
                wire:loading.attr="disabled"
                wire:target="save"
            >
                <span wire:loading.remove wire:target="save">Save All Changes</span>
                <span wire:loading wire:target="save">Saving...</span>
            </flux:button>
        </div>
    </div>
    <style>
        @keyframes slide-in {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-slide-in {
            animation: slide-in 0.2s ease-out forwards;
        }
    </style>
</div>
