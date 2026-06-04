<x-layouts.frontend>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="lg:grid lg:grid-cols-12 lg:gap-x-10">
            <!-- Sidebar -->
            <aside class="py-6 px-2 sm:px-6 lg:py-0 lg:px-0 lg:col-span-3">
                <nav class="space-y-1 bg-white border border-slate-200 rounded-[1.5rem] p-4 shadow-sm">
                    <div class="px-3 py-2 mb-2 text-xs font-black text-slate-400 uppercase tracking-widest">
                        My Account
                    </div>
                    
                    <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'bg-sky-50 text-sky-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }} group flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-colors">
                        <flux:icon name="home" class="{{ request()->routeIs('dashboard') ? 'text-sky-500' : 'text-slate-400 group-hover:text-slate-500' }} flex-shrink-0 -ml-1 mr-3 h-5 w-5" />
                        <span class="truncate">Dashboard</span>
                    </a>

                    <a href="{{ route('user.orders') }}" class="{{ request()->routeIs('user.orders*') ? 'bg-sky-50 text-sky-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }} group flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-colors">
                        <flux:icon name="shopping-bag" class="{{ request()->routeIs('user.orders*') ? 'text-sky-500' : 'text-slate-400 group-hover:text-slate-500' }} flex-shrink-0 -ml-1 mr-3 h-5 w-5" />
                        <span class="truncate">My Orders</span>
                    </a>

                    <a href="{{ route('user.address.index') }}" class="{{ request()->routeIs('user.address.index*') ? 'bg-sky-50 text-sky-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }} group flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-colors">
                        <flux:icon name="map-pin" class="{{ request()->routeIs('user.address.index*') ? 'text-sky-500' : 'text-slate-400 group-hover:text-slate-500' }} flex-shrink-0 -ml-1 mr-3 h-5 w-5" />
                        <span class="truncate">Address Book</span>
                    </a>

                    <a href="{{ route('user.wishlist.index') }}" class="{{ request()->routeIs('user.wishlist.index*') ? 'bg-sky-50 text-sky-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }} group flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-colors">
                        <flux:icon name="heart" class="{{ request()->routeIs('user.wishlist.index*') ? 'text-sky-500' : 'text-slate-400 group-hover:text-slate-500' }} flex-shrink-0 -ml-1 mr-3 h-5 w-5" />
                        <span class="truncate">My Wishlist</span>
                    </a>

                    <a href="{{ route('settings.profile') }}" class="{{ request()->routeIs('settings.profile') ? 'bg-sky-50 text-sky-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }} group flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-colors">
                        <flux:icon name="user" class="{{ request()->routeIs('settings.profile') ? 'text-sky-500' : 'text-slate-400 group-hover:text-slate-500' }} flex-shrink-0 -ml-1 mr-3 h-5 w-5" />
                        <span class="truncate">Profile Info</span>
                    </a>

                    <a href="{{ route('settings.password') }}" class="{{ request()->routeIs('settings.password') ? 'bg-sky-50 text-sky-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }} group flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-colors">
                        <flux:icon name="key" class="{{ request()->routeIs('settings.password') ? 'text-sky-500' : 'text-slate-400 group-hover:text-slate-500' }} flex-shrink-0 -ml-1 mr-3 h-5 w-5" />
                        <span class="truncate">Change Password</span>
                    </a>

                    <div class="pt-4 mt-4 border-t border-slate-100">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left text-red-600 hover:bg-red-50 hover:text-red-700 group flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-colors">
                                <flux:icon name="arrow-right-start-on-rectangle" class="text-red-400 group-hover:text-red-500 flex-shrink-0 -ml-1 mr-3 h-5 w-5" />
                                <span class="truncate">Sign Out</span>
                            </button>
                        </form>
                    </div>
                </nav>
            </aside>

            <!-- Main content -->
            <main class="lg:col-span-9 pt-6 lg:pt-0">
                <div class="bg-white border border-slate-200 rounded-[2rem] p-8 shadow-sm">
                    {{ $slot }}
                </div>
            </main>
        </div>
    </div>
</x-layouts.frontend>