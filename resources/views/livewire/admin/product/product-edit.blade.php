<div class="relative mb-6 w-full">
    <flux:heading size="xl" level="1">{{ __('Edit Product') }}</flux:heading>
    <flux:subheading size="lg" class="mb-6">{{ __('Edit product details and manage product attributes.') }}
    </flux:subheading>
    <flux:separator variant="subtle" />


    <div class="p-6 bg-white rounded-xl shadow-md">
        <x-product-variant-navigation :product-id="$productId" :product="$product" active-step="info" />
        <div class="mt-10">
        <!-- Panel 1: General Product Information -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden mb-12 mt-6">
            <div class="p-8">
                <div class="flex items-center mb-8">
                    <div class="bg-sky-50 p-2.5 rounded-xl mr-4">
                        <flux:icon.document-text class="w-6 h-6 text-sky-500" />
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 tracking-tight">General Information</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pb-6">
                    <flux:input wire:model="product_name" label="Product Name" placeholder="Enter product name" badge="Required" description="Enter a unique name for the product." />
                    <flux:input wire:model="slug" label="Slug" placeholder="Enter URL-friendly slug" badge="Required" description="Enter a unique slug for the product." />
                    <flux:select wire:model="product_type" label="Product Type" description="Single or Variant (Locked)" disabled>
                        <option value="single">Single</option>
                        <option value="variant">Variant</option>
                    </flux:select>
                    <flux:select wire:model="is_featured" label="Is Featured" description="Display prominently on homepage?">
                        <option value="1">Yes</option>
                        <option value="0">No</option>
                    </flux:select>
                    
                    @if($product_type === 'single')
                        <flux:input wire:model="price" type="number" step="0.01" label="Base Price" placeholder="0.00" badge="Required" />
                        <flux:input wire:model="offer_price" type="number" step="0.01" label="Offer Price" placeholder="0.00" />
                        <flux:input wire:model="stock_qty" type="number" label="Stock Quantity" placeholder="0" badge="Required" />
                        <flux:input wire:model="sku_code" label="SKU / Product Code" placeholder="e.g. PROD-123" />
                    @endif

                    <flux:select wire:model.live="category_id" label="Category" badge="Required" description="Select the category for this product.">
                        <option value="">Select a category</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                        @endforeach
                    </flux:select>
                    
                    <flux:select wire:model="status" label="Status" description="Is this product active?">
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </flux:select>

                    <div class="md:col-span-2 mt-4">
                        <flux:textarea wire:model="description" label="Short Description" placeholder="Enter short description" class="h-[150px]" />
                    </div>
                
                <div class="@if($product_type === 'single') col-span-1 md:col-span-2 @else col-span-1 @endif pt-4 border-t border-slate-50 mt-4">
                        <flux:input wire:model="image" type="file" label="Product Image" accept="image/*" />
                        <div class="mt-4 flex items-center space-x-4">
                            <div class="relative group">
                                <img src="{{ $existing_image ? asset('storage/' . $existing_image) : asset('assets/images/no_image.jpg') }}" class="w-24 h-24 object-cover rounded-xl border-2 border-slate-100 shadow-sm" alt="Product Image">
                                @if($existing_image)
                                    <div class="mt-2 text-center">
                                        <flux:checkbox wire:model="deleteImg" value="1" label="Delete?" class="text-xs font-bold text-red-500" />
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                @if (session()->has('info_message'))
                    <div class="mt-6 bg-emerald-50 border-emerald-200 text-emerald-800 p-4 rounded-xl border flex items-center shadow-sm animate-fade-in">
                        <flux:icon.check-circle class="w-5 h-5 mr-3 text-emerald-500" />
                        <span class="font-bold text-sm">{{ session('info_message') }}</span>
                    </div>
                @endif
            </div>

            <!-- Footer Save Bar for Panel 1 -->
            <div class="bg-slate-50/80 px-8 py-5 border-t border-slate-200 flex items-center justify-between">
                <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Core Data Module</span>
                <flux:button wire:click="updateGeneralInfo" variant="primary" color="sky" size="sm" class="px-8 shadow-lg shadow-sky-100">
                    <span wire:loading.remove wire:target="updateGeneralInfo">Save General Information</span>
                    <span wire:loading wire:target="updateGeneralInfo">Saving...</span>
                </flux:button>
            </div>
        </div>

        <!-- Panel: Tag Management -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden mb-12 mt-10">
            <div class="p-8">
                <div class="flex items-center mb-8">
                    <div class="bg-sky-50 p-2.5 rounded-xl mr-4">
                        <flux:icon.tag class="w-6 h-6 text-sky-500" />
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 tracking-tight">Product Tags</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Select Existing Tags -->
                    <div class="space-y-4">
                        <flux:label>Select Tags</flux:label>
                        <div class="flex flex-wrap gap-3 p-4 bg-slate-50 rounded-xl border border-slate-100 max-h-[200px] overflow-y-auto">
                            @foreach($allTags as $tag)
                                <label class="flex items-center space-x-2 bg-white px-3 py-1.5 rounded-lg border border-slate-200 cursor-pointer hover:bg-sky-50 hover:border-sky-200 transition-all">
                                    <input type="checkbox" wire:model="selected_tags" value="{{ $tag->id }}" class="rounded text-sky-500 focus:ring-sky-500">
                                    <span class="text-sm font-medium text-slate-700">{{ $tag->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Add New Tag -->
                    <div class="space-y-4">
                        <flux:label>Add New Tag</flux:label>
                        <div class="flex items-end gap-3">
                            <div class="flex-1">
                                <flux:input wire:model="new_tag_name" placeholder="Enter tag name (e.g. Summer Collection)" />
                            </div>
                            <flux:button wire:click="addNewTag" variant="ghost" color="sky" icon="plus" class="h-10">Add</flux:button>
                        </div>
                        <p class="text-xs text-slate-400 italic">Newly added tags will be automatically selected for this product.</p>
                    </div>
                </div>
                
                @if(!empty($selected_tags))
                    <div class="mt-8 pt-6 border-t border-slate-100">
                        <flux:label class="mb-3 block">Selected Tags Preview:</flux:label>
                        <div class="flex flex-wrap gap-2">
                            @foreach($selected_tags as $tagId)
                                @php $tagName = $allTags->firstWhere('id', $tagId)?->name; @endphp
                                @if($tagName)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-sky-100 text-sky-700 border border-sky-200">
                                        {{ $tagName }}
                                    </span>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
            
            <!-- Footer Save Bar for Tag Management -->
            <div class="bg-slate-50/80 px-8 py-5 border-t border-slate-200 flex items-center justify-between">
                <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Tag Module</span>
                <flux:button wire:click="updateGeneralInfo" variant="primary" color="sky" size="sm" class="px-8 shadow-lg shadow-sky-100">
                    <span wire:loading.remove wire:target="updateGeneralInfo">Save Tags</span>
                    <span wire:loading wire:target="updateGeneralInfo">Saving...</span>
                </flux:button>
            </div>
        </div>

        <!-- Panel 2: Additional Specifications -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden mb-16 mt-10">
            <div class="p-8">
                <div class="flex items-center justify-between mb-8">
                    <div class="flex items-center">
                        <div class="bg-indigo-50 p-2.5 rounded-xl mr-4">
                            <flux:icon.clipboard-document-list class="w-6 h-6 text-indigo-500" />
                        </div>
                        <h3 class="text-xl font-bold text-slate-800 tracking-tight">Additional Specifications</h3>
                    </div>
                    <flux:button wire:click="addDetailSection" variant="ghost" icon="plus" size="xs" color="sky">Add Section</flux:button>
                </div>

                @if (session()->has('specs_message'))
                    <div class="mb-8 bg-sky-50 border-sky-200 text-sky-800 p-4 rounded-xl border flex items-center shadow-sm animate-fade-in">
                        <flux:icon.information-circle class="w-5 h-5 mr-3 text-sky-500" />
                        <span class="font-bold text-sm">{{ session('specs_message') }}</span>
                    </div>
                @endif
                
                <div class="space-y-6">
                    @forelse($additional_details as $sIdx => $section)
                        <div wire:key="section-{{ $sIdx }}-{{ count($section['fields'] ?? []) }}" class="bg-slate-50/50 rounded-2xl border border-slate-100 overflow-hidden shadow-sm">
                            <!-- Section Header Bar -->
                            <div class="bg-white px-6 py-2.5 border-b border-slate-100 flex items-center justify-between">
                                <div class="flex-1 max-w-sm flex items-center space-x-3" x-data="{ editing: false }">
                                    <button @click="editing = !editing; if(editing) $nextTick(() => $refs.titleInput.focus())" class="text-sky-600 p-1.5 hover:bg-sky-50 rounded-lg transition-colors shadow-sm bg-slate-50/50">
                                        <flux:icon.pencil-square class="w-4 h-4" />
                                    </button>
                                    <input 
                                        x-ref="titleInput"
                                        wire:model="additional_details.{{ $sIdx }}.title" 
                                        placeholder="Section Title" 
                                        @focus="editing = true"
                                        @blur="editing = false"
                                        :class="editing ? 'border-sky-300 bg-white ring-4 ring-sky-50 px-2' : 'border-transparent bg-transparent'"
                                        class="font-extrabold text-slate-800 bg-transparent border rounded-lg py-1 text-sm w-full placeholder:text-slate-300 transition-all outline-none"
                                    />
                                </div>
                                <div class="flex items-center space-x-2">
                                    <flux:button wire:click="addDetailField({{ $sIdx }})" variant="ghost" icon="plus" size="xs" color="sky">Field</flux:button>
                                    <flux:button wire:click="removeDetailSection({{ $sIdx }})" variant="ghost" icon="trash" size="xs" color="red" />
                                </div>
                            </div>
                            
                            <!-- Fields List -->
                            <div class="p-3 bg-white/50 space-y-2">
                                @forelse($section['fields'] as $fIdx => $field)
                                    <div wire:key="field-{{ $sIdx }}-{{ $fIdx }}-{{ $field['key'] ?? 'new' }}" class="flex items-center bg-white border border-slate-100 p-2 rounded-xl shadow-sm hover:shadow transition-shadow group/field">
                                        <!-- Key Side -->
                                        <div class="w-64 flex-shrink-0 flex items-center space-x-3 border-r border-slate-50 pr-4 pl-2" x-data="{ editing: false }">
                                            <button @click="editing = !editing; if(editing) $nextTick(() => $refs.keyInput.focus())" class="text-slate-300 hover:text-sky-600 transition-colors">
                                                <flux:icon.pencil-square class="w-3.5 h-3.5" />
                                            </button>
                                            <input 
                                                x-ref="keyInput"
                                                wire:model="additional_details.{{ $sIdx }}.fields.{{ $fIdx }}.key" 
                                                placeholder="Key..." 
                                                @focus="editing = true"
                                                @blur="editing = false"
                                                :class="editing ? 'border-sky-300 bg-white ring-2 ring-sky-50 px-2' : 'border-transparent bg-transparent'"
                                                class="w-full font-bold text-slate-700 text-sm border rounded py-0.5 transition-all outline-none placeholder:text-slate-300"
                                            />
                                        </div>

                                        <!-- Value Side -->
                                        <div class="flex-1 flex items-center space-x-4 pl-4">
                                            <div class="flex-1">
                                                <input 
                                                    wire:model="additional_details.{{ $sIdx }}.fields.{{ $fIdx }}.value" 
                                                    placeholder="Enter value..." 
                                                    class="w-full bg-slate-50/50 border border-slate-100 rounded-lg text-sm text-slate-800 focus:ring-4 focus:ring-sky-50 focus:border-sky-300 focus:bg-white px-3 py-1.5 transition-all outline-none placeholder:text-slate-400 placeholder:italic font-medium"
                                                />
                                            </div>
                                            <button wire:click="removeDetailField({{ $sIdx }}, {{ $fIdx }})" class="opacity-0 group-hover/field:opacity-100 text-red-300 hover:text-red-500 transition-all p-1">
                                                <flux:icon.x-mark class="w-4 h-4" />
                                            </button>
                                        </div>
                                    </div>
                                @empty
                                    <div class="py-10 text-center text-slate-300 text-sm font-medium italic">No specifications defined for this section.</div>
                                @endforelse
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-20 bg-slate-50 rounded-3xl border-2 border-dashed border-slate-200">
                            <flux:icon.pencil-square class="mx-auto h-12 w-12 text-slate-200 mb-4" />
                            <p class="text-slate-500 font-bold">Build Your Custom Specifications</p>
                            <flux:button wire:click="addDetailSection" variant="ghost" color="sky" class="mt-4">New Section</flux:button>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Footer Save Bar for Panel 2 -->
            <div class="bg-slate-50/80 px-8 py-5 border-t border-slate-200 flex items-center justify-between">
                <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Specifications Module</span>
                <flux:button wire:click="updateAdditionalSpecs" variant="primary" color="sky" size="sm" class="px-12 shadow-lg shadow-sky-100">
                    <span wire:loading.remove wire:target="updateAdditionalSpecs">Save Specifications</span>
                    <span wire:loading wire:target="updateAdditionalSpecs">Saving...</span>
                </flux:button>
            </div>
        </div>
    </div>
</div>
</div>
</div>
