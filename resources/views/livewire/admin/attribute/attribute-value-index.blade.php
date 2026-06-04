<div class="relative mb-6 w-full">
    <flux:heading size="xl" level="1">{{ __('Attribute Values') }}</flux:heading>
    <flux:subheading size="lg" class="mb-6">{{ __('Create, update, or remove attribute values as needed.') }}
    </flux:subheading>
    <flux:separator variant="subtle" />

    @if (session()->has('message'))
        <x-alert-message :message="session('message')" />
    @endif
    <div class="p-6 bg-white rounded-xl shadow-md">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-semibold">Record List for : {{ $attribute['attribute_name'] }} <span class="text-sm text-gray-500">attribute</span></h2>
            <div class="flex items-center gap-2">
                <flux:button variant="ghost" icon="arrow-left" href="{{ route('admin.attribute.index') }}" class="text-sm text-gray-500">Back to Attribute List</flux:button>
                <livewire:admin.attribute.attribute-value-create :attribute_id="$attribute_id" />
            </div>
        </div>

        <!-- Search and Filter Section -->
        <div class="flex gap-4 mb-6 mt-6">
            <flux:input type="text" wire:model="searchName" placeholder="Search by attribute name..."
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
                            Value Name</th>
                        <th
                            class="px-6 py-3 border border-gray-300 text-left text-xs font-medium text-white uppercase tracking-wider">
                            Slug</th>
                        <th
                            class="px-6 py-3 border border-gray-300 text-left text-xs font-medium text-white uppercase tracking-wider">
                            Sort Order</th>
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
                    @if ($valueList->isNotEmpty())
                        @foreach ($valueList as $value)
                            <tr>
                                <td class="px-6 py-6 whitespace-nowrap text-sm text-gray-900 border border-gray-300">
                                    {{ $loop->iteration + ($valueList->firstItem() - 1) }}</td>
                                <td class="px-6 py-4 border border-gray-300">
                                    {{ $value->value_name }}
                                </td>
                                <td class="px-6 py-4 border border-gray-300">
                                    {{ $value->slug }}
                                </td>
                                <td class="px-6 py-4 border border-gray-300 text-center">
                                    {{ $value->sort_order }}
                                </td>
                                <td class="px-6 py-4 border border-gray-300">
                                    {{ $value->status == 'active' ? 'Active' : 'Inactive' }}
                                </td>
                                <td class="px-6 py-4 border border-gray-300">
                                    <flux:dropdown>
                                        <flux:button icon:trailing="chevron-down">Options</flux:button>
                                        <flux:menu>
                                            <flux:menu.item wire:click="edit({{ $value->id }})"
                                                icon="pencil-square">Edit</flux:menu.item>
                                            <flux:menu.item wire:click="deleteConfirmModal({{ $value->id }})"
                                                icon="trash" variant="danger">Delete</flux:menu.item>
                                        </flux:menu>
                                    </flux:dropdown>
                                </td>
                            </tr>
                            <!-- Add more rows as needed -->
                        @endforeach
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-sm text-gray-500">
                                {{ $valueList->links() }}
                            </td>
                        </tr>
                    @else
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">No attribute found.
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
    <!-- Edit Modal -->
    <livewire:admin.attribute.attribute-value-edit :attribute_id="$attribute_id" />

    <!-- Delete Confirmation Modal -->
    <flux:modal name="delete-attribute-value" class="min-w-[22rem]">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Delete Attribute Value?</flux:heading>

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

                <flux:button type="submit" wire:click="delete()" variant="danger">Delete Attribute Value</flux:button>
            </div>
        </div>
    </flux:modal>
</div>
