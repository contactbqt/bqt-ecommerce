<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light">

<head>
    @include('partials.head')
    @livewireStyles
    <link rel="stylesheet" href="https://unpkg.com/jodit@4.2.24/es2021/jodit.min.css">
    <link rel="stylesheet" href="{{ asset('resources/css/app.css') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Progress bar for Livewire navigation -->
    <div wire:loading class="fixed top-0 left-0 right-0 z-50">
        <div class="h-1 bg-gradient-to-r from-blue-500 to-purple-500 animate-pulse"></div>
    </div>

    <!-- Loading overlay -->
    <div wire:loading class="fixed inset-0 bg-black bg-opacity-25 z-40 flex items-center justify-center">
        <div class="bg-white rounded-lg p-4 flex items-center space-x-3">
            <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-500"></div>
            <span class="text-gray-700">Loading...</span>
        </div>
    </div>
</head>

<body class="min-h-screen bg-white">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="sidebar-container hidden lg:flex lg:flex-col">
            <!-- Mobile close button -->
            <div class="lg:hidden flex items-center justify-between px-4 py-3 border-gray-200">
                <flux:navlist.item :href="route('home')" wire:navigate class="flex items-center space-x-2 p-0">
                    <img src="{{ asset('assets/frontend/img/logo.png') }}" alt="Logo" width="150px">
                </flux:navlist.item>
                <button type="button" class="text-gray-500 hover:text-gray-600" onclick="window.toggleMobileSidebar()">
                    <flux:icon name="x-mark" class="h-6 w-6" />
                </button>
            </div>

            <!-- Desktop Logo -->
            <div class="hidden lg:flex items-center px-6 py-4 border-gray-200">
                <flux:navlist.item :href="route('home')" wire:navigate class="flex items-center space-x-2 p-0">
                    <img src="{{ asset('assets/frontend/img/logo.png') }}" alt="Logo" width="200px">
                </flux:navlist.item>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 px-4 py-4 space-y-2">
                <div class="space-y-1">
                    <div class="px-3 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        Platform
                    </div>
                    <flux:navlist.item icon="home" :href="route('dashboard')"
                        :current="request()->routeIs('dashboard')" wire:navigate>
                        Dashboard
                    </flux:navlist.item>
                    <flux:navlist.item icon="user" :href="route('patient.profile')"
                        :current="request()->routeIs('patient.profile')" wire:navigate>
                        Profile
                    </flux:navlist.item>
                    <flux:navlist.item icon="calendar-date-range" :href="route('patient.appointment')"
                        :current="request()->routeIs('patient.appointment')" wire:navigate>
                        Appointments
                    </flux:navlist.item>
                </div>
            </nav>

            <!-- User Menu -->
            <div class="px-4 py-4 border-t border-gray-200">
                <flux:dropdown position="top" align="start">
                    <flux:profile :name="auth()->user()->name" :initials="auth()->user()->initials()"
                        icon-trailing="chevrons-up-down" />

                    <flux:menu class="w-[220px]">
                        <flux:menu.radio.group>
                            <div class="p-0 text-sm font-normal">
                                <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                    <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                        <span
                                            class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black">
                                            {{ auth()->user()->initials() }}
                                        </span>
                                    </span>
                                    <div class="grid flex-1 text-start text-sm leading-tight">
                                        <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                        <span
                                            class="truncate text-xs">{{ Auth::user()->roles()->pluck('name')->implode(', ') }}</span>
                                    </div>
                                </div>
                            </div>
                        </flux:menu.radio.group>
                        <flux:menu.separator />
                        <form method="POST" action="{{ route('logout') }}" class="w-full">
                            @csrf
                            <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle"
                                class="w-full">
                                {{ __('Log Out') }}
                            </flux:menu.item>
                        </form>
                    </flux:menu>
                </flux:dropdown>
            </div>
        </div>

        <!-- Mobile overlay -->
        <div id="mobile-overlay" class="mobile-overlay"></div>

        <!-- Main content -->
        <div class="main-content flex flex-col flex-1">
            <!-- Top Navigation Bar (Desktop & Mobile) -->
            <div class="flex items-center justify-between px-4 py-3 top-nav-bg w-full sticky top-0 z-40">
                <!-- Mobile menu button (only visible on mobile) -->
                <button type="button" class="lg:hidden text-white hover:text-gray-200" id="mobile-toggle"
                    onclick="window.toggleMobileSidebar()">
                    <flux:icon name="bars-3" class="h-6 w-6" />
                </button>

                <!-- Desktop title (only visible on desktop) -->
                <div class="hidden lg:block">
                    <h1 class="text-lg font-semibold text-white">{{ $title ?? '' }}</h1>
                </div>

                <!-- User dropdown (always visible) -->
                <div class="flex items-center">
                    <flux:dropdown position="top" align="end">
                        <flux:profile :initials="auth()->user()->initials()" icon-trailing="chevron-down" />
                        <flux:menu>
                            <flux:menu.radio.group>
                                <div class="p-0 text-sm font-normal">
                                    <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                        <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                            <span
                                                class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black">
                                                {{ auth()->user()->initials() }}
                                            </span>
                                        </span>
                                        <div class="grid flex-1 text-start text-sm leading-tight">
                                            <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                            <span
                                                class="truncate text-xs">{{ Auth::user()->roles()->pluck('name')->implode(', ') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </flux:menu.radio.group>
                            <flux:menu.separator />
                            <flux:menu.item icon="home" :href="route('home')" wire:navigate>Home</flux:menu.item>
                            <flux:menu.item icon="user" :href="route('patient.profile')" wire:navigate>My Profile
                            </flux:menu.item>
                            <flux:menu.item icon="calendar-date-range" :href="route('patient.appointment')"
                                wire:navigate>Appointment History</flux:menu.item>
                            <flux:menu.separator />
                            <form method="POST" action="{{ route('logout') }}" class="w-full">
                                @csrf
                                <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle"
                                    class="w-full">
                                    {{ __('Log Out') }}
                                </flux:menu.item>
                            </form>
                        </flux:menu>
                    </flux:dropdown>
                </div>
            </div>


            <!-- Page content -->
            <main class="flex-1 overflow-y-auto min-h-0">
                <div class="h-full p-0 sm:p-0">
                    {{ $slot }}
                </div>
            </main>
        </div>
    </div>

    <script>
        // Toggle sidebar function - available globally for onclick handlers
        window.toggleMobileSidebar = function() {
            const sidebar = document.querySelector('.sidebar-container');
            const overlay = document.getElementById('mobile-overlay');

            if (sidebar && overlay) {
                sidebar.classList.toggle('mobile-open');
                overlay.classList.toggle('show');
            }
        };

        // Handle overlay and outside clicks
        document.addEventListener('click', function(event) {
            const sidebar = document.querySelector('.sidebar-container');
            const overlay = document.getElementById('mobile-overlay');

            // Handle overlay click
            if (event.target.id === 'mobile-overlay' || event.target.classList.contains('mobile-overlay')) {
                window.toggleMobileSidebar();
                return;
            }

            // Close sidebar when clicking outside on mobile
            if (window.innerWidth <= 1023 && sidebar && overlay) {
                if (sidebar.classList.contains('mobile-open') &&
                    !sidebar.contains(event.target) &&
                    !event.target.closest('#mobile-toggle')) {
                    sidebar.classList.remove('mobile-open');
                    overlay.classList.remove('show');
                }
            }
        });

        // Auto-close sidebar on Livewire navigation
        document.addEventListener('livewire:navigating', function() {
            const sidebar = document.querySelector('.sidebar-container');
            const overlay = document.getElementById('mobile-overlay');
            if (sidebar && overlay) {
                sidebar.classList.remove('mobile-open');
                overlay.classList.remove('show');
            }
        });
    </script>

    @livewireScripts
    <script src="https://unpkg.com/jodit@4.2.24/es2021/jodit.min.js"></script>

    {{-- make sure all @push('scripts') blocks render! --}}
    @stack('scripts')
    @stack('styles')

    @fluxScripts
</body>

</html>
