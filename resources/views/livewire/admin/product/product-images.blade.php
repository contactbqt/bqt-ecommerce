<div class="relative mb-6 w-full" x-data="{ imageToDelete: null, variantsToDelete: [] }">
    <flux:heading size="xl" level="1">{{ __('Manage Images') }}</flux:heading>
    <flux:subheading size="lg" class="mb-6">{{ __('Upload and organize images for your product variants.') }}</flux:subheading>
    <flux:separator variant="subtle" />

    <div class="p-6 bg-white rounded-xl shadow-md">
        <!-- Navigation Tabs -->
        <x-product-variant-navigation :product-id="$productId" :product="$product" active-step="images" />

        <div class="mt-6">
            <x-product-info-header :product="$product" />
            <h3 class="text-lg font-semibold mb-6">Upload Images</h3>
            
            <div class="grid grid-cols-1 sm:grid-cols-4 gap-8">
                @if($product_type === 'variant')
                    <!-- Variant Selection (Left Side) -->
                    <div class="col-span-1 border-r pr-4">
                        <h4 class="text-base font-bold text-gray-900 mb-4 border-b pb-2">Variants</h4>
                        <div class="space-y-3 pr-2">
                            <label class="inline-flex items-center gap-2 cursor-pointer select-none mb-2 hover:bg-gray-50 p-1 rounded transition-colors w-full">
                                <input type="checkbox" 
                                       wire:click="toggleSelectAllVariants"
                                       @if(count($selectedVariants) === count($variants) && count($variants) > 0) checked @endif
                                       class="w-4 h-4 text-blue-600 bg-white border-gray-300 rounded focus:ring-blue-500 transition-colors">
                                <span class="text-xs font-semibold text-gray-700">Select All Variants</span>
                            </label>
                            
                            @foreach($variants as $variant)
                                <label class="flex items-center gap-3 cursor-pointer group hover:bg-gray-50 p-1 rounded transition-colors w-full">
                                    <input type="checkbox" 
                                           wire:model="selectedVariants" 
                                           value="{{ $variant->id }}"
                                           class="w-4 h-4 text-blue-600 bg-white border-gray-300 rounded focus:ring-blue-500 transition-colors cursor-pointer">
                                    <span class="text-sm font-medium text-gray-700 group-hover:text-gray-900 truncate">{{ $variant->variant_name }}</span>
                                </label>
                            @endforeach
                            @error('selectedVariants') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>
                @endif
                
                <!-- Upload Area (Right Side, or Full Width if not variant) -->
                <div class="@if($product_type === 'variant') sm:col-span-3 @else sm:col-span-4 @endif">
                    <div class="relative w-full h-48 sm:h-64 bg-gray-50 border-2 border-dashed border-gray-300 rounded-lg flex flex-col items-center justify-center hover:bg-gray-100 transition-colors group cursor-pointer">
                        <input type="file" wire:model="photos" multiple accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" />
                        
                        <div wire:loading.remove wire:target="photos" class="flex flex-col items-center text-gray-500 pointer-events-none">
                            <svg class="w-10 h-10 mb-3 text-gray-400 group-hover:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                            </svg>
                            <span class="text-sm font-medium">Drag Images Here to Upload</span>
                            <span class="text-xs mt-1 text-gray-400">or click to browse</span>
                        </div>

                        <div wire:loading wire:target="photos" class="flex flex-col items-center text-gray-500 pointer-events-none">
                            <svg class="animate-spin -ml-1 mr-3 h-8 w-8 text-blue-500 mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span class="text-sm font-medium animate-pulse">Processing Images...</span>
                        </div>
                    </div>
                    
                    @if($photos)
                         <div class="mt-4 flex flex-wrap gap-2">
                              @foreach($photos as $photo)
                                  <div class="w-16 h-16 rounded-md bg-gray-100 overflow-hidden border border-gray-200">
                                      <img src="{{ $photo->temporaryUrl() }}" class="w-full h-full object-cover">
                                  </div>
                              @endforeach
                         </div>
                    @endif
                    @error('photos.*') <span class="text-red-500 text-xs mt-2 block">{{ $message }}</span> @enderror
                    
                    <div class="mt-6 flex justify-end">
                        <flux:button wire:click="saveImages" variant="primary" color="sky" wire:loading.attr="disabled" wire:target="saveImages">
                            <span wire:loading.remove wire:target="saveImages">Upload and Save</span>
                            <span wire:loading wire:target="saveImages">Saving...</span>
                        </flux:button>
                    </div>
                </div>
            </div>

            <!-- Existing Set -->
            <div class="mt-12">
                <h4 class="text-lg font-bold text-gray-900 mb-6">Existing Set</h4>
                
                @if(count($existingImages) > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        @foreach($existingImages as $imgData)
                            <div class="flex gap-6 items-start p-4 bg-white border border-gray-100 rounded-xl shadow-sm hover:shadow-md transition-shadow" wire:key="img-set-{{ md5($imgData['image_name']) }}">
                                
                                <!-- Image Thumbnail with Delete Button -->
                                <div class="relative flex-shrink-0 group">
                                    <div class="w-28 h-28 sm:w-36 sm:h-36 bg-gray-100 rounded-lg overflow-hidden border border-gray-200 shadow-sm">
                                        <img src="{{ asset('storage/' . $imgData['image_name']) }}" alt="Product Image" class="w-full h-full object-cover">
                                    </div>
                                    <button type="button" 
                                            @click="
                                                imageToDelete = '{{ $imgData['image_name'] }}'; 
                                                variantsToDelete = {{ json_encode($imgData['variants'] ?? []) }};
                                                $dispatch('modal-show', { name: 'confirm-image-delete' })
                                            "
                                            class="absolute -top-3 -right-3 bg-red-500 hover:bg-red-600 text-white w-7 h-7 rounded-full flex items-center justify-center shadow-md transform transition-transform hover:scale-110 focus:outline-none z-10 border-2 border-white"
                                            title="Delete Image">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>

                                <!-- Mapped Variants -->
                                <div class="flex-grow pt-1">
                                    @if($product_type === 'variant' && !empty($imgData['variants']))
                                        <div class="flex flex-col gap-1.5">
                                            @foreach($imgData['variants'] as $vName)
                                                <span class="text-sm font-medium text-gray-700 leading-tight">{{ $vName }}</span>
                                            @endforeach
                                        </div>
                                    @elseif($product_type !== 'variant')
                                        <span class="text-sm font-medium text-gray-500">Base Product Image</span>
                                    @else
                                        <span class="text-sm italic text-gray-400">No variants mapped</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="py-12 bg-gray-50 rounded-lg border-2 border-dashed border-gray-200 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <p class="mt-2 text-sm text-gray-500">No images have been uploaded yet.</p>
                    </div>
                @endif
            </div>

        </div>
    </div>

    <!-- Custom Delete Confirmation Modal -->
    <flux:modal name="confirm-image-delete" class="md:w-[450px]">
        <div class="p-2">
            <div class="flex items-start gap-4 mb-4">
                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-red-100 flex items-center justify-center text-red-600 mt-1">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900 mb-1">Delete Image?</h3>
                    <p class="text-sm text-gray-600 mb-4">Are you sure you want to permanently delete this image?</p>
                    
                    <template x-if="variantsToDelete && variantsToDelete.length > 0">
                        <div class="bg-red-50 border border-red-100 rounded-md p-3 mb-2">
                            <p class="text-xs font-semibold text-red-800 mb-2 border-b border-red-200 pb-1">Warning! This image will be unlinked from the following variants:</p>
                            <ul class="list-disc pl-5 text-xs text-red-700 space-y-1">
                                <template x-for="variant in variantsToDelete" :key="variant">
                                    <li x-text="variant"></li>
                                </template>
                            </ul>
                        </div>
                    </template>
                    
                    <template x-if="!variantsToDelete || variantsToDelete.length === 0">
                         <p class="text-xs italic text-gray-500 mb-2">This image is currently not mapped to any specific variants.</p>
                    </template>
                </div>
            </div>
            
            <div class="flex justify-end gap-3 mt-6">
                <flux:modal.close>
                    <flux:button variant="ghost">Cancel</flux:button>
                </flux:modal.close>

                <flux:button variant="danger" x-on:click="$wire.deleteImage(imageToDelete); $dispatch('modal-close', { name: 'confirm-image-delete' });">
                    Yes, Delete
                </flux:button>
            </div>
        </div>
    </flux:modal>
</div>
