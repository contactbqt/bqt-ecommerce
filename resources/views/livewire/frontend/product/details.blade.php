<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

    @section('title', $meta->meta_title ?? $displayProductName)
    @section('meta_description', $meta->meta_description ?? \Illuminate\Support\Str::limit(strip_tags($product->description), 160))
    @section('meta_keywords', $meta->meta_keywords ?? '')

    @section('og_title', $meta->meta_title ?? $displayProductName)
    @section('og_description', $meta->meta_description ?? \Illuminate\Support\Str::limit(strip_tags($product->description), 160))
    @section('og_image', $mainImage ? asset('storage/' . $mainImage) : asset('storage/' . get_setting('SITE_LOGO')))
    
    <!-- Breadcrumbs -->
    <nav class="flex mb-8 text-sm font-medium text-slate-500" aria-label="Breadcrumb">
        <ol class="flex items-center space-x-2">
            <li>
                <a href="{{ route('home') }}" class="hover:text-sky-600 transition-colors">Home</a>
            </li>
            <li class="flex items-center space-x-2">
                <svg class="w-4 h-4 text-slate-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                </svg>
                <a href="{{ route('shop') }}" class="hover:text-sky-600 transition-colors">Shop</a>
            </li>
            @if($category)
            <li class="flex items-center space-x-2">
                <svg class="w-4 h-4 text-slate-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                </svg>
                <a href="{{ route('shop', ['category' => $category->slug]) }}" class="hover:text-sky-600 transition-colors">{{ $category->category_name }}</a>
            </li>
            @endif
            <li class="flex items-center space-x-2">
                <svg class="w-4 h-4 text-slate-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                </svg>
                <span class="text-slate-900 font-semibold truncate max-w-[200px]">{{ $displayProductName }}</span>
            </li>
        </ol>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-start">
        <!-- Product Images -->
        <div class="space-y-6" wire:key="product-images-{{ $variant ? $variant->id : $product->id }}-{{ count($allImages) }}-{{ implode('-', $selectedAttributeValues) }}" x-data="{ currentImage: '{{ $mainImage ? asset('storage/' . $mainImage) : '' }}', zoom: false, mouseX: 0, mouseY: 0 }">
            <div class="relative bg-white rounded-2xl overflow-hidden border border-slate-200 cursor-crosshair group"
                 @mouseenter="zoom = true"
                 @mouseleave="zoom = false"
                 @mousemove="mouseX = (($event.clientX - $event.currentTarget.getBoundingClientRect().left) / $event.currentTarget.getBoundingClientRect().width) * 100; mouseY = (($event.clientY - $event.currentTarget.getBoundingClientRect().top) / $event.currentTarget.getBoundingClientRect().height) * 100"
            >
                @if($mainImage)
                    <img :src="currentImage" alt="{{ $product->product_name }}" 
                         class="w-full h-auto object-contain transition-transform duration-200 ease-out block" 
                         :style="zoom ? `transform: scale(2.5); transform-origin: ${mouseX}% ${mouseY}%;` : 'transform: scale(1); transform-origin: center center;'">
                @else
                    <div class="w-full aspect-[3/4] flex items-center justify-center text-slate-300">
                        <svg class="w-24 h-24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                @endif
            </div>

            <!-- Thumbnail Gallery -->
            @if(count($allImages) > 1)
            <div class="grid grid-cols-5 gap-4">
                @foreach($allImages as $img)
                <button @click="currentImage = '{{ asset('storage/' . $img['image_name']) }}'; zoom = false" 
                        class="aspect-[3/4] rounded-lg overflow-hidden border-2 border-slate-200 hover:border-sky-500 transition-colors focus:outline-none bg-white"
                        :class="{ 'border-sky-500': currentImage === '{{ asset('storage/' . $img['image_name']) }}' }">
                    <img src="{{ asset('storage/' . $img['image_name']) }}" alt="Product thumb" class="w-full h-full object-contain">
                </button>
                @endforeach
            </div>
            @endif
        </div>

        <!-- Product Info -->
        <div class="flex flex-col">
            <div class="mb-6">
                <h1 class="text-3xl font-black text-slate-900 mb-2">{{ $displayProductName }}</h1>
                <p class="text-sm text-sky-600 font-bold uppercase tracking-widest">
                    {{ $category->category_name ?? 'General' }}
                </p>
            </div>

            <div class="flex items-center gap-4 mb-8">
                @php
                    $displayPrice = $product->price;
                    $displayOfferPrice = $product->offer_price;
                    $stockQty = $product->stock_qty;

                    if ($variant) {
                        $displayPrice = $variant->price;
                        $displayOfferPrice = $variant->offer_price > 0 ? $variant->offer_price : null;
                        $stockQty = $variant->stock_qty;
                    }
                @endphp

                @if($displayOfferPrice)
                    <span class="text-xl text-slate-400 line-through">₹{{ number_format($displayPrice, 2) }}</span>
                    <span class="text-4xl font-black text-slate-900">₹{{ number_format($displayOfferPrice, 2) }}</span>
                    @php
                        $discount = (($displayPrice - $displayOfferPrice) / $displayPrice) * 100;
                    @endphp
                    <span class="bg-red-100 text-red-600 text-sm font-bold px-2.5 py-1 rounded-md">{{ round($discount) }}% OFF</span>
                @else
                    <span class="text-4xl font-black text-slate-900">₹{{ number_format($displayPrice, 2) }}</span>
                @endif
            </div>

            <!-- Product Type / Variants -->
            @if($product->product_type === 'variant' && count($availableAttributes) > 0)
            <div class="mb-8 space-y-8">
                @foreach($availableAttributes as $group)
                <div class="space-y-4">
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Select {{ $group['name'] }}</h3>
                    <div class="flex flex-wrap gap-3">
                        @foreach($group['values'] as $val)
                        @if($this->isValueValid($group['id'], $val['id']))
                        <button wire:click="selectAttributeValue({{ $group['id'] }}, {{ $val['id'] }})" 
                            class="px-5 py-2.5 rounded-xl border-2 text-sm font-bold transition-all relative group
                            {{ ($selectedAttributeValues[$group['id']] ?? null) == $val['id'] 
                                ? 'border-sky-600 bg-sky-50 text-sky-600 shadow-sm' 
                                : 'border-slate-100 bg-white text-slate-600 hover:border-slate-200 hover:bg-slate-50' }}">
                            
                            @if($val['hexa'])
                                <div class="flex items-center gap-2">
                                    <span class="w-4 h-4 rounded-full border border-slate-200" style="background-color: {{ $val['hexa'] }}"></span>
                                    {{ $val['name'] }}
                                </div>
                            @else
                                {{ $val['name'] }}
                            @endif

                            @if(($selectedAttributeValues[$group['id']] ?? null) == $val['id'])
                                <span class="absolute -top-1.5 -right-1.5 bg-sky-600 text-white rounded-full p-0.5 shadow-sm">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </span>
                            @endif
                        </button>
                        @endif
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
            @endif

            <!-- Stock Status -->
            <div class="mb-8">
                @if($stockQty > 0)
                    <div class="flex items-center text-emerald-600 font-bold text-sm bg-emerald-50 px-3 py-1.5 rounded-full w-fit">
                        <span class="w-2 h-2 bg-emerald-500 rounded-full mr-2 animate-pulse"></span>
                        In Stock ({{ $stockQty }} units left)
                    </div>
                @else
                    <div class="flex items-center text-red-600 font-bold text-sm bg-red-50 px-3 py-1.5 rounded-full w-fit">
                        <span class="w-2 h-2 bg-red-500 rounded-full mr-2"></span>
                        Out of Stock
                    </div>
                @endif
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 mb-10 pb-10 border-b border-slate-100">
                <button wire:click="addToCart(false)" class="flex-1 bg-slate-900 text-white font-black py-4 rounded-xl hover:bg-slate-800 transition-colors shadow-lg shadow-slate-200 active:scale-95 disabled:opacity-50 disabled:pointer-events-none" {{ $stockQty <= 0 ? 'disabled' : '' }}>
                    ADD TO CART
                </button>
                <button wire:click="addToCart(true)" class="flex-1 bg-sky-600 text-white font-black py-4 rounded-xl hover:bg-sky-500 transition-colors shadow-lg shadow-sky-100 active:scale-95 disabled:opacity-50 disabled:pointer-events-none" {{ $stockQty <= 0 ? 'disabled' : '' }}>
                    BUY NOW
                </button>
            </div>

            <!-- Social Share Section -->
            <div class="mb-10 pb-10 border-b border-slate-100">
                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-4">Share this product</h3>
                <div class="flex flex-wrap gap-4">
                    @php
                        $shareUrl = urlencode(request()->fullUrl());
                        $shareTitle = urlencode($displayProductName);
                    @endphp

                    <!-- Facebook -->
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ $shareUrl }}" 
                       target="_blank" rel="noopener noreferrer"
                       class="w-10 h-10 rounded-full bg-slate-50 flex items-center justify-center text-[#1877F2] hover:bg-[#1877F2] hover:text-white transition-all shadow-sm active:scale-90"
                       title="Share on Facebook">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    </a>

                    <!-- Twitter (X) -->
                    <a href="https://twitter.com/intent/tweet?url={{ $shareUrl }}&text={{ $shareTitle }}" 
                       target="_blank" rel="noopener noreferrer"
                       class="w-10 h-10 rounded-full bg-slate-50 flex items-center justify-center text-slate-900 hover:bg-slate-900 hover:text-white transition-all shadow-sm active:scale-90"
                       title="Share on Twitter">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                    </a>

                    <!-- WhatsApp -->
                    <a href="https://api.whatsapp.com/send?text={{ $shareTitle }}%20{{ $shareUrl }}" 
                       target="_blank" rel="noopener noreferrer"
                       class="w-10 h-10 rounded-full bg-slate-50 flex items-center justify-center text-[#25D366] hover:bg-[#25D366] hover:text-white transition-all shadow-sm active:scale-90"
                       title="Share on WhatsApp">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.27 9.27 0 01-4.73-1.29l-.339-.202-3.51.921.937-3.421-.221-.353a9.242 9.242 0 01-1.417-4.873c0-5.105 4.155-9.26 9.269-9.26 2.476 0 4.803.965 6.551 2.713a9.214 9.214 0 012.713 6.554c0 5.107-4.156 9.263-9.27 9.263m10.057-19.324A10.778 10.778 0 0012.048 2c-5.95 0-10.784 4.832-10.784 10.78 0 1.902.489 3.759 1.42 5.408l-1.509 5.511 5.64-1.48a10.74 10.74 0 005.23 1.353h.005c5.947 0 10.781-4.832 10.781-10.78 0-2.898-1.128-5.623-3.18-7.674"/></svg>
                    </a>

                    <!-- LinkedIn -->
                    <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ $shareUrl }}" 
                       target="_blank" rel="noopener noreferrer"
                       class="w-10 h-10 rounded-full bg-slate-50 flex items-center justify-center text-[#0077B5] hover:bg-[#0077B5] hover:text-white transition-all shadow-sm active:scale-90"
                       title="Share on LinkedIn">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                    </a>
                </div>
            </div>

            <!-- Short Description -->
            @if($product->description)
            <div class="prose prose-slate max-w-none mb-10">
                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-4">Product Description</h3>
                <div class="text-slate-600 leading-relaxed">
                    {!! $product->description !!}
                </div>
            </div>
            @endif

            <!-- Product Additional Info (Specifications) -->
            @if($product->additional_info->count() > 0)
            <div class="space-y-8 mt-10 pt-10 border-t border-slate-100">
                @foreach($product->additional_info as $section)
                <div class="space-y-6">
                    <h3 class="text-xl font-bold text-slate-900">{{ $section->title }}</h3>
                    <div class="flex flex-col space-y-4">
                        @if(is_array($section->additional_info))
                            @foreach($section->additional_info as $key => $value)
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 sm:gap-4">
                                <div class="sm:col-span-1 text-slate-600 text-sm font-medium flex items-center">{{ $key }}</div>
                                <div class="sm:col-span-2 text-slate-900 text-base">{{ $value }}</div>
                            </div>
                            @endforeach
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>

    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
    <div class="mt-24">
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-2xl font-black text-slate-900">You Might Also Like</h2>
            <a href="{{ route('shop', ['category' => $category->slug ?? '']) }}" class="text-sky-600 font-bold hover:underline">View All</a>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            @foreach($relatedProducts as $relItem)
            @php
                $relCategorySlug = $relItem->categories->first()->slug ?? 'general';
                $relVariantId = $relItem->product_type === 'variant' && $relItem->product_variants->count() > 0 ? $relItem->product_variants->first()->id : $relItem->id;
            @endphp
            <div class="group">
                <a href="{{ route('product.details', ['category_slug' => $relCategorySlug, 'product_slug' => $relItem->slug, 'varient_id' => $relVariantId]) }}" class="block">
                    <div class="aspect-square bg-slate-100 rounded-2xl overflow-hidden mb-4 border border-slate-200">
                        @if($relItem->image)
                            <img src="{{ asset('storage/' . $relItem->image) }}" alt="{{ $relItem->product_name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-slate-300">
                                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        @endif
                    </div>
                    <h3 class="text-slate-900 font-bold group-hover:text-sky-600 transition-colors line-clamp-1">{{ $relItem->product_name }}</h3>
                    <p class="text-slate-500 text-sm mb-2 uppercase tracking-widest font-bold text-[10px]">{{ $relItem->categories->first()->category_name ?? 'General' }}</p>
                    <div class="flex items-center gap-2">
                        @if($relItem->product_type === 'variant' && $relItem->product_varients->count() > 0)
                            @php
                                $prices = $relItem->product_varients->map(function($v) {
                                    return ($v->offer_price > 0) ? $v->offer_price : $v->price;
                                });
                                $minPrice = $prices->min();
                                $maxPrice = $prices->max();

                                $originalPrices = $relItem->product_varients->map(fn($v) => $v->price);
                                $minOriginalPrice = $originalPrices->min();
                                $maxOriginalPrice = $originalPrices->max();
                            @endphp

                            @if($minPrice != $maxPrice)
                                <span class="text-lg font-black text-slate-900">₹{{ number_format($minPrice, 2) }} - ₹{{ number_format($maxPrice, 2) }}</span>
                            @else
                                @if($minOriginalPrice > $minPrice)
                                    <span class="text-sm text-slate-400 line-through">₹{{ number_format($minOriginalPrice, 2) }}</span>
                                @endif
                                <span class="text-lg font-black text-slate-900">₹{{ number_format($minPrice, 2) }}</span>
                            @endif
                        @else
                            @if($relItem->offer_price)
                                <span class="text-sm text-slate-400 line-through">₹{{ number_format($relItem->price, 2) }}</span>
                                <span class="text-lg font-black text-slate-900">₹{{ number_format($relItem->offer_price, 2) }}</span>
                            @else
                                <span class="text-lg font-black text-slate-900">₹{{ number_format($relItem->price, 2) }}</span>
                            @endif
                        @endif
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Product Reviews Section -->
    @if(get_setting('ENABLE_REVIEWS') == '1')
    <div class="mt-24 pt-16 border-t border-slate-100">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-16">
            <!-- Left Column: Summary -->
            <div class="lg:col-span-1">
                <h2 class="text-3xl font-black text-slate-900 mb-6">Customer Reviews</h2>
                
                @php
                    $totalReviews = $product->approvedReviews->count();
                    $avgRating = $totalReviews > 0 ? $product->approvedReviews->avg('rating') : 0;
                @endphp

                <div class="flex items-center gap-4 mb-6">
                    <div class="text-5xl font-black text-slate-900">{{ number_format($avgRating, 1) }}</div>
                    <div class="flex flex-col">
                        <div class="flex text-amber-400">
                            @for($i = 1; $i <= 5; $i++)
                                <flux:icon.star class="w-5 h-5 {{ $i <= round($avgRating) ? 'fill-current' : 'text-slate-200' }}" />
                            @endfor
                        </div>
                        <div class="text-sm text-slate-500 font-bold mt-1">Based on {{ $totalReviews }} reviews</div>
                    </div>
                </div>

                @if(Auth::check())
                    @if($user_has_reviewed)
                        <div class="bg-emerald-50 border border-emerald-100 p-6 rounded-2xl">
                            <flux:text class="font-bold text-emerald-800 text-sm">
                                <flux:icon.check-circle class="w-5 h-5 inline mr-2" />
                                You have already submitted a review for this product.
                            </flux:text>
                        </div>
                    @elseif(get_setting('VERIFIED_PURCHASE_ONLY') == '1' && !$user_has_purchased)
                        <div class="bg-amber-50 border border-amber-100 p-6 rounded-2xl">
                            <flux:text class="font-bold text-amber-800 text-sm italic">
                                <flux:icon.information-circle class="w-5 h-5 inline mr-2" />
                                Review submission is restricted to verified purchasers only.
                            </flux:text>
                        </div>
                    @else
                        <div class="bg-slate-50 p-8 rounded-2xl border border-slate-100">
                            <h3 class="text-lg font-bold text-slate-900 mb-4">Write a Review</h3>
                            
                            @if (session()->has('review_success'))
                                <div class="mb-6 bg-emerald-100 text-emerald-800 p-4 rounded-xl border border-emerald-200 text-sm font-bold">
                                    {{ session('review_success') }}
                                </div>
                            @endif

                            @if (session()->has('review_error'))
                                <div class="mb-6 bg-red-100 text-red-800 p-4 rounded-xl border border-red-200 text-sm font-bold">
                                    {{ session('review_error') }}
                                </div>
                            @endif

                            <form wire:submit.prevent="submitReview" class="space-y-6">
                                <div>
                                    <flux:label class="mb-2 block">Rating</flux:label>
                                    <div class="flex gap-2">
                                        @for($i = 1; $i <= 5; $i++)
                                            <button type="button" wire:click="$set('rating', {{ $i }})" class="focus:outline-none group">
                                                <flux:icon.star class="w-8 h-8 {{ $i <= $rating ? 'text-amber-400 fill-current' : 'text-slate-300 group-hover:text-amber-200' }} transition-colors" />
                                            </button>
                                        @endfor
                                    </div>
                                    @error('rating') <span class="text-xs text-red-500 font-bold mt-1">{{ $message }}</span> @enderror
                                </div>

                                <flux:textarea 
                                    wire:model="review_text" 
                                    label="Your Review" 
                                    placeholder="What did you like or dislike?" 
                                    rows="4"
                                />

                                <flux:button type="submit" variant="primary" color="sky" class="w-full h-12 rounded-xl font-bold">
                                    Submit Review
                                </flux:button>
                                
                                @if(!$user_has_purchased)
                                    <p class="text-[10px] text-slate-400 italic text-center">
                                        Note: Your review will not be marked as a verified purchase.
                                    </p>
                                @endif
                            </form>
                        </div>
                    @endif
                @else
                    <div class="bg-sky-50 p-8 rounded-2xl border border-sky-100 text-center">
                        <p class="text-slate-900 font-bold mb-4 text-sm uppercase tracking-widest">Want to share your thoughts?</p>
                        <flux:button href="{{ route('login') }}" variant="primary" color="sky" class="w-full">Sign In to Write a Review</flux:button>
                    </div>
                @endif
            </div>

            <!-- Right Column: Review List -->
            <div class="lg:col-span-2 space-y-12">
                @forelse($product->approvedReviews as $review)
                    <div class="flex flex-col sm:flex-row gap-6 pb-12 border-b border-slate-100 last:border-0">
                        <div class="flex-shrink-0 flex flex-col items-center">
                            <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 font-black text-xl border-2 border-white shadow-sm mb-2">
                                {{ strtoupper(substr($review->user->name, 0, 1)) }}
                            </div>
                            <div class="text-xs font-bold text-slate-400 uppercase tracking-tighter">{{ $review->created_at->format('M d, Y') }}</div>
                        </div>
                        
                        <div class="flex-1">
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex flex-col">
                                    <span class="font-black text-slate-900">{{ $review->user->name }}</span>
                                    @if($review->verified_purchase)
                                        <span class="flex items-center text-[10px] font-black uppercase text-emerald-600 tracking-wider">
                                            <flux:icon.check-badge class="w-3 h-3 mr-1" />
                                            Verified Purchase
                                        </span>
                                    @endif
                                </div>
                                <div class="flex text-amber-400">
                                    @for($i = 1; $i <= 5; $i++)
                                        <flux:icon.star class="w-4 h-4 {{ $i <= $review->rating ? 'fill-current' : 'text-slate-200' }}" />
                                    @endfor
                                </div>
                            </div>
                            <p class="text-slate-600 leading-relaxed">{{ $review->review }}</p>
                        </div>
                    </div>
                @empty
                    <div class="flex flex-col items-center justify-center py-20 bg-slate-50 rounded-[3rem] border border-slate-100 text-center">
                        <div class="w-20 h-20 bg-white rounded-3xl flex items-center justify-center text-slate-200 mb-6 shadow-sm">
                            <flux:icon.chat-bubble-bottom-center-text class="w-10 h-10" />
                        </div>
                        <h3 class="text-xl font-black text-slate-900 uppercase tracking-tighter mb-2">No reviews yet</h3>
                        <p class="text-slate-400 text-sm font-bold uppercase tracking-widest">Be the first to review this product!</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
    @endif
</div>
