<div>
    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="text-2xl font-black text-slate-900 tracking-tight">Address Book</h2>
            <p class="text-sm text-slate-500 mt-1">Manage your shipping and billing addresses.</p>
        </div>
        <button wire:click="openModal" class="inline-flex items-center px-4 py-2 bg-sky-600 text-white text-sm font-bold rounded-xl hover:bg-sky-700 transition-colors shadow-sm active:scale-95">
            <flux:icon name="plus" class="w-4 h-4 mr-2" />
            Add New Address
        </button>
    </div>

    @if (session()->has('success'))
        <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 text-sm font-medium rounded-xl flex items-center">
            <flux:icon name="check-circle" class="w-5 h-5 mr-3 text-emerald-500" />
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @forelse($addresses as $address)
            <div class="relative group bg-white border {{ $address->is_default ? 'border-sky-200 ring-1 ring-sky-100' : 'border-slate-200' }} rounded-2xl p-6 hover:shadow-md transition-all">
                @if($address->is_default)
                    <span class="absolute top-4 right-4 bg-sky-100 text-sky-700 text-[10px] font-black uppercase tracking-widest px-2.5 py-1 rounded-full">
                        Default
                    </span>
                @endif

                <div class="flex items-start gap-4 mb-4">
                    <div class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400 group-hover:bg-sky-50 group-hover:text-sky-500 transition-colors">
                        <flux:icon name="map-pin" class="w-5 h-5" />
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-900">{{ $address->title }}</h3>
                        <p class="text-sm text-slate-500 mt-1">Shipping Address</p>
                    </div>
                </div>

                <div class="space-y-1 text-sm text-slate-600 mb-6">
                    <p>{{ $address->address1 }}</p>
                    @if($address->address2)
                        <p>{{ $address->address2 }}</p>
                    @endif
                    <p>{{ $address->city }}, {{ $address->state }} - {{ $address->pincode }}</p>
                    <p>{{ $address->country }}</p>
                </div>

                <div class="flex items-center gap-3 pt-4 border-t border-slate-50">
                    <button wire:click="edit({{ $address->id }})" class="text-xs font-bold text-slate-600 hover:text-sky-600 transition-colors">
                        Edit
                    </button>
                    <button wire:click="delete({{ $address->id }})" wire:confirm="Are you sure you want to delete this address?" class="text-xs font-bold text-slate-600 hover:text-red-600 transition-colors">
                        Delete
                    </button>
                    @if(!$address->is_default)
                        <button wire:click="setAsDefault({{ $address->id }})" class="ml-auto text-xs font-bold text-sky-600 hover:text-sky-700 transition-colors">
                            Set as Default
                        </button>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-full py-16 text-center">
                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300">
                    <flux:icon name="map-pin" class="w-8 h-8" />
                </div>
                <h3 class="text-slate-900 font-bold mb-1">No addresses found</h3>
                <p class="text-slate-500 text-sm mb-6">You haven't added any addresses to your account yet.</p>
                <button wire:click="openModal" class="inline-flex items-center px-4 py-2 bg-white border border-slate-200 text-slate-900 text-sm font-bold rounded-xl hover:bg-slate-50 transition-colors">
                    Add your first address
                </button>
            </div>
        @endforelse
    </div>

    <!-- Modal -->
    @if($showModal)
        <div class="fixed inset-0 z-[60] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" aria-hidden="true" wire:click="closeModal"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div class="inline-block align-bottom bg-white rounded-[2rem] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-8 pt-8 pb-6">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-xl font-black text-slate-900" id="modal-title">
                                {{ $isEditing ? 'Edit Address' : 'Add New Address' }}
                            </h3>
                            <button wire:click="closeModal" class="text-slate-400 hover:text-slate-500 transition-colors">
                                <flux:icon name="x-mark" class="w-6 h-6" />
                            </button>
                        </div>

                        <form wire:submit.prevent="{{ $isEditing ? 'update' : 'store' }}" class="space-y-4">
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1">Address Title (e.g. Home, Office)</label>
                                <input type="text" wire:model="title" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-sky-500 focus:border-sky-500 transition-all" placeholder="Home">
                                @error('title') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div class="grid grid-cols-1 gap-4">
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-1">Address Line 1</label>
                                    <input type="text" wire:model="address1" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-sky-500 focus:border-sky-500 transition-all" placeholder="Street address, P.O. box">
                                    @error('address1') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-1">Address Line 2 (Optional)</label>
                                    <input type="text" wire:model="address2" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-sky-500 focus:border-sky-500 transition-all" placeholder="Apartment, suite, unit, building, floor">
                                    @error('address2') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-1">City</label>
                                    <input type="text" wire:model="city" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-sky-500 focus:border-sky-500 transition-all">
                                    @error('city') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-1">State / Province</label>
                                    <input type="text" wire:model="state" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-sky-500 focus:border-sky-500 transition-all">
                                    @error('state') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-1">ZIP / Postal Code</label>
                                    <input type="text" wire:model="pincode" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-sky-500 focus:border-sky-500 transition-all">
                                    @error('pincode') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-1">Country</label>
                                    <select wire:model="country" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-sky-500 focus:border-sky-500 transition-all">
                                        <option value="India">India</option>
                                        <option value="USA">USA</option>
                                        <option value="UK">UK</option>
                                        <option value="Canada">Canada</option>
                                    </select>
                                    @error('country') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="flex items-center gap-2 pt-2">
                                <input type="checkbox" id="is_default" wire:model="is_default" class="w-4 h-4 text-sky-600 border-slate-300 rounded focus:ring-sky-500">
                                <label for="is_default" class="text-sm font-medium text-slate-700">Set as default address</label>
                            </div>

                            <div class="pt-6 flex gap-3">
                                <button type="button" wire:click="closeModal" class="flex-1 px-4 py-3 border border-slate-200 text-slate-700 text-sm font-bold rounded-xl hover:bg-slate-50 transition-colors">
                                    Cancel
                                </button>
                                <button type="submit" class="flex-1 px-4 py-3 bg-slate-900 text-white text-sm font-bold rounded-xl hover:bg-slate-800 transition-colors shadow-lg shadow-slate-200">
                                    {{ $isEditing ? 'Update Address' : 'Save Address' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
