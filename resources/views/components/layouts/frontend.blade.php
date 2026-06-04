<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', get_setting('SITE_TITLE', config('app.name', 'DigiCart'))) | {{ get_setting('SITE_NAME', config('app.name', 'DigiCart')) }}</title>
    <meta name="description" content="@yield('meta_description', '')">
    <meta name="keywords" content="@yield('meta_keywords', '')">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('og_title', get_setting('SITE_TITLE', config('app.name', 'DigiCart')))">
    <meta property="og:description" content="@yield('og_description', get_setting('SITE_TITLE', config('app.name', 'DigiCart')))">
    <meta property="og:image" content="@yield('og_image', asset('storage/' . get_setting('SITE_LOGO')))">
    <meta property="og:site_name" content="{{ get_setting('SITE_NAME', config('app.name', 'DigiCart')) }}">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta property="twitter:title" content="@yield('og_title', get_setting('SITE_TITLE', config('app.name', 'DigiCart')))">
    <meta property="twitter:description" content="@yield('og_description', get_setting('SITE_TITLE', config('app.name', 'DigiCart')))">
    <meta property="twitter:image" content="@yield('og_image', asset('storage/' . get_setting('SITE_LOGO')))">

    <!-- Fonts -->
    <link relpreconnect="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800,900&display=swap" rel="stylesheet" />

    <!-- Fav icon-->
    @php
        $favicon = get_setting('FAVICON');
        $faviconPath = $favicon ? asset('storage/' . $favicon) : asset('assets/images/favicon.png');
    @endphp
    <link rel="icon" type="image/x-icon" href="{{ $faviconPath }}">

    <!-- Scripts / Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    
    <!-- jQuery and jQuery UI for Range Slider -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    
    <!-- Alpine JS is loaded by Livewire 3 automatically -->
</head>
<body class="font-sans antialiased text-slate-800 bg-slate-50 relative selection:bg-sky-500 selection:text-white">

    <!-- Navbar Megamenu -->
    <livewire:frontend.partials.navbar />

    <!-- Main Content -->
    <main class="min-h-screen">
        <!-- Toast Notification Area -->
        @if (session()->has('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" class="fixed bottom-10 right-10 z-[100] animate-bounce-subtle">
                <div class="bg-emerald-600 text-white px-6 py-4 rounded-2xl shadow-2xl flex items-center gap-4 border border-emerald-500/20 backdrop-blur-md">
                    <div class="bg-white/20 p-2 rounded-xl">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <div>
                        <p class="font-black uppercase tracking-widest text-[10px] opacity-80">Success</p>
                        <p class="font-bold text-sm">{{ session('success') }}</p>
                    </div>
                    <button @click="show = false" class="ml-4 opacity-50 hover:opacity-100 transition-opacity">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>
        @endif

        @if (session()->has('info'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" class="fixed bottom-10 right-10 z-[100] animate-bounce-subtle">
                <div class="bg-sky-600 text-white px-6 py-4 rounded-2xl shadow-2xl flex items-center gap-4 border border-sky-500/20 backdrop-blur-md">
                    <div class="bg-white/20 p-2 rounded-xl">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <p class="font-black uppercase tracking-widest text-[10px] opacity-80">Notice</p>
                        <p class="font-bold text-sm">{{ session('info') }}</p>
                    </div>
                    <button @click="show = false" class="ml-4 opacity-50 hover:opacity-100 transition-opacity">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>
        @endif

        @if (session()->has('error'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" class="fixed bottom-10 right-10 z-[100] animate-bounce-subtle">
                <div class="bg-red-600 text-white px-6 py-4 rounded-2xl shadow-2xl flex items-center gap-4 border border-red-500/20 backdrop-blur-md">
                    <div class="bg-white/20 p-2 rounded-xl">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                    <div>
                        <p class="font-black uppercase tracking-widest text-[10px] opacity-80">Error</p>
                        <p class="font-bold text-sm">{{ session('error') }}</p>
                    </div>
                    <button @click="show = false" class="ml-4 opacity-50 hover:opacity-100 transition-opacity">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>
        @endif

        {{ $slot }}
    </main>

    <!-- Simple Footer -->
    <footer class="bg-white border-t border-slate-200 mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-center">
                <!-- Logo -->
                <div class="flex justify-center md:justify-start">
                    @php
                        $logo = get_setting('SITE_LOGO');
                        $logoPath = $logo ? asset('storage/' . $logo) : asset('assets/images/logo.png');
                        $siteName = get_setting('SITE_NAME', config('app.name', 'DigiCart'));
                    @endphp
                    <img src="{{ $logoPath }}" alt="{{ $siteName }}" class="w-50">
                </div>
                
                <!-- Links -->
                <div class="flex justify-center gap-6 text-sm font-bold text-slate-500">
                    <a href="{{ route('terms') }}" class="hover:text-sky-600 transition-colors">Terms of Service</a>
                    <a href="{{ route('privacy') }}" class="hover:text-sky-600 transition-colors">Privacy Policy</a>
                </div>

                <!-- Copyright -->
                <div class="text-center md:text-right">
                    <p class="text-sm text-slate-500">
                        {{ get_setting('COPYRIGHT_TEXT', config('app.name', 'DigiCart © ' . date('Y') . '. All rights reserved.')) }}
                    </p>
                </div>
            </div>
        </div>
    </footer>

    @livewireScripts
    @stack('scripts')
</body>
</html>
