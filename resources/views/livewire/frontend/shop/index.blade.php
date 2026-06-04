<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    @section('title', $meta->meta_title ?? $category_name ?? 'Shop')
    @section('meta_description', $meta->meta_description ?? '')
    @section('meta_keywords', $meta->meta_keywords ?? '')
    
    <!-- Page Header & Breadcrumbs -->
    <div class="flex items-center text-sm text-slate-500 mb-6 gap-2">
        <a href="{{ route('home') }}" class="hover:text-sky-600 transition-colors">Home</a>
        <span>/</span>
        <span class="text-slate-900 font-semibold">
            @if(!empty($search))
                Search results for "{{ $search }}"
            @elseif(!empty($category))
                {{ ucfirst(str_replace('-', ' ', $category)) }}
            @else
                All Products
            @endif
        </span>
    </div>

    <!-- Shop Grid Container -->
    <div class="flex flex-col md:flex-row gap-8 items-start">
        
        <!-- Left Sidebar: Filters (Amazon Style) -->
        <aside class="w-full md:w-64 shrink-0 top-24 sticky">
            <h3 class="text-lg font-black text-slate-900 mb-4 pb-2 border-b border-slate-200">Filters</h3>
            
            <div class="space-y-6">
                <!-- Scaffold Filter Group 1 -->
                <div>
                    <h4 class="font-bold text-sm text-slate-900 mb-3">Categories</h4>
                    <div class="space-y-2">
                        @if($firstLevelCategories->count() > 0)
                            <!-- Show all the categories with link -->
                            <ul class="space-y-2">
                            @foreach($firstLevelCategories as $category)
                                <li class="text-sm">
                                    <a href="{{ route('shop', ['category' => $category->slug]) }}" class="hover:text-sky-600 transition-colors">
                                        {{ $category->category_name }}
                                    </a>
                                </li>
                            @endforeach
                            </ul>
                        @endif
                    </div>
                </div>

                <!-- Price Range Filter -->
                <div>
                    <h4 class="font-bold text-sm text-slate-900 mb-3">Price Range</h4>
                    <div class="px-2 mb-2">
                        <div id="price-range-slider" wire:ignore></div>
                    </div>
                    <div class="text-center mb-4">
                        <span class="text-xs font-bold text-slate-600">
                            ₹<span id="min-price-text">{{ $minPrice ?: $minPriceRange }}</span> 
                            - 
                            ₹<span id="max-price-text">{{ $maxPrice ?: $maxPriceRange }}</span>
                        </span>
                    </div>
                </div>

                <!-- Scaffold Filter Group 2 -->
                @if($filterAttributes->count() > 0)
                    @foreach($filterAttributes as $attrCat)
                    <div>
                        <h4 class="font-bold text-sm text-slate-900 mb-3">{{ $attrCat->attribute->attribute_name }}</h4>
                        <div class="space-y-2">
                            @foreach($attrCat->attributeValueCategories as $attrValueCat)
                                <label class="flex items-center gap-2 cursor-pointer group text-sm text-slate-600 hover:text-sky-600">
                                    <input type="checkbox" wire:model.live="selectedAttributes.{{ $attrCat->attribute->slug }}.{{ $attrValueCat->attributeValue->slug }}" class="text-sky-500 border-slate-300 focus:ring-sky-500 rounded">
                                    {{ $attrValueCat->attributeValue->value_name }}
                                </label>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                @endif
            </div>
            
            <button wire:click="clearAllFilters" class="w-full mt-6 py-2 bg-slate-100 hover:bg-slate-200 text-slate-900 font-semibold rounded-lg text-sm transition-colors border border-slate-200">
                Clear Filters
            </button>
        </aside>

        <!-- Right Side: Product Listing -->
        <main class="flex-1 w-full bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
            <div class="flex items-center justify-between mb-6 pb-6 border-b border-slate-100">
                <p class="text-sm text-slate-500 font-medium">Showing {{ $products->count() }} results</p>
                
                <div class="flex items-center gap-3">
                    <span class="text-sm text-slate-500">Sort by:</span>
                    <select wire:model.live="sort" class="text-sm border-slate-200 rounded-lg focus:ring-sky-500 focus:border-sky-500 py-1.5 pl-3 pr-8 bg-slate-50">
                        <option value="">Default (Newest)</option>
                        <option value="price_low_to_high">Price: Low to High</option>
                        <option value="price_high_to_low">Price: High to Low</option>
                        <option value="newest">Newest Arrivals</option>
                        <option value="name_a_z">Name: A to Z</option>
                        <option value="name_z_a">Name: Z to A</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                 <!-- Product cards will load here -->
                 @forelse($products as $item)
                    @php
                        $isOutOfStock = false;
                        if ($item->product_type === 'single') {
                            $isOutOfStock = $item->stock_qty <= 0;
                        } elseif ($item->product_type === 'variant') {
                            $filteredVariants = $item->product_variants->where('price', '>', 0);

                            // Apply price filters to variants in blade
                            if ($minPrice !== '') {
                                $filteredVariants = $filteredVariants->filter(function($v) use ($minPrice) {
                                    $p = ($v->offer_price > 0) ? $v->offer_price : $v->price;
                                    return $p >= $minPrice;
                                });
                            }
                            if ($maxPrice !== '') {
                                $filteredVariants = $filteredVariants->filter(function($v) use ($maxPrice) {
                                    $p = ($v->offer_price > 0) ? $v->offer_price : $v->price;
                                    return $p <= $maxPrice;
                                });
                            }

                            if (!empty($selectedGroups)) {
                                $parentAttrSlugsByGroup = [];
                                foreach($item->productAttributes as $pa) {
                                    $gSlug = $pa->attributeValue->attributes->slug ?? null;
                                    $vSlug = $pa->attributeValue->slug ?? null;
                                    if ($gSlug && $vSlug) {
                                        $parentAttrSlugsByGroup[$gSlug][] = $vSlug;
                                    }
                                }

                                $filteredVariants = $filteredVariants->filter(function($variant) use ($selectedGroups, $parentAttrSlugsByGroup) {
                                    $variantAttrSlugsByGroup = [];
                                    foreach($variant->attributes as $va) {
                                        $gSlug = $va->attributeValue->attributes->slug ?? null;
                                        $vSlug = $va->attributeValue->slug ?? null;
                                        if ($gSlug && $vSlug) {
                                            $variantAttrSlugsByGroup[$gSlug][] = $vSlug;
                                        }
                                    }

                                    foreach ($selectedGroups as $attrSlug => $slugs) {
                                        if (isset($variantAttrSlugsByGroup[$attrSlug])) {
                                            if (count(array_intersect($variantAttrSlugsByGroup[$attrSlug], $slugs)) == 0) {
                                                return false;
                                            }
                                        } else {
                                            if (isset($parentAttrSlugsByGroup[$attrSlug])) {
                                                if (count(array_intersect($parentAttrSlugsByGroup[$attrSlug], $slugs)) == 0) {
                                                    return false;
                                                }
                                            } else {
                                                return false;
                                            }
                                        }
                                    }
                                    return true;
                                });
                            }
                            $isOutOfStock = $filteredVariants->where('status', 1)->sum('stock_qty') <= 0;
                        }
                    @endphp
                    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden hover:shadow-lg transition-shadow group flex flex-col h-full relative">
                        <div class="relative w-full aspect-square bg-slate-100 overflow-hidden shrink-0">
                            <!-- Wishlist Heart Icon -->
                            @php
                                $firstValidVariantForWishlist = $item->product_type === 'variant' ? $item->product_variants->where('price', '>', 0)->first() : null;
                                $currentVariantId = $firstValidVariantForWishlist ? $firstValidVariantForWishlist->id : null;
                                $wishlistKey = $currentVariantId ? 'v_' . $currentVariantId : 'p_' . $item->id;
                                $isInWishlist = in_array($wishlistKey, $wishlistProductIds);
                            @endphp
                            @if( get_setting('PRODUCT_WISHLIST', '0') === '1' )
                            <button 
                                wire:click.prevent="addToWishlist({{ $item->id }}, {{ $currentVariantId ?: 'null' }})"
                                class="absolute top-3 left-3 z-20 w-8 h-8 flex items-center justify-center bg-white/90 hover:bg-white {{ $isInWishlist ? 'text-red-500' : 'text-slate-400' }} hover:text-red-500 rounded-full shadow-sm transition-all active:scale-90 group/heart"
                                title="{{ $isInWishlist ? 'Remove from Wishlist' : 'Add to Wishlist' }}"
                            >
                                <svg class="w-5 h-5 transition-colors {{ $isInWishlist ? 'fill-current' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                            </button>
                            @endif
                            @if($isOutOfStock)
                                <div class="absolute top-3 right-3 z-10">
                                    <span class="px-2.5 py-1 bg-red-500 text-white text-xs font-bold uppercase tracking-wider rounded shadow-sm">
                                        Out of Stock
                                    </span>
                                </div>
                            @endif
                            @if($item->image)
                                <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->product_name }}"
                                    class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-in-out {{ $isOutOfStock ? 'opacity-60 grayscale' : '' }}">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-slate-300 {{ $isOutOfStock ? 'opacity-60' : '' }}">
                                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                </div>
                            @endif
                        </div>

                        <div class="p-4 flex-1 flex flex-col">
                            <p class="text-xs text-sky-600 font-semibold mb-1 uppercase tracking-wider">
                                {{ $item->product_categories->first() && $item->product_categories->first()->categories ? $item->product_categories->first()->categories->category_name : 'General' }}
                            </p>
                            <h3 class="text-slate-900 font-bold mb-2 flex-1 group-hover:text-sky-600 transition-colors line-clamp-2">
                                @php
                                     $categorySlug = $item->product_categories->first() && $item->product_categories->first()->categories ? $item->product_categories->first()->categories->slug : 'general';
                                     $firstValidVariant = $item->product_type === 'variant' ? $item->product_variants->where('price', '>', 0)->first() : null;
                                     $variantId = $firstValidVariant ? $firstValidVariant->id : $item->id;
                                 @endphp
                                <a href="{{ route('product.details', ['category_slug' => $categorySlug, 'product_slug' => $item->slug, 'varient_id' => $variantId]) }}">{{ $item->product_name }}</a>
                            </h3>
                            <div class="flex items-center gap-2 mt-auto pt-4 border-t border-slate-50">
                                @if($item->product_type === 'variant' && $filteredVariants->count() > 0)
                                    @php
                                        $prices = $filteredVariants->map(function($v) {
                                            return ($v->offer_price > 0) ? $v->offer_price : $v->price;
                                        });
                                        $minP = $prices->min();
                                        $maxP = $prices->max();

                                        $originalPrices = $filteredVariants->map(fn($v) => $v->price);
                                        $minOriginalPrice = $originalPrices->min();
                                        $maxOriginalPrice = $originalPrices->max();
                                    @endphp

                                    @if($minP != $maxP)
                                        <span class="text-lg font-black text-slate-900">₹{{ number_format($minP, 2) }} - ₹{{ number_format($maxP, 2) }}</span>
                                    @else
                                        @if($minOriginalPrice > $minP)
                                            <span class="text-sm text-slate-400 line-through">₹{{ number_format($minOriginalPrice, 2) }}</span>
                                        @endif
                                        <span class="text-lg font-black text-slate-900">₹{{ number_format($minP, 2) }}</span>
                                    @endif
                                @else
                                    @if($item->offer_price)
                                        <span class="text-sm text-slate-400 line-through">₹{{ number_format($item->price, 2) }}</span>
                                        <span class="text-lg font-black text-slate-900">₹{{ number_format($item->offer_price, 2) }}</span>
                                    @else
                                        <span class="text-lg font-black text-slate-900">₹{{ number_format($item->price, 2) }}</span>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                 @empty
                    <div class="col-span-full py-20 text-center flex flex-col items-center justify-center">
                        <svg class="h-12 w-12 text-slate-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                        </svg>
                        <p class="text-slate-500 font-medium">No products found matching your criteria.</p>
                    </div>
                 @endforelse
            </div>
            
            @if($products->hasPages())
                <div class="mt-8">
                    {{ $products->links() }}
                </div>
            @endif
        </main>
    </div>

    @push('scripts')
    <script>
        $(function() {
            const minRange = {{ $minPriceRange }};
            const maxRange = {{ $maxPriceRange }};
            
            function initSlider() {
                $("#price-range-slider").slider({
                    range: true,
                    min: minRange,
                    max: maxRange,
                    values: [ 
                        @this.get('minPrice') || minRange, 
                        @this.get('maxPrice') || maxRange 
                    ],
                    slide: function(event, ui) {
                        $("#min-price-text").text(ui.values[0]);
                        $("#max-price-text").text(ui.values[1]);
                    },
                    stop: function(event, ui) {
                        @this.set('minPrice', ui.values[0]);
                        @this.set('maxPrice', ui.values[1]);
                        $("#min-price-text").text(ui.values[0]);
                        $("#max-price-text").text(ui.values[1]);
                    }
                });
            }

            initSlider();

            // Listen for Livewire events to reset slider
            window.addEventListener('resetPriceSlider', event => {
                $("#price-range-slider").slider("option", "min", minRange);
                $("#price-range-slider").slider("option", "max", maxRange);
                $("#price-range-slider").slider("values", [minRange, maxRange]);
                $("#min-price-text").text(minRange);
                $("#max-price-text").text(maxRange);
            });

            // Listen for dynamic range updates
            window.addEventListener('updatePriceRange', event => {
                const data = event.detail[0];
                $("#price-range-slider").slider("option", "min", data.min);
                $("#price-range-slider").slider("option", "max", data.max);
                $("#price-range-slider").slider("values", [data.currentMin, data.currentMax]);
                $("#min-price-text").text(data.currentMin);
                $("#max-price-text").text(data.currentMax);
            });
        });
    </script>
    <style>
        /* Amazon Style Range Slider Customization */
        #price-range-slider {
            height: 4px;
            border: none;
            background: #e2e8f0;
            border-radius: 2px;
            margin: 0 10px; /* Give some space for handles at edges */
        }
        #price-range-slider .ui-slider-range {
            background: #0ea5e9;
        }
        #price-range-slider .ui-slider-handle {
            width: 20px;
            height: 20px;
            top: -8px;
            border-radius: 50%;
            border: 2px solid #0ea5e9;
            background: #fff;
            cursor: pointer;
            transition: transform 0.1s, box-shadow 0.1s;
            z-index: 2;
        }
        #price-range-slider .ui-slider-handle:hover {
            transform: scale(1.1);
            box-shadow: 0 0 0 4px rgba(14, 165, 233, 0.1);
        }
        #price-range-slider .ui-slider-handle:focus {
            outline: none;
            box-shadow: 0 0 0 4px rgba(14, 165, 233, 0.2);
        }
        /* Fix overlapping handles visually */
        #price-range-slider .ui-slider-handle.ui-state-active {
            z-index: 3;
        }
    </style>
    @endpush
</div>
