<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'DigiCart') }}</title>


    <!-- Fonts -->
    <link relpreconnect="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800,900&display=swap" rel="stylesheet" />

    <!-- Scripts / Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="font-sans antialiased text-slate-800 bg-slate-50 flex flex-col min-h-screen relative selection:bg-sky-500 selection:text-white">

    <!-- Navbar Megamenu -->
    <div class="relative z-50 bg-white">
        <livewire:frontend.partials.navbar />
    </div>

    <!-- Main Content -->
    <main class="flex-grow flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 relative overflow-hidden">
        <!-- Decorative Background -->
        <div class="absolute inset-0 z-0 opacity-30 pointer-events-none">
            <div class="absolute -top-24 -left-24 w-96 h-96 rounded-full bg-sky-200 blur-3xl"></div>
            <div class="absolute top-1/2 right-0 w-80 h-80 rounded-full bg-indigo-200 blur-3xl"></div>
        </div>

        <div class="w-full max-w-md bg-white border border-slate-200 rounded-[2.5rem] p-8 md:p-10 shadow-2xl shadow-slate-200/50 relative z-10">
            {{ $slot }}
        </div>
    </main>

    <!-- Simple Footer -->
    <footer class="bg-white border-t border-slate-200 relative z-10 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="md:flex md:items-center md:justify-between">
                <div class="flex justify-center md:justify-start">
                    <img src="{{ asset('assets/images/logo.png') }}" alt="DigiCart" class="w-50">
                </div>
                <div class="mt-8 md:mt-0">
                    <p class="text-center text-sm text-slate-500">
                        &copy; {{ date('Y') }} DigiCart. All rights reserved.
                    </p>
                </div>
            </div>
        </div>
    </footer>

    @livewireScripts
    @fluxScripts
</body>
</html>