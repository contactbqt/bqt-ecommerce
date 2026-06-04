<div>
    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="text-2xl font-black text-slate-900 tracking-tight uppercase">My Wishlist</h2>
            <p class="text-sm text-slate-500 mt-1">Products you've saved for later.</p>
        </div>
    </div>

    @if (session()->has('success'))
        <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 text-sm font-medium rounded-xl flex items-center">
            <flux:icon name="check-circle" class="w-5 h-5 mr-3 text-emerald-500" />
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($wishlistItems as $item)
            @if($item->product_name) {{-- Ensure the product exists --}}
            <div class="relative bg-white border border-slate-200 rounded-3xl overflow-hidden hover:shadow-lg transition-all group">
                <!-- Product Image -->
                <div class="relative aspect-square bg-slate-50 overflow-hidden shrink-0">
                    <a href="{{ route('product.details', ['category_slug' => $item->category_slug, 'product_slug' => $item->slug, 'varient_id' => $item->variant_id]) }}" class="block w-full h-full">
                        @if($item->image)
                            <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->product_name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 {{ $item->is_out_of_stock ? 'opacity-60 grayscale' : '' }}">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-slate-300">
                                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        @endif
                    </a>

                    @if($item->is_out_of_stock)
                        <div class="absolute inset-0 bg-white/20 z-10 flex items-center justify-center backdrop-blur-[2px]">
                            <span class="px-3 py-1 bg-red-600 text-white text-[10px] font-black uppercase tracking-widest rounded-lg shadow-xl shadow-red-200 animate-pulse-once">
                                Out of Stock
                            </span>
                        </div>
                    @endif

                    <!-- Remove Button -->
                    <button wire:click="remove({{ $item->id }})" class="absolute top-3 right-3 z-20 w-8 h-8 flex items-center justify-center bg-white/90 hover:bg-white text-slate-400 hover:text-red-600 rounded-full shadow-sm transition-all active:scale-90" title="Remove from Wishlist">
                        <flux:icon name="x-mark" class="w-4 h-4" />
                    </button>
                </div>

                <!-- Product Details -->
                <div class="p-5">
                    <p class="text-[10px] font-black text-sky-600 uppercase tracking-[0.15em] mb-1">
                        {{ ucfirst($item->category_slug) }}
                    </p>
                    <h3 class="text-sm font-bold text-slate-900 mb-3 group-hover:text-sky-600 transition-colors line-clamp-1">
                        <a href="{{ route('product.details', ['category_slug' => $item->category_slug, 'product_slug' => $item->slug, 'varient_id' => $item->variant_id]) }}">
                            {{ $item->product_name }}
                        </a>
                    </h3>
                    
                    <div class="flex items-center gap-2 mb-5">
                        @if($item->offer_price)
                            <span class="text-xs text-slate-400 line-through">₹{{ number_format($item->price, 2) }}</span>
                            <span class="text-lg font-black text-slate-900 tracking-tighter">₹{{ number_format($item->offer_price, 2) }}</span>
                        @else
                            <span class="text-lg font-black text-slate-900 tracking-tighter">₹{{ number_format($item->price, 2) }}</span>
                        @endif
                    </div>

                    <div class="grid grid-cols-2 gap-2">
                        @if(!$item->is_out_of_stock)
                            <button wire:click="addToCart({{ $item->product_id }}, {{ $item->variant_id }})" 
                                class="flex items-center justify-center gap-2 bg-slate-900 text-white text-[10px] font-black py-3 rounded-xl hover:bg-slate-800 transition-all active:scale-95 uppercase tracking-widest group/btn"
                                wire:loading.attr="disabled"
                                wire:target="addToCart({{ $item->product_id }}, {{ $item->variant_id }})"
                            >
                                <flux:icon name="shopping-bag" class="w-3.5 h-3.5 group-hover/btn:animate-bounce" />
                                <span wire:loading.remove wire:target="addToCart({{ $item->product_id }}, {{ $item->variant_id }})">Add To Cart</span>
                                <span wire:loading wire:target="addToCart({{ $item->product_id }}, {{ $item->variant_id }})">Adding...</span>
                            </button>
                            <a href="{{ route('product.details', ['category_slug' => $item->category_slug, 'product_slug' => $item->slug, 'varient_id' => $item->variant_id]) }}" 
                                class="flex items-center justify-center bg-sky-50 text-sky-700 text-[10px] font-black py-3 rounded-xl hover:bg-sky-100 transition-all active:scale-95 uppercase tracking-widest text-center border border-sky-100"
                            >
                                View Details
                            </a>
                        @else
                            <button disabled class="col-span-2 bg-slate-50 text-slate-400 text-[10px] font-black py-3 rounded-xl cursor-not-allowed uppercase tracking-widest border border-slate-100">
                                Out of Stock
                            </button>
                        @endif
                    </div>
                </div>
            </div>
            @endif
        @empty
            <div class="col-span-full py-20 text-center flex flex-col items-center justify-center bg-slate-50/50 rounded-[3rem] border-2 border-dashed border-slate-100">
                <div class="w-20 h-20 bg-white rounded-3xl flex items-center justify-center text-slate-200 shadow-sm mb-6">
                    <flux:icon name="heart" class="w-10 h-10" />
                </div>
                <h3 class="text-xl font-black text-slate-900 uppercase tracking-tight">Your wishlist is empty</h3>
                <p class="text-slate-500 mt-2 mb-8 font-medium">Save items you love and they will show up here.</p>
                <a href="{{ route('shop') }}" class="inline-flex bg-slate-900 text-white font-black px-10 py-4 rounded-2xl hover:bg-slate-800 transition-all shadow-xl shadow-slate-200 active:scale-95 uppercase tracking-widest text-xs">
                    Start Shopping
                </a>
            </div>
        @endforelse
    </div>
</div>
