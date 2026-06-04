<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('partials.head')
    @livewireStyles
    <link rel="stylesheet" href="https://unpkg.com/jodit@4.2.24/es2021/jodit.min.css">
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.4.1/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.4.1/dist/js/tom-select.complete.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
</head>

<body class="min-h-screen bg-custom dark:bg-zinc-800">
    <flux:sidebar sticky stashable class="border-e border-sky-200/50 bg-gradient-to-b from-sky-100 via-slate-100 to-indigo-100 dark:from-sky-950/80 dark:via-slate-900 dark:to-indigo-950/80 sidebar fixed left-0 top-0 h-screen overflow-y-auto" style="position: fixed !important; z-index: 50;">
        <flux:sidebar.toggle class="lg:hidden text-sky-800" icon="x-mark" />

        <a href="{{ route('dashboard') }}" class="me-5 flex items-center space-x-2 rtl:space-x-reverse">
            <x-app-logo />
        </a>

        <flux:navlist variant="outline" class="mt-4 bg-white/30 dark:bg-slate-900/30 rounded-lg p-2">
            <flux:navlist.group :heading="__('Platform')" class="grid">
                <flux:navlist.item icon="home" :href="route('admin.dashboard')"
                    :current="request()->routeIs('admin.dashboard')" class="text-slate-800 hover:bg-sky-200 hover:text-sky-900 data-[active]:bg-sky-300 data-[active]:text-sky-900 font-medium {{ request()->routeIs('admin.dashboard') ? 'active bg-sky-200 text-sky-900' : '' }}">
                    {{ __('Dashboard') }}</flux:navlist.item>
                    
                <flux:navlist.item icon="squares-2x2" :href="route('admin.category.index')"
                    :current="request()->routeIs('admin.category.*')" class="text-slate-800 hover:bg-sky-200 hover:text-sky-900 data-[active]:bg-sky-300 data-[active]:text-sky-900 font-medium {{ request()->routeIs('admin.category.*') ? 'active bg-sky-200 text-sky-900' : '' }}">
                    {{ __('Categories') }}</flux:navlist.item>
                </flux:navlist.group>

                <flux:navlist.item icon="tag" :href="route('admin.attribute.index')"
                        :current="request()->routeIs('admin.attribute.*')" class="text-slate-800 hover:bg-sky-200 hover:text-sky-900 data-[active]:bg-sky-300 data-[active]:text-sky-900 font-medium {{ request()->routeIs('admin.attribute.*') ? 'active bg-sky-200 text-sky-900' : '' }}">
                        {{ __('Attributes') }}</flux:navlist.item>

                <flux:navlist.item icon="tag" :href="route('admin.tag.index')"
                        :current="request()->routeIs('admin.tag.*')" class="text-slate-800 hover:bg-sky-200 hover:text-sky-900 data-[active]:bg-sky-300 data-[active]:text-sky-900 font-medium {{ request()->routeIs('admin.tag.*') ? 'active bg-sky-200 text-sky-900' : '' }}">
                        {{ __('Tags') }}</flux:navlist.item>

                <flux:navlist.item icon="shopping-bag" :href="route('admin.product.index')"
                        :current="request()->routeIs('admin.product.*')" class="text-slate-800 hover:bg-sky-200 hover:text-sky-900 data-[active]:bg-sky-300 data-[active]:text-sky-900 font-medium {{ request()->routeIs('admin.product.*') ? 'active bg-sky-200 text-sky-900' : '' }}">
                        {{ __('Products') }}</flux:navlist.item>

                <flux:navlist.item icon="shopping-cart" :href="route('admin.order.index')"
                        :current="request()->routeIs('admin.order.*')" class="text-slate-800 hover:bg-sky-200 hover:text-sky-900 data-[active]:bg-sky-300 data-[active]:text-sky-900 font-medium {{ request()->routeIs('admin.order.*') ? 'active bg-sky-200 text-sky-900' : '' }}">
                        {{ __('Orders') }}</flux:navlist.item>

                <flux:navlist.item icon="users" :href="route('admin.customer.index')"
                        :current="request()->routeIs('admin.customer.*')" class="text-slate-800 hover:bg-sky-200 hover:text-sky-900 data-[active]:bg-sky-300 data-[active]:text-sky-900 font-medium {{ request()->routeIs('admin.customer.*') ? 'active bg-sky-200 text-sky-900' : '' }}">
                        {{ __('Customers') }}</flux:navlist.item>

                <flux:navlist.item icon="star" :href="route('admin.reviews.index')"
                        :current="request()->routeIs('admin.reviews.*')" class="text-slate-800 hover:bg-sky-200 hover:text-sky-900 data-[active]:bg-sky-300 data-[active]:text-sky-900 font-medium {{ request()->routeIs('admin.reviews.*') ? 'active bg-sky-200 text-sky-900' : '' }}">
                        {{ __('Reviews') }}</flux:navlist.item>

                <flux:navlist.group :heading="__('Settings')" class="grid">
                    <flux:navlist.item icon="cog-6-tooth" :href="route('admin.setting.index')"
                        :current="request()->routeIs('admin.setting.*')" class="text-slate-800 hover:bg-sky-200 hover:text-sky-900 data-[active]:bg-sky-300 data-[active]:text-sky-900 font-medium {{ request()->routeIs('admin.setting.*') ? 'active bg-sky-200 text-sky-900' : '' }}">
                        {{ __('Site Settings') }}</flux:navlist.item>

                    <flux:navlist.item icon="circle-stack" :href="route('admin.backup.index')"
                        :current="request()->routeIs('admin.backup.*')" class="text-slate-800 hover:bg-sky-200 hover:text-sky-900 data-[active]:bg-sky-300 data-[active]:text-sky-900 font-medium {{ request()->routeIs('admin.backup.*') ? 'active bg-sky-200 text-sky-900' : '' }}">
                        {{ __('Backup Management') }}</flux:navlist.item>

                    <flux:navlist.item icon="exclamation-triangle" :href="route('admin.system.reset')"
                        :current="request()->routeIs('admin.system.reset')" class="text-slate-800 hover:bg-sky-200 hover:text-sky-900 data-[active]:bg-sky-300 data-[active]:text-sky-900 font-medium {{ request()->routeIs('admin.system.reset') ? 'active bg-sky-200 text-sky-900' : '' }}">
                        {{ __('System Reset') }}</flux:navlist.item>

                    <flux:navlist.item icon="arrow-up-tray" :href="route('admin.import.index')"
                        :current="request()->routeIs('admin.import.*')" class="text-slate-800 hover:bg-sky-200 hover:text-sky-900 data-[active]:bg-sky-300 data-[active]:text-sky-900 font-medium {{ request()->routeIs('admin.import.*') ? 'active bg-sky-200 text-sky-900' : '' }}">
                        {{ __('Import') }}</flux:navlist.item>

                </flux:navlist.group>
        </flux:navlist>
        <flux:spacer />

        <!-- Desktop User Menu -->
        <flux:dropdown position="bottom" align="start">
            <flux:profile :name="auth()->user()->name" :initials="auth()->user()->initials()"
                icon-trailing="chevrons-up-down" />

            <flux:menu class="w-[220px]">
                <flux:menu.radio.group>
                    <div class="p-0 text-sm font-normal">
                        <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                            <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                <span
                                    class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white">
                                    {{ auth()->user()->initials() }}
                                </span>
                            </span>

                            <div class="grid flex-1 text-start text-sm leading-tight">
                                <span class="truncate font-semibold">{{ auth()->user()->name }} </span>
                                <span
                                    class="truncate text-xs">{{ Auth::user()->roles()->pluck('name')->implode(', ') }}</span>
                            </div>
                        </div>
                    </div>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <form method="POST" action="{{ route('admin.logout') }}" class="w-full">
                    @csrf
                    <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                        {{ __('Log Out') }}
                    </flux:menu.item>
                </form>
            </flux:menu>
        </flux:dropdown>
    </flux:sidebar>

    <!-- Mobile User Menu -->
    <flux:header class="lg:hidden">
        <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

        <flux:spacer />

        <flux:dropdown position="top" align="end">
            <flux:profile :initials="auth()->user()->initials()" icon-trailing="chevron-down" />

            <flux:menu>
                <flux:menu.radio.group>
                    <div class="p-0 text-sm font-normal">
                        <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                            <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                <span
                                    class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white">
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

                <form method="POST" action="{{ route('admin.logout') }}" class="w-full">
                    @csrf
                    <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                        {{ __('Log Out') }}
                    </flux:menu.item>
                </form>
            </flux:menu>
        </flux:dropdown>
    </flux:header>

    {{ $slot }}
    @livewireScripts
    <script src="https://unpkg.com/jodit@4.2.24/es2021/jodit.min.js"></script>
    {{-- make sure all @push('scripts') blocks render! --}}
    @stack('scripts')
    @stack('styles')

    <x-toast />
    @fluxScripts
</body>

</html>
