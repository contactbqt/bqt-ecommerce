<div>
    <!-- Hero Slider Section -->
    <div x-data="{ 
        activeSlide: 1, 
        slides: [
            { 
                id: 1, 
                title: 'Step into Style', 
                subtitle: 'Exclusive footwear for every walk of life', 
                image: 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?q=80&w=1920&auto=format&fit=crop',
                cta: 'Shop Footwear'
            },
            { 
                id: 2, 
                title: 'Premium Apparel Collection', 
                subtitle: 'Exquisite fashion for your unique style', 
                image: 'https://images.unsplash.com/photo-1490481651871-ab68de25d43d?q=80&w=1920&auto=format&fit=crop',
                cta: 'Explore Clothing'
            },
            { 
                id: 3, 
                title: 'Cutting-Edge Electronics', 
                subtitle: 'Innovative gadgets to power your lifestyle', 
                image: 'https://images.unsplash.com/photo-1498049794561-7780e7231661?q=80&w=1920&auto=format&fit=crop',
                cta: 'View Electronics'
            },
            { 
                id: 4, 
                title: 'Modern Home Decor', 
                subtitle: 'Transform your space with elegant accents', 
                image: 'https://images.unsplash.com/photo-1513519245088-0e12902e5a38?q=80&w=1920&auto=format&fit=crop',
                cta: 'Shop Decor'
            }
        ],
        next() { this.activeSlide = this.activeSlide === this.slides.length ? 1 : this.activeSlide + 1 },
        prev() { this.activeSlide = this.activeSlide === 1 ? this.slides.length : this.activeSlide - 1 },
        init() { setInterval(() => this.next(), 6000) }
    }" class="relative bg-slate-900 overflow-hidden h-[400px] md:h-[500px] lg:h-[600px]">
        
        <!-- Slides -->
        <template x-for="slide in slides" :key="slide.id">
            <div x-show="activeSlide === slide.id" 
                 x-transition:enter="transition ease-out duration-1000"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-500"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="absolute inset-0">
                
                <!-- Background Image with Overlay -->
                <div class="absolute inset-0 bg-slate-900">
                    <img :src="slide.image" :alt="slide.title" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-r from-slate-900/60 via-slate-900/20 to-transparent"></div>
                </div>

                <!-- Content -->
                <div class="relative h-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center">
                    <div class="max-w-xl text-left">
                        <span x-text="'Collection 2026'" class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold tracking-widest text-sky-400 bg-sky-400/10 mb-6 border border-sky-400/20 uppercase"></span>
                        <h1 x-text="slide.title" class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-black text-white mb-4 md:mb-6 leading-tight"></h1>
                        <p x-text="slide.subtitle" class="text-base sm:text-lg md:text-xl text-slate-200 md:mb-8 font-light leading-relaxed"></p>
                        
                        <div class="flex flex-wrap gap-4">
                            <a :href="'{{ route('shop') }}'" class="inline-flex items-center justify-center px-6 py-3 md:px-8 md:py-4 bg-sky-500 text-slate-900 font-black rounded-full hover:bg-sky-400 transition-all shadow-lg shadow-sky-500/30 active:scale-95">
                                <span x-text="slide.cta"></span>
                                <svg class="w-4 h-4 md:w-5 md:h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </template>

        <!-- Navigation Arrows -->
        <div class="absolute inset-x-0 top-1/2 -translate-y-1/2 flex justify-between px-4 md:px-8 z-20 pointer-events-none">
            <button @click="prev()" class="w-10 h-10 md:w-12 md:h-12 rounded-full bg-white/10 hover:bg-white/20 text-white backdrop-blur-md flex items-center justify-center transition-all pointer-events-auto active:scale-90">
                <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </button>
            <button @click="next()" class="w-10 h-10 md:w-12 md:h-12 rounded-full bg-white/10 hover:bg-white/20 text-white backdrop-blur-md flex items-center justify-center transition-all pointer-events-auto active:scale-90">
                <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </button>
        </div>

        <!-- Pagination Dots -->
        <div class="absolute bottom-6 md:bottom-8 left-1/2 -translate-x-1/2 flex gap-3 z-20">
            <template x-for="slide in slides" :key="slide.id">
                <button @click="activeSlide = slide.id" 
                        class="h-1.5 rounded-full transition-all duration-300"
                        :class="activeSlide === slide.id ? 'w-8 bg-sky-400' : 'w-2 bg-white/30 hover:bg-white/50'"></button>
            </template>
        </div>
    </div>

    <!-- Featured Categories Section -->
    @if(count($featuredCategories) > 0)
        <div class="bg-white py-16 border-b border-slate-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-black text-slate-900 tracking-tight mb-2">Featured Categories</h2>
                    <p class="text-slate-500 max-w-2xl mx-auto">Explore our wide range of products across various categories. Find exactly what you're looking for.</p>
                </div>

                <div class="flex flex-wrap justify-center gap-8 md:gap-12">
                    @foreach($featuredCategories as $cat)
                        <a href="{{ route('shop', ['category' => $cat->slug]) }}" 
                           class="group flex flex-col items-center text-center max-w-[120px] transition-all duration-300">
                            <div class="relative w-25 h-25 md:w-30 md:h-30 rounded-full overflow-hidden mb-4 border-2 {{ $cat->is_featured ? 'border-amber-400 shadow-amber-200/50' : 'border-transparent' }} group-hover:border-sky-500 transition-all shadow-md group-hover:shadow-xl group-hover:-translate-y-1">
                                @if($cat->image)
                                    <img src="{{ asset('storage/' . $cat->image) }}" alt="{{ $cat->category_name }}"
                                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                @else
                                    <div class="w-full h-full bg-slate-100 flex items-center justify-center text-slate-400">
                                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16m-7 6h7"></path>
                                        </svg>
                                    </div>
                                @endif
                                <div class="absolute inset-0 bg-sky-500/0 group-hover:bg-sky-500/10 transition-colors"></div>
                            </div>
                            <h3 class="text-sm md:text-base font-bold text-slate-800 group-hover:text-sky-600 transition-colors line-clamp-1">
                                {{ $cat->category_name }}
                            </h3>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- Featured Real Products Section -->
    <div class="bg-slate-50 py-16 border-t border-slate-200 shadow-inner">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-10">
                <div>
                    <h2 class="text-2xl font-black text-slate-900 tracking-tight">Trending Now</h2>
                    <p class="text-sm text-slate-500 mt-1">Explore our latest and greatest items.</p>
                </div>
                <a href="{{ route('shop') }}"
                    class="px-5 py-2.5 rounded-full bg-white border border-slate-200 text-sm font-semibold hover:border-sky-300 hover:text-sky-600 shadow-sm transition-all">
                    View shop
                </a>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
                @forelse($featuredProducts as $item)
                    @php
                        $isOutOfStock = false;
                        if ($item->product_type === 'single') {
                            $isOutOfStock = $item->stock_qty <= 0;
                        } elseif ($item->product_type === 'variant') {
                            $isOutOfStock = $item->product_variants->sum('stock_qty') <= 0;
                        }
                        
                        $catSlug = $item->product_categories->first() && $item->product_categories->first()->categories ? $item->product_categories->first()->categories->slug : 'general';
                        $variantId = $item->product_type === 'variant' && $item->product_variants->count() > 0 ? $item->product_variants->first()->id : $item->id;
                        $productUrl = route('product.details', ['category_slug' => $catSlug, 'product_slug' => $item->slug, 'varient_id' => $variantId]);
                    @endphp
                    <a href="{{ $productUrl }}" class="bg-white rounded-xl border border-slate-200 overflow-hidden hover:shadow-lg transition-shadow group flex flex-col h-full relative">
                        <div class="relative w-full aspect-square bg-slate-100 overflow-hidden shrink-0 block">
                            @if($isOutOfStock)
                                <div class="absolute top-3 right-3 z-10">
                                    <span class="px-2.5 py-1 bg-red-500 text-white text-xs font-bold uppercase tracking-wider rounded shadow-sm">
                                        Out of Stock
                                    </span>
                                </div>
                            @endif
                            @if($item->image)
                                <!-- Let's pretend the URL maps properly -->
                                <div class="w-full h-full flex items-center justify-center bg-sky-50 text-sky-300 font-bold tracking-widest text-[10px] uppercase">
                                    <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->product_name }}"
                                        class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-in-out {{ $isOutOfStock ? 'opacity-60 grayscale' : '' }}">
                                </div>
                            @else
                                <div class="w-full h-full flex items-center justify-center text-slate-300 {{ $isOutOfStock ? 'opacity-60' : '' }}">
                                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                </div>
                            @endif
                            <div class="absolute top-3 left-3 flex flex-col gap-2">
                                <span class="px-2 py-1 bg-white shadow-sm text-xs font-bold text-slate-700 rounded-lg">New</span>
                            </div>
                        </div>

                        <div class="p-4 flex-1 flex flex-col">
                            <p class="text-xs text-sky-600 font-semibold mb-1 uppercase tracking-wider">
                                {{ $item->product_categories->first() && $item->product_categories->first()->categories ? $item->product_categories->first()->categories->category_name : 'General' }}
                            </p>
                            <h3
                                class="text-slate-900 font-bold mb-2 flex-1 group-hover:text-sky-600 transition-colors line-clamp-2">
                                {{ $item->product_name }}
                            </h3>
                            <div class="flex items-center gap-2 mt-auto pt-4 border-t border-slate-50">
                                @if($item->product_type === 'variant' && $item->product_variants->count() > 0)
                                    @php
                                        $prices = $item->product_variants->map(function($v) {
                                            return ($v->offer_price > 0) ? $v->offer_price : $v->price;
                                        });
                                        $minPrice = $prices->min();
                                        $maxPrice = $prices->max();

                                        $originalPrices = $item->product_variants->map(fn($v) => $v->price);
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
                                    @if($item->offer_price)
                                        <span class="text-sm text-slate-400 line-through">₹{{ number_format($item->price, 2) }}</span>
                                        <span class="text-lg font-black text-slate-900">₹{{ number_format($item->offer_price, 2) }}</span>
                                    @else
                                        <span class="text-lg font-black text-slate-900">₹{{ number_format($item->price, 2) }}</span>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="col-span-full py-12 text-center text-slate-500">
                        No featured products currently available.
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Value Proposition -->
    <div class="bg-white py-16 border-t border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                <div class="flex flex-col items-center text-center p-6 rounded-2xl bg-slate-50 border border-slate-100">
                    <div
                        class="w-14 h-14 bg-white rounded-full flex items-center justify-center mb-4 shadow-sm text-sky-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 mb-2">Free Delivery</h3>
                    <p class="text-slate-500 text-sm">On all orders above $100. Fast and secure shipping to your
                        doorstep.</p>
                </div>
                <div class="flex flex-col items-center text-center p-6 rounded-2xl bg-slate-50 border border-slate-100">
                    <div
                        class="w-14 h-14 bg-white rounded-full flex items-center justify-center mb-4 shadow-sm text-sky-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.956 11.956 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 mb-2">Secure Payments</h3>
                    <p class="text-slate-500 text-sm">100% secure payment gateways ensuring your data is fully
                        protected.</p>
                </div>
                <div class="flex flex-col items-center text-center p-6 rounded-2xl bg-slate-50 border border-slate-100">
                    <div
                        class="w-14 h-14 bg-white rounded-full flex items-center justify-center mb-4 shadow-sm text-sky-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 mb-2">Easy Returns</h3>
                    <p class="text-slate-500 text-sm">30-day no questions asked return policy so you can shop with
                        confidence.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Testimonials Section -->
    <div class="bg-slate-50 py-20 border-t border-slate-100 overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-black text-slate-900 tracking-tight mb-4">What Our Customers Say</h2>
                <div class="w-20 h-1.5 bg-sky-500 mx-auto rounded-full"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Testimonial 1 -->
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100 flex flex-col h-full relative">
                    <div class="absolute -top-4 -left-4 w-12 h-12 bg-sky-500 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-sky-500/30">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M14.017 21L14.017 18C14.017 16.8954 14.9124 16 16.017 16H19.017C19.5693 16 20.017 15.5523 20.017 15V9C20.017 8.44772 19.5693 8 19.017 8H16.017C15.4647 8 15.017 8.44772 15.017 9V12C15.017 12.5523 14.5693 13 14.017 13H12.017L12.017 6H22.017V15C22.017 18.3137 19.3307 21 16.017 21H14.017ZM2.01697 21L2.01697 18C2.01697 16.8954 2.9124 16 4.01697 16H7.01697C7.56925 16 8.01697 15.5523 8.01697 15V9C8.01697 8.44772 7.56925 8 7.01697 8H4.01697C3.46468 8 3.01697 8.44772 3.01697 9V12C3.01697 12.5523 2.56925 13 2.01697 13H0.0169702L0.0169702 6H10.017V15C10.017 18.3137 7.33068 21 4.01697 21H2.01697Z" /></svg>
                    </div>
                    <p class="text-slate-600 italic mb-8 flex-1 leading-relaxed">"The quality of the products is absolutely top-notch. I've bought several items from DigiCart and they never disappoint. Delivery was faster than expected!"</p>
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full bg-sky-100 flex items-center justify-center text-sky-600 font-bold">JD</div>
                        <div>
                            <h4 class="font-bold text-slate-900">John Doe</h4>
                            <p class="text-xs text-slate-500">Verified Customer</p>
                        </div>
                    </div>
                </div>

                <!-- Testimonial 2 -->
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100 flex flex-col h-full relative">
                    <div class="absolute -top-4 -left-4 w-12 h-12 bg-sky-500 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-sky-500/30">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M14.017 21L14.017 18C14.017 16.8954 14.9124 16 16.017 16H19.017C19.5693 16 20.017 15.5523 20.017 15V9C20.017 8.44772 19.5693 8 19.017 8H16.017C15.4647 8 15.017 8.44772 15.017 9V12C15.017 12.5523 14.5693 13 14.017 13H12.017L12.017 6H22.017V15C22.017 18.3137 19.3307 21 16.017 21H14.017ZM2.01697 21L2.01697 18C2.01697 16.8954 2.9124 16 4.01697 16H7.01697C7.56925 16 8.01697 15.5523 8.01697 15V9C8.01697 8.44772 7.56925 8 7.01697 8H4.01697C3.46468 8 3.01697 8.44772 3.01697 9V12C3.01697 12.5523 2.56925 13 2.01697 13H0.0169702L0.0169702 6H10.017V15C10.017 18.3137 7.33068 21 4.01697 21H2.01697Z" /></svg>
                    </div>
                    <p class="text-slate-600 italic mb-8 flex-1 leading-relaxed">"Excellent customer service! I had an issue with my size and they resolved it within 24 hours. The new pair fits perfectly and looks great."</p>
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full bg-sky-100 flex items-center justify-center text-sky-600 font-bold">AS</div>
                        <div>
                            <h4 class="font-bold text-slate-900">Anna Smith</h4>
                            <p class="text-xs text-slate-500">Verified Customer</p>
                        </div>
                    </div>
                </div>

                <!-- Testimonial 3 -->
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100 flex flex-col h-full relative">
                    <div class="absolute -top-4 -left-4 w-12 h-12 bg-sky-500 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-sky-500/30">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M14.017 21L14.017 18C14.017 16.8954 14.9124 16 16.017 16H19.017C19.5693 16 20.017 15.5523 20.017 15V9C20.017 8.44772 19.5693 8 19.017 8H16.017C15.4647 8 15.017 8.44772 15.017 9V12C15.017 12.5523 14.5693 13 14.017 13H12.017L12.017 6H22.017V15C22.017 18.3137 19.3307 21 16.017 21H14.017ZM2.01697 21L2.01697 18C2.01697 16.8954 2.9124 16 4.01697 16H7.01697C7.56925 16 8.01697 15.5523 8.01697 15V9C8.01697 8.44772 7.56925 8 7.01697 8H4.01697C3.46468 8 3.01697 8.44772 3.01697 9V12C3.01697 12.5523 2.56925 13 2.01697 13H0.0169702L0.0169702 6H10.017V15C10.017 18.3137 7.33068 21 4.01697 21H2.01697Z" /></svg>
                    </div>
                    <p class="text-slate-600 italic mb-8 flex-1 leading-relaxed">"The electronics collection is cutting edge. I bought the latest noise-cancelling headphones and the sound quality is mind-blowing. Highly recommended!"</p>
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full bg-sky-100 flex items-center justify-center text-sky-600 font-bold">MK</div>
                        <div>
                            <h4 class="font-bold text-slate-900">Mike K.</h4>
                            <p class="text-xs text-slate-500">Verified Customer</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Get In Touch Section -->
    <div class="bg-white py-24 border-t border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                <!-- Contact Info -->
                <div>
                    <h2 class="text-4xl font-black text-slate-900 tracking-tight mb-6">Get In Touch</h2>
                    <p class="text-slate-500 text-lg mb-10 leading-relaxed">Have a question or need assistance? Our team is here to help you. Reach out to us through any of the following channels.</p>
                    
                    <div class="space-y-8">
                        <div class="flex items-start gap-5">
                            <div class="w-12 h-12 rounded-2xl bg-sky-50 flex items-center justify-center text-sky-500 shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-900 mb-1">Email Us</h4>
                                <p class="text-slate-500">support@digicart.com</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-5">
                            <div class="w-12 h-12 rounded-2xl bg-sky-50 flex items-center justify-center text-sky-500 shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-900 mb-1">Call Us</h4>
                                <p class="text-slate-500">+1 (555) 000-0000</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-5">
                            <div class="w-12 h-12 rounded-2xl bg-sky-50 flex items-center justify-center text-sky-500 shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-900 mb-1">Our Location</h4>
                                <p class="text-slate-500">123 Commerce St, Digital City, DC 10101</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Form Card -->
                <div class="bg-slate-900 p-8 md:p-12 rounded-[2.5rem] shadow-2xl shadow-slate-900/20 text-white">
                    <h3 class="text-2xl font-bold mb-8">Send us a message</h3>
                    <form action="#" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-slate-400 mb-2">Full Name</label>
                                <input type="text" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 focus:outline-none focus:border-sky-500 transition-colors text-white placeholder-slate-500" placeholder="John Doe">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-400 mb-2">Email Address</label>
                                <input type="email" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 focus:outline-none focus:border-sky-500 transition-colors text-white placeholder-slate-500" placeholder="john@example.com">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-400 mb-2">Subject</label>
                            <input type="text" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 focus:outline-none focus:border-sky-500 transition-colors text-white placeholder-slate-500" placeholder="How can we help?">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-400 mb-2">Message</label>
                            <textarea rows="4" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 focus:outline-none focus:border-sky-500 transition-colors text-white placeholder-slate-500" placeholder="Write your message here..."></textarea>
                        </div>
                        <button type="button" class="w-full py-4 bg-sky-500 hover:bg-sky-400 text-slate-900 font-black rounded-xl transition-all active:scale-[0.98] shadow-lg shadow-sky-500/20">
                            Send Message
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>