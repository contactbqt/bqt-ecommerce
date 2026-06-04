<div class="relative mb-6 w-full">
    <flux:heading size="xl" level="1">{{ __('Tags') }}</flux:heading>
    <flux:subheading size="lg" class="mb-6">{{ __('Create, update, or remove tags as needed.') }}
    </flux:subheading>
    <flux:separator variant="subtle" />

    @if (session()->has('message'))
        <x-alert-message :message="session('message')" />
    @endif
    <div class="p-6 bg-white rounded-xl shadow-md">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-semibold">Record List</h2>
            <div class="flex items-center gap-2">
                <livewire:admin.tag.tag-create />
            </div>
        </div>

        <!-- Search and Filter Section -->
        <div class="flex gap-4 mb-6 mt-6">
            <flux:input type="text" wire:model="searchName" placeholder="Search by tag name..."
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
                            Tag Name</th>
                        <th
                            class="px-6 py-3 border border-gray-300 text-left text-xs font-medium text-white uppercase tracking-wider">
                            Slug</th>
                        <th
                            class="px-6 py-3 border border-gray-300 text-left text-xs font-medium text-white uppercase tracking-wider">
                            Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200 table-fixed">
                    <!-- Example row -->
                    @if ($tagList->isNotEmpty())
                        @foreach ($tagList as $tag)
                            <tr>
                                <td class="px-6 py-6 whitespace-nowrap text-sm text-gray-900 border border-gray-300">
                                    {{ $loop->iteration + ($tagList->firstItem() - 1) }}</td>
                                <td class="px-6 py-4 border border-gray-300">
                                    {{ $tag->name }}
                                </td>
                                <td class="px-6 py-4 border border-gray-300">
                                    {{ $tag->slug }}
                                </td>
                                <td class="px-6 py-4 border border-gray-300">
                                    <flux:dropdown>
                                        <flux:button icon:trailing="chevron-down">Options</flux:button>
                                        <flux:menu>
                                            <flux:menu.item wire:click="edit({{ $tag->id }})"
                                                icon="pencil-square">Edit</flux:menu.item>
                                            <flux:menu.item wire:click="deleteConfirmModal({{ $tag->id }})"
                                                icon="trash" variant="danger">Delete</flux:menu.item>
                                        </flux:menu>
                                    </flux:dropdown>
                                </td>
                            </tr>
                            <!-- Add more rows as needed -->
                        @endforeach
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-sm text-gray-500">
                                {{ $tagList->links() }}
                            </td>
                        </tr>
                    @else
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">No tag found.
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
    <!-- Edit Modal -->
    <livewire:admin.tag.tag-edit />

    <!-- Delete Confirmation Modal -->
    <flux:modal name="delete-tag" class="min-w-[22rem]">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Delete Tag?</flux:heading>

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

                <flux:button type="submit" wire:click="delete()" variant="danger">Delete Tag</flux:button>
            </div>
        </div>
    </flux:modal>
</div>
