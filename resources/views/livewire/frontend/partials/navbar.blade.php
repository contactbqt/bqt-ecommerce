<div>
    <nav class="bg-white border-b border-slate-200 sticky top-0 z-50 shadow-sm">
        <!-- Top Level: Logo, Search, Actions -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex items-center justify-between gap-8">
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center">
                    <a href="{{ route('home') }}" class="text-3xl font-black tracking-tighter text-slate-900 group">
                        <x-app-logo />
                    </a>
                </div>

                <!-- Search Bar (Expanded in center) -->
                <div class="hidden md:flex flex-1 max-w-2xl">
                    <form action="{{ route('shop') }}" method="GET" class="relative w-full">
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="Search for products, brands and categories..." 
                               class="w-full pl-5 pr-12 py-3 border-2 border-slate-100 rounded-2xl focus:outline-none focus:border-sky-500 focus:ring-4 focus:ring-sky-500/10 text-sm bg-slate-50 hover:bg-white transition-all shadow-inner" />
                        <button type="submit" class="absolute right-4 top-3 text-slate-400 hover:text-sky-500 transition-colors">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </button>
                    </form>
                </div>

                <!-- Action Icons -->
                <div class="flex items-center space-x-5">
                    <livewire:frontend.cart.cart-widget />

                    @auth
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" @click.away="open = false" class="text-slate-500 hover:text-sky-600 transition-colors group flex items-center gap-3 focus:outline-none">
                                <div class="p-2.5 bg-slate-50 rounded-2xl group-hover:bg-sky-50 border border-slate-200 transition-colors">
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <div class="hidden xl:flex flex-col items-start leading-tight">
                                    <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Account</span>
                                    <span class="text-sm font-bold text-slate-700 group-hover:text-sky-600 transition-colors">{{ Str::before(Auth::user()->name, ' ') }}</span>
                                </div>
                            </button>

                            <div x-show="open" 
                                 x-transition:enter="transition ease-out duration-100" 
                                 x-transition:enter-start="transform opacity-0 scale-95" 
                                 x-transition:enter-end="transform opacity-100 scale-100" 
                                 x-transition:leave="transition ease-in duration-75" 
                                 x-transition:leave-start="transform opacity-100 scale-100" 
                                 x-transition:leave-end="transform opacity-0 scale-95" 
                                 class="absolute right-0 mt-3 w-56 bg-white rounded-2xl shadow-2xl border border-slate-100 py-2 z-[60] overflow-hidden"
                                 style="display: none;">
                                <div class="px-4 py-3 border-b border-slate-50 bg-slate-50/50 mb-1">
                                    <p class="text-xs font-black text-slate-400 uppercase tracking-widest">Signed in as</p>
                                    <p class="text-sm font-bold text-slate-900 truncate">{{ Auth::user()->email }}</p>
                                </div>
                                <a href="{{ route('settings.profile') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm font-bold text-slate-600 hover:bg-sky-50 hover:text-sky-600 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    My Profile
                                </a>
                                <a href="{{ route('user.orders') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm font-bold text-slate-600 hover:bg-sky-50 hover:text-sky-600 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                    My Orders
                                </a>
                                @if( get_setting('PRODUCT_WISHLIST', '0') === '1' )
                                    <a href="{{ route('user.wishlist.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm font-bold text-slate-600 hover:bg-sky-50 hover:text-sky-600 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                                        Wishlist
                                    </a>
                                @endif
                                <div class="border-t border-slate-50 mt-1 pt-1">
                                    <form method="POST" action="{{ route('logout') }}" x-data>
                                        @csrf
                                        <button type="submit" class="flex items-center gap-3 w-full px-4 py-2.5 text-sm font-bold text-red-500 hover:bg-red-50 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                            Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-slate-500 hover:text-sky-600 transition-colors group flex items-center gap-3">
                            <div class="p-2.5 bg-slate-50 rounded-2xl group-hover:bg-sky-50 border border-slate-200 transition-colors">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <div class="hidden xl:flex flex-col items-start leading-tight">
                                <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Join Us</span>
                                <span class="text-sm font-bold text-slate-700 group-hover:text-sky-600 transition-colors">Sign In</span>
                            </div>
                        </a>
                    @endauth
                </div>
            </div>
        </div>

        <!-- Bottom Level: Navigation Bar -->
        <div class="border-t border-slate-100 bg-slate-50/50 backdrop-blur-sm hidden lg:block">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center space-x-10 h-12">
                    <a href="{{ route('home') }}" class="text-[14px] font-bold text-slate-600 hover:text-sky-600 transition-all flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        Home
                    </a>
                    
                    <!-- Categories Megamenu Trigger -->
                    <div x-data="{ open: false }" @mouseleave="open = false" class="flex h-full">
                        <button @mouseenter="open = true" class="text-[14px] font-bold text-slate-600 hover:text-sky-600 transition-all outline-none flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/></svg>
                            Shop Categories
                            <svg class="h-3 w-3 transition-transform duration-200" :class="{'rotate-180': open}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <!-- Megamenu Panel -->
                        <div x-show="open" 
                             @mouseenter="open = true"
                             x-transition:enter="transition ease-out duration-200" 
                             x-transition:enter-start="opacity-0 translate-y-1" 
                             x-transition:enter-end="opacity-100 translate-y-0" 
                             x-transition:leave="transition ease-in duration-150" 
                             class="absolute inset-x-0 top-full w-screen bg-white border-b border-slate-200 shadow-2xl z-50 overflow-hidden"
                             style="display: none;">
                            
                            <div class="max-w-7xl mx-auto grid grid-cols-4 gap-12 p-12 bg-white relative">
                                @forelse($categories as $cat)
                                    <div>
                                        <h3 class="text-xs font-black tracking-widest uppercase text-slate-900 mb-6 flex items-center gap-2">
                                           <span class="w-1.5 h-6 bg-sky-500 rounded-full"></span>
                                           <a href="{{ route('shop', ['category' => $cat->slug]) }}" class="hover:text-sky-600 transition-colors">{{ $cat->category_name }}</a>
                                        </h3>
                                        @if($cat->children->isNotEmpty())
                                            <ul class="space-y-4">
                                                @foreach($cat->children as $child)
                                                    <li class="group/item">
                                                        {{-- Level 2 --}}
                                                        <a href="{{ route('shop', ['category' => $child->slug]) }}" class="text-[14px] font-bold text-slate-700 hover:text-sky-600 transition-all flex items-center justify-between">
                                                            {{ $child->category_name }}
                                                        </a>

                                                        @if($child->children->isNotEmpty())
                                                            <ul class="mt-2 ml-3 space-y-2 border-l-2 border-slate-100 pl-3">
                                                                @foreach($child->children as $grandchild)
                                                                    <li>
                                                                        {{-- Level 3 --}}
                                                                        <a href="{{ route('shop', ['category' => $grandchild->slug]) }}" class="text-[13px] font-semibold text-slate-500 hover:text-sky-600 transition-colors flex items-center gap-2">
                                                                            <span class="w-1 h-1 bg-slate-300 rounded-full"></span>
                                                                            {{ $grandchild->category_name }}
                                                                        </a>

                                                                        @if($grandchild->children->isNotEmpty())
                                                                            <ul class="mt-1 ml-4 space-y-1">
                                                                                @foreach($grandchild->children as $greatgrandchild)
                                                                                    <li>
                                                                                        {{-- Level 4 --}}
                                                                                        <a href="{{ route('shop', ['category' => $greatgrandchild->slug]) }}" class="text-[12px] text-slate-400 hover:text-sky-600 transition-colors block pl-2 border-l border-slate-50">
                                                                                            {{ $greatgrandchild->category_name }}
                                                                                        </a>
                                                                                    </li>
                                                                                @endforeach
                                                                            </ul>
                                                                        @endif
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                        @endif
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </div>
                                @empty
                                    <div class="col-span-4 text-center text-slate-400 text-sm py-12">No categories found.</div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('shop') }}" class="text-[14px] font-bold text-slate-600 hover:text-sky-600 transition-all flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                        All Products
                    </a>
                    <a href="#" class="text-[14px] font-bold text-slate-600 hover:text-sky-600 transition-all flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        Today's Deals
                    </a>
                    <a href="#" class="text-[14px] font-bold text-slate-600 hover:text-sky-600 transition-all flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                        Blog
                    </a>
                    <a href="#" class="text-[14px] font-bold text-slate-600 hover:text-sky-600 transition-all flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        About Us
                    </a>
                </div>
            </div>
        </div>
    </nav>
</div>
