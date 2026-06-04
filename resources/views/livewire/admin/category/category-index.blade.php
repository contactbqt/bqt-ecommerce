<div class="relative mb-6 w-full">
    <div class="mb-6">
        <flux:heading size="xl" level="1">{{ __('Categories') }}</flux:heading>
        <flux:subheading size="lg">{{ __('Manage your product categories with drag-and-drop hierarchy') }}</flux:subheading>
    </div>

    <flux:separator variant="subtle" class="mb-6" />

    <!-- Success Message -->
    @if (session()->has('message'))
        <div class="mb-6 bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-r-lg shadow-sm flex items-center">
            <svg class="w-5 h-5 text-emerald-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <p class="text-emerald-800 font-medium">{{ session('message') }}</p>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Left Panel - Create Category Form -->
        <div class="space-y-6">
            <div class="bg-white rounded-xl shadow-md p-8 border border-gray-100">
                <h2 class="text-xl font-bold text-slate-900 mb-6">
                    {{ $editMode ? 'Edit Category' : 'Create Category' }}
                </h2>

                <form wire:submit.prevent="save" class="space-y-6">
                    <flux:input 
                        wire:model.live="category_name" 
                        label="Category Name" 
                        badge="Required" 
                        placeholder="Enter category name" 
                    />
                    
                    <flux:input 
                        wire:model="slug" 
                        label="Slug Name" 
                        badge="Required" 
                        placeholder="category-slug" 
                    />

                    <flux:select wire:model="parent_id" label="Select Parent">
                        <option value="0">None (Root Category)</option>
                        @foreach($parentCategories as $parent)
                            <option value="{{ $parent->id }}">{{ $parent->category_name }}</option>
                        @endforeach
                    </flux:select>

                    <flux:input 
                        type="file" 
                        wire:model="image" 
                        label="Choose Image" 
                        accept="image/*" 
                    />

                    @if ($image)
                        <div class="mt-2 text-sm text-slate-500">
                            <p class="mb-1 font-medium">New image preview:</p>
                            <img src="{{ $image->temporaryUrl() }}" class="h-20 w-20 object-cover rounded-lg border border-slate-200 shadow-sm">
                        </div>
                    @elseif ($editMode && !empty($category))
                        @php
                            $existingImage = is_object($category) ? $category->image : ($category['image'] ?? null);
                        @endphp
                        @if ($existingImage)
                            <div class="mt-2 text-sm text-slate-500">
                                <p class="mb-1 font-medium">Current image:</p>
                                <img src="{{ asset('storage/' . $existingImage) }}" class="h-20 w-20 object-cover rounded-lg border border-slate-200 shadow-sm mb-2">
                                <flux:checkbox 
                                    wire:model="remove_image" 
                                    label="Check if you want to delete" 
                                />
                            </div>
                        @endif
                    @endif

                    <flux:checkbox 
                        wire:model="is_featured" 
                        label="Mark as Featured Category" 
                        description="Featured categories appear prominently on the storefront."
                    />

                    <flux:select wire:model="status" label="Status" badge="Required">
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </flux:select>

                    <div class="pt-4 border-t border-gray-100">
                        <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-4">SEO Information</h3>
                        <div class="space-y-4">
                            <flux:input 
                                wire:model.live="meta_title" 
                                label="Meta Title" 
                                placeholder="SEO Title for category" 
                            />
                            <flux:textarea 
                                wire:model.live="meta_description" 
                                label="Meta Description" 
                                placeholder="Brief description for search engines" 
                                rows="3"
                            />
                            <flux:input 
                                wire:model.live="meta_keywords" 
                                label="Meta Keywords" 
                                placeholder="keyword1, keyword2, etc." 
                            />
                        </div>
                    </div>

                    <div class="flex gap-3 pt-4">
                        <flux:button 
                            type="submit" 
                            variant="primary" 
                            color="sky" 
                            class="flex-1"
                            wire:loading.attr="disabled"
                        >
                            {{ $editMode ? 'Update Category' : 'Create Category' }}
                        </flux:button>
                        
                        @if($editMode)
                            <flux:button 
                                type="button" 
                                wire:click="resetForm" 
                                variant="ghost"
                            >
                                Cancel
                            </flux:button>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <!-- Right Panel - Category Listing -->
        <div class="space-y-6">
            <div class="bg-white rounded-xl shadow-md p-8 border border-gray-100">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold text-slate-900">Category Listing</h2>
                    <div class="flex items-center space-x-2 text-xs font-semibold text-slate-400 bg-slate-50 px-3 py-1.5 rounded-full border border-slate-100 uppercase tracking-tighter">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                        </svg>
                        <span>Drag to reorder</span>
                    </div>
                </div>

                @if($categories->count() > 0)
                    <div id="categoryList" class="space-y-3 mb-8 min-h-[100px]">
                        @foreach($categories as $category)
                            @include('livewire.admin.category.partials.category-item', ['category' => $category, 'level' => 0])
                        @endforeach
                    </div>
                    
                    <flux:button 
                        wire:click="saveOrder" 
                        variant="primary" 
                        color="sky" 
                        icon="check" 
                        class="w-full"
                    >
                        Save Order Changes
                    </flux:button>
                @else
                    <div class="text-center py-20 bg-slate-50 rounded-xl border-2 border-dashed border-slate-200">
                        <svg class="w-16 h-16 mx-auto text-slate-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                        </svg>
                        <p class="text-slate-500 font-medium">No categories yet.<br>Create your first category!</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let pendingChanges = [];
    
    document.addEventListener('livewire:navigated', function() {
        if (typeof Sortable !== 'undefined') {
            initializeSortable();
        }
    });

    function initializeSortable() {
        if (typeof Sortable === 'undefined') return;
        const categoryList = document.getElementById('categoryList');
        if (categoryList) {
            new Sortable(categoryList, {
                group: 'nested',
                animation: 200,
                fallbackOnBody: true,
                swapThreshold: 0.65,
                handle: '.drag-handle',
                ghostClass: 'sortable-ghost',
                chosenClass: 'sortable-chosen',
                dragClass: 'sortable-drag',
                onEnd: function(evt) {
                    collectCategoryOrder();
                }
            });
            
            initializeNestedSortables();
        }
    }
    
    function initializeNestedSortables() {
        document.querySelectorAll('.children-list').forEach(function(el) {
            new Sortable(el, {
                group: 'nested',
                animation: 200,
                fallbackOnBody: true,
                swapThreshold: 0.65,
                handle: '.drag-handle',
                ghostClass: 'sortable-ghost',
                chosenClass: 'sortable-chosen',
                dragClass: 'sortable-drag',
                onEnd: function(evt) {
                    collectCategoryOrder();
                }
            });
        });
    }
    
    function collectCategoryOrder() {
        const items = [];
        document.querySelectorAll('#categoryList > .category-item').forEach((item, index) => {
            items.push(getCategoryData(item, index + 1));
        });
        pendingChanges = items;
    }
    
    function getCategoryData(element, order) {
        const data = {
            id: parseInt(element.dataset.id),
            sort_order: order,
            children: []
        };
        
        const childrenList = element.querySelector(':scope > .children-list');
        if (childrenList) {
            childrenList.querySelectorAll(':scope > .category-item').forEach((child, index) => {
                data.children.push(getCategoryData(child, index + 1));
            });
        }
        
        return data;
    }

    initializeSortable();
    
    document.addEventListener('livewire:init', () => {
        Livewire.on('saveOrder', () => {
            if (pendingChanges.length > 0) {
                @this.call('updateOrder', pendingChanges);
            }
        });
    });
</script>

<style>
    .sortable-ghost {
        opacity: 0.3;
        background: #f0f9ff !important;
        border: 2px dashed #0ea5e9 !important;
    }
    
    .sortable-chosen {
        background: #f8fafc;
    }
</style>
@endpush
