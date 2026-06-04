<div class="category-item" data-id="{{ $category->id }}" style="margin-left: {{ $level * 1.5 }}rem;">
    <div class="flex items-center justify-between p-3 bg-white border border-slate-100 rounded-xl hover:shadow-lg hover:border-blue-200 transition-all duration-300 group shadow-sm mb-2">
        <!-- Left Side - Drag Handle & Category Info -->
        <div class="flex items-center space-x-3.5 flex-1 min-w-0">
            <!-- Drag Handle -->
            <div class="drag-handle text-slate-300 hover:text-blue-500 transition-colors flex-shrink-0 cursor-grab active:cursor-grabbing p-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/>
                </svg>
            </div>

            <!-- Category Name & Slug -->
            <div class="flex-1 min-w-0">
                <h3 class="font-bold text-slate-800 text-[12px] overflow-hidden leading-tight mb-0.5 tracking-tight">{{ $category->category_name }}</h3>
                <p class="text-[10px] text-slate-400 font-medium font-mono truncate opacity-60">{{ $category->slug }}</p>
            </div>

            <!-- Stats/Badges -->
            <div class="flex items-center space-x-2 flex-shrink-0">
                @php
                    $isActive = $category->status == 1 || $category->status === 'active';
                @endphp
                <span class="border rounded-md px-1.5 font-bold tracking-tighter" style="font-size: 9px !important; padding-top: 1px !important; padding-bottom: 1px !important; {{ $isActive ? 'background-color: #ecfdf5; color: #059669; border-color: #d1fae5;' : 'background-color: #fffbeb; color: #d97706; border-color: #fef3c7;' }}">
                    {{ $isActive ? 'ACTIVE' : 'INACTIVE' }}
                </span>

                @if($category->children && $category->children->count() > 0)
                    <span class="border rounded-md px-1.5 font-bold tracking-tighter" style="font-size: 9px !important; padding-top: 1px !important; padding-bottom: 1px !important; background-color: #eff6ff; color: #2563eb; border-color: #dbeafe;">
                        {{ $category->children->count() }} SUB
                    </span>
                @endif
            </div>
        </div>

        <!-- Right Side - Action Dropdown (Always Visible) -->
        <div class="flex items-center ml-4">
            <flux:dropdown>
                <flux:button variant="ghost" icon="ellipsis-vertical" size="sm" class="text-slate-300 hover:text-slate-500" />
                <flux:menu>
                    <flux:menu.item 
                        href="{{ route('admin.category.info', $category->id) }}" 
                        icon="document-text" 
                        wire:navigate
                    >
                        Specifications
                    </flux:menu.item>

                    <flux:menu.item 
                        href="{{ route('admin.category.attributes', $category->id) }}" 
                        icon="tag" 
                        wire:navigate
                    >
                        Tag Attributes
                    </flux:menu.item>
                    
                    <flux:menu.item 
                        wire:click="edit({{ $category->id }})" 
                        icon="pencil-square"
                    >
                        Edit
                    </flux:menu.item>

                    <flux:menu.separator />
                    
                    <flux:menu.item 
                        wire:click="delete({{ $category->id }})" 
                        wire:confirm="Are you sure you want to delete this category?"
                        icon="trash" 
                        variant="danger"
                    >
                        Delete
                    </flux:menu.item>
                </flux:menu>
            </flux:dropdown>
        </div>
    </div>

    <!-- Children Categories -->
    @if($category->children && $category->children->count() > 0)
        <div class="children-list space-y-1 mb-2">
            @foreach($category->children as $child)
                @include('livewire.admin.category.partials.category-item', ['category' => $child, 'level' => $level + 1])
            @endforeach
        </div>
    @endif
</div>
