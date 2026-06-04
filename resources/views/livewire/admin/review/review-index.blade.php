<div class="relative mb-6 w-full">
    <flux:heading size="xl" level="1">{{ __('Product Reviews') }}</flux:heading>
    <flux:subheading size="lg" class="mb-6">{{ __('Manage and moderate customer reviews.') }}</flux:subheading>
    <flux:separator variant="subtle" />

    <div class="p-6 bg-white rounded-xl shadow-md mt-6">
        <!-- Filters -->
        <div class="flex flex-col md:flex-row gap-4 mb-6">
            <div class="flex-1">
                <flux:input wire:model.live="search" icon="magnifying-glass" placeholder="Search by product, user, or review text..." />
            </div>
            <div class="flex gap-4">
                <flux:select wire:model.live="ratingFilter" placeholder="All Ratings">
                    <option value="">All Ratings</option>
                    <option value="5">5 Stars</option>
                    <option value="4">4 Stars</option>
                    <option value="3">3 Stars</option>
                    <option value="2">2 Stars</option>
                    <option value="1">1 Star</option>
                </flux:select>
                <flux:select wire:model.live="statusFilter" placeholder="All Status">
                    <option value="">All Status</option>
                    <option value="1">Approved</option>
                    <option value="0">Pending/Rejected</option>
                </flux:select>
                <flux:select wire:model.live="spamFilter" placeholder="Spam Status">
                    <option value="0">Hide Spam</option>
                    <option value="1">Spam Only</option>
                </flux:select>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-zinc-50 border-b border-zinc-200">
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-zinc-500">Product</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-zinc-500">User</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-zinc-500">Rating</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-zinc-500">Review</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-zinc-500 text-center">Verified</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-zinc-500 text-center">Status</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-zinc-500 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200">
                    @forelse($reviews as $review)
                        <tr wire:key="{{ $review->id }}" class="hover:bg-zinc-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="text-sm font-bold text-zinc-900">{{ $review->product->product_name }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-zinc-600 font-medium">{{ $review->user->name }}</div>
                                <div class="text-xs text-zinc-400">{{ $review->user->email }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex text-amber-400">
                                    @for($i = 1; $i <= 5; $i++)
                                        <flux:icon.star class="w-4 h-4 {{ $i <= $review->rating ? 'fill-current' : 'text-zinc-200' }}" />
                                    @endfor
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-zinc-600 line-clamp-2 max-w-xs">{{ $review->review }}</div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($review->verified_purchase)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-[10px] font-black uppercase bg-emerald-100 text-emerald-700">Yes</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-[10px] font-black uppercase bg-zinc-100 text-zinc-400">No</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($review->is_approved)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">Approved</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-800">Pending</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right space-x-2">
                                @if(!$review->is_spam)
                                    <flux:button 
                                        wire:click="toggleFeatured({{ $review->id }})" 
                                        variant="ghost" 
                                        size="xs" 
                                        icon="sparkles" 
                                        color="{{ $review->is_featured ? 'amber' : 'zinc' }}" 
                                        title="Toggle Featured" 
                                    />
                                    @if(!$review->is_approved)
                                        <flux:button wire:click="approve({{ $review->id }})" variant="ghost" size="xs" icon="check" color="emerald" title="Approve" />
                                    @else
                                        <flux:button wire:click="reject({{ $review->id }})" variant="ghost" size="xs" icon="x-mark" color="amber" title="Reject" />
                                    @endif
                                    <flux:button wire:click="markAsSpam({{ $review->id }})" variant="ghost" size="xs" icon="exclamation-triangle" color="orange" title="Mark as Spam" />
                                @else
                                    <span class="text-[10px] font-black uppercase text-red-500 mr-2">Spam</span>
                                    <flux:button wire:click="approve({{ $review->id }})" variant="ghost" size="xs" icon="arrow-path" color="zinc" title="Restore" />
                                @endif
                                <flux:button wire:click="confirmDelete({{ $review->id }})" variant="ghost" size="xs" icon="trash" color="red" title="Delete" />
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-zinc-400 italic">No reviews found matching your criteria.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $reviews->links() }}
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <flux:modal name="delete-review-modal" variant="danger" class="min-w-[400px]">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Delete Review</flux:heading>
                <flux:subheading>Are you sure you want to delete this review? This action cannot be undone.</flux:subheading>
            </div>

            <div class="flex gap-3">
                <flux:spacer />
                <flux:modal.close>
                    <flux:button variant="ghost">Cancel</flux:button>
                </flux:modal.close>
                <flux:button wire:click="delete" variant="primary" color="red">Delete Review</flux:button>
            </div>
        </div>
    </flux:modal>
</div>
