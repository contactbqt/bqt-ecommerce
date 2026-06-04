<div class="relative mb-6 w-full">
    <flux:heading size="xl" level="1">{{ __('Create Product') }}</flux:heading>
    <flux:subheading size="lg" class="mb-6">{{ __('Add a new product to the catalog.') }}
    </flux:subheading>
    <flux:separator variant="subtle" />


    <div class="p-6 bg-white rounded-xl shadow-md">
        <div class="flex items-center justify-between mb-4 bg-gray-100 p-4 rounded-lg overflow-x-auto">
            <div class="flex items-center gap-2 whitespace-nowrap">
                <flux:button href="{{ route('admin.product.create') }}" variant="primary">Product Information</flux:button>
                <flux:button variant="ghost" disabled>Product Attributes</flux:button>
                
                @if($product_type === 'variant')
                    <flux:button variant="ghost" disabled>Create Combinations</flux:button>
                    <flux:button variant="ghost" disabled>Variants & Pricing</flux:button>
                @endif
                
                <flux:button variant="ghost" disabled>Manage Images</flux:button>
                <flux:button variant="ghost" disabled>Meta Management</flux:button>
            </div>
        </div>
        <div class="mt-6">
            <h3 class="text-lg font-semibold mb-4">Product Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <flux:input wire:model="product_name" label="Product Name" placeholder="Enter product name" badge="Required" description="Enter a unique name for the product." />
                <flux:input wire:model="slug" label="Slug" placeholder="Enter URL-friendly slug" badge="Required" description="Enter a unique slug for the product. This will be used in the product URL." />

                <flux:select wire:model.live="product_type" label="Product Type" badge="Required" description="Single products have no variants, while variant products can have multiple variations. Once product is saved you cannot change it.">
                    <option value="">Select a type</option>
                    @if($product_type === 'simple')
                        <option value="single">Single</option>
                    @elseif($product_type === 'variant')
                        <option value="variant">Variant</option>
                    @else
                        <option value="single">Single</option>
                        <option value="variant">Variant</option>
                    @endif
                </flux:select>

                <flux:select wire:model="is_featured" label="Is Featured" badge="Required" description="Featured products will be displayed prominently on the homepage. Select 'Yes' to feature the product.">
                    <option value="">Select an option</option>
                    <option value="1">Yes</option>
                    <option value="0">No</option>
                </flux:select>
                
                @if($product_type === 'single')
                    <!-- Single Product Only Fields -->
                    <flux:input wire:model="price" type="number" step="0.01" label="Base Price" placeholder="0.00" badge="Required" description="Set the selling price for this product." />
                    <flux:input wire:model="offer_price" type="number" step="0.01" label="Offer Price" placeholder="0.00" description="Set the offer price for this product." />
                    <flux:input wire:model="stock_qty" type="number" label="Stock Quantity" placeholder="0" badge="Required" description="Set the available inventory count." />
                    <flux:input wire:model="sku_code" label="SKU / Product Code" placeholder="e.g. PROD-123" description="Optional internal inventory code." />
                    
                    <div class="space-y-4">
                        <flux:select wire:model.live="family_id" label="Product Family" placeholder="Select an existing family or add new">
                            <option value="">Select a family</option>
                            <option value="new">+ Add New Family</option>
                            @foreach ($productFamilies as $family)
                                <option value="{{ $family->id }}">{{ $family->name }}</option>
                            @endforeach
                        </flux:select>
                        
                        @if ($family_id === 'new')
                            <flux:input wire:model="new_family_name" label="New Family Name" placeholder="Enter new family name" description="Enter the name of the new family to create." />
                        @endif
                    </div>
                @endif

                <flux:select wire:model.live="category_id" label="Category" badge="Required" description="Select the category for this product.">
                    <option value="">Select a category</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                    @endforeach
                </flux:select>

                <flux:select wire:model="status" label="Status" badge="Required" description="Select the status of the product.">
                    <option value="">Select a status</option>
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </flux:select>
                
                <div class="md:col-span-2 mt-4">
                    <flux:textarea wire:model="description" label="Short Description" placeholder="Enter short description" description="Enter a short description for the product." class="h-[150px]" />
                </div>
                
                <div class="md:col-span-2">
                    <flux:input wire:model="image" type="file" label="Product Image" placeholder="Upload product image" accept="image/*" />
                </div>
            </div>

            <!-- Panel: Tag Management -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden mb-16 mt-10">
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
            </div>

            <div class="flex">
                <flux:spacer />
                <flux:button wire:click="createProduct" type="submit" variant="primary" color="sky" wire:loading.attr="disabled" wire:target="createProduct">
                    <span wire:loading.remove wire:target="createProduct">Save changes</span>
                    <span wire:loading wire:target="createProduct">Saving...</span>
                </flux:button>
            </div>
        </div>
    </div>
</div>
