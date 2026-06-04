<div class="relative mb-6 w-full">
    <flux:heading size="xl" level="1">{{ __('Products') }}</flux:heading>
    <flux:subheading size="lg" class="mb-6">{{ __('Create, update, or remove products as needed.') }}
    </flux:subheading>
    <flux:separator variant="subtle" />


    <div class="p-6 bg-white rounded-xl shadow-md">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-semibold">Record List</h2>
            <div class="flex items-center gap-2">
                <flux:button href="{{ route('admin.product.create') }}" variant="ghost" icon:variant="solid" icon="plus">Create New Product</flux:button>
            </div>
        </div>

        <!-- Search and Filter Section -->
        <div class="flex gap-4 mb-6 mt-6">
            <flux:input type="text" wire:model="searchName" placeholder="Search by product name..."
                class="w-full" />

            <flux:button variant="primary" color="sky" wire:click="search">Search</flux:button>
            <flux:button variant="primary" color="zinc" wire:click="resetSearch">Reset</flux:button>
        </div>

        <!-- Department Table -->
        <div class="overflow-x-auto">
            <table class="w-full border-collapse border border-teal-300">
                <thead class="bg-sidebar">
                    <tr>
                        <th
                            class="px-6 py-3 border border-gray-300 text-left text-xs font-medium text-white uppercase tracking-wider">
                            Sl No.</th>
                        <th
                            class="px-6 py-3 border border-gray-300 text-left text-xs font-medium text-white uppercase tracking-wider">
                            Image</th>
                        <th
                            class="px-6 py-3 border border-gray-300 text-left text-xs font-medium text-white uppercase tracking-wider">
                            Name</th>
                        <th
                            class="px-6 py-3 border border-gray-300 text-left text-xs font-medium text-white uppercase tracking-wider">
                            Type</th>
                        <th
                            class="px-6 py-3 border border-gray-300 text-left text-xs font-medium text-white uppercase tracking-wider">
                            Category</th>
                        <th
                            class="px-6 py-3 border border-gray-300 text-left text-xs font-medium text-white uppercase tracking-wider">
                            Price</th>
                        <th
                            class="px-6 py-3 border border-gray-300 text-left text-xs font-medium text-white uppercase tracking-wider">
                            Stock</th>
                        <th
                            class="px-6 py-3 border border-gray-300 text-left text-xs font-medium text-white uppercase tracking-wider">
                            Status</th>
                        <th
                            class="px-6 py-3 border border-gray-300 text-left text-xs font-medium text-white uppercase tracking-wider">
                            Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200 table-fixed">
                    <!-- Example row -->
                    @if ($productList->isNotEmpty())
                        @foreach ($productList as $product)
                            <tr>
                                <td class="px-6 py-6 whitespace-nowrap text-sm text-gray-900 border border-gray-300">
                                    {{ $loop->iteration + ($productList->firstItem() - 1) }}</td>
                                <td class="px-6 py-4 border border-gray-300">
                                    @if(!empty($product->image) && $product->image!= null)
                                        <img src="{{ asset('storage/' . $product->image) }}" alt="Product Image" class="w-16 h-16 rounded-full">
                                    @else
                                        <img src="{{ asset('assets/images/no_image.jpg') }}" alt="Product Image" class="w-16 h-16 rounded-full">
                                    @endif
                                </td>
                                <td class="px-6 py-4 border border-gray-300">
                                    {{ $product->product_name }}
                                </td>
                                <td class="px-6 py-4 border border-gray-300">
                                    {{ ucwords($product->product_type) }}
                                </td>
                                <td class="px-6 py-4 border border-gray-300 text-center">
                                    @if($product->product_categories->isNotEmpty())
                                        @foreach($product->product_categories as $product_category)
                                            {{ $product_category->categories->category_name }}
                                        @endforeach
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-6 py-4 border border-gray-300">
                                    {{ $product->price ? number_format($product->price, 2) : '-' }}
                                </td>
                                <td class="px-6 py-4 border border-gray-300">
                                    @if ($product->product_type === 'variant')
                                        {{ \App\Models\ProductVariant::where('product_id', $product->id)->sum('stock_qty') ?: '0' }}
                                    @else
                                        {{ $product->stock_qty ? $product->stock_qty : '-' }}
                                    @endif
                                </td>
                                <td class="px-6 py-4 border border-gray-300">
                                    {{ $product->status == 'active' ? 'Active' : 'Inactive' }}
                                </td>
                                <td class="px-6 py-4 border border-gray-300">
                                    <flux:dropdown>
                                        <flux:button icon:trailing="chevron-down">Options</flux:button>
                                        <flux:menu>
                                            <flux:menu.item href="{{ route('admin.product.edit', $product->id) }}"
                                                icon="pencil-square">Edit</flux:menu.item>
                                            <flux:menu.item wire:click="deleteConfirmModal({{ $product->id }})"
                                                icon="trash" variant="danger">Delete</flux:menu.item>
                                        </flux:menu>
                                    </flux:dropdown>
                                </td>
                            </tr>
                            <!-- Add more rows as needed -->
                        @endforeach
                        <tr>
                            <td colspan="9" class="px-6 py-4 text-sm text-gray-500">
                                {{ $productList->links() }}
                            </td>
                        </tr>
                    @else
                        <tr>
                            <td colspan="9" class="px-6 py-4 text-center text-sm text-gray-500">No product found.
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <flux:modal name="delete-product" class="min-w-[22rem]">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Delete Product?</flux:heading>

                <flux:text class="mt-2">
                    <p>Are you want to delete the record?</p>
                    <p>You will not able to retore the data anymore.</p>
                </flux:text>
            </div>

            <div class="flex gap-2">
                <flux:spacer />

                <flux:modal.close>
                    <flux:button variant="ghost">Cancel</flux:button>
                </flux:modal.close>

                <flux:button type="submit" wire:click="delete()" variant="danger">Delete Product</flux:button>
            </div>
        </div>
    </flux:modal>
</div>
