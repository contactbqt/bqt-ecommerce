<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maintenance Mode | {{ get_setting('SITE_NAME', config('app.name', 'DigiCart')) }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800,900&display=swap" rel="stylesheet" />
    
    @php
        $favicon = get_setting('FAVICON');
        $faviconPath = $favicon ? asset('storage/' . $favicon) : asset('assets/images/favicon.png');
    @endphp
    <link rel="icon" type="image/x-icon" href="{{ $faviconPath }}">

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .bg-dots-darker {
            background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(0,0,0,0.07)'/%3E%3C/svg%3E");
        }
    </style>
</head>
<body class="antialiased bg-slate-50 text-slate-800">
    <div class="relative flex items-top justify-center min-h-screen bg-dots-darker sm:items-center py-4 sm:pt-0">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="flex flex-col items-center justify-center pt-8 sm:justify-start sm:pt-0">
                
                <!-- Logo -->
                <div class="mb-12 transition-transform hover:scale-105 duration-300">
                    @php
                        $logo = get_setting('SITE_LOGO');
                        $logoPath = $logo ? asset('storage/' . $logo) : asset('assets/images/logo.png');
                    @endphp
                    <img src="{{ $logoPath }}" alt="{{ get_setting('SITE_NAME', config('app.name')) }}" class="h-16 w-auto">
                </div>

                <div class="bg-white overflow-hidden shadow-2xl shadow-slate-200/50 rounded-[3rem] border border-slate-100 p-10 md:p-16 text-center">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-sky-50 rounded-3xl text-sky-600 mb-8 animate-pulse">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                    </div>

                    <h1 class="text-4xl font-black text-slate-900 mb-6 uppercase tracking-tight leading-tight">
                        We'll be back <br>
                        <span class="text-sky-600">very soon!</span>
                    </h1>

                    <p class="text-slate-500 text-lg font-medium leading-relaxed mb-10 max-w-sm mx-auto">
                        {{ get_setting('MAINTENANCE_MESSAGE') ?: get_setting('SITE_NAME', config('app.name')) . ' is currently undergoing scheduled maintenance to improve your shopping experience.' }}
                    </p>

                    <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                        <div class="flex items-center gap-2 px-6 py-3 bg-slate-50 rounded-2xl border border-slate-100">
                            <div class="w-2 h-2 bg-emerald-500 rounded-full animate-ping"></div>
                            <span class="text-xs font-bold text-slate-600 uppercase tracking-widest">System Upgrade in progress</span>
                        </div>
                    </div>

                    <div class="mt-12 pt-8 border-t border-slate-50">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">
                            &copy; {{ date('Y') }} {{ get_setting('SITE_NAME', config('app.name')) }}. All rights reserved.
                        </p>
                    </div>
                </div>

                <!-- Admin Link (Hidden but accessible) -->
                <div class="mt-8">
                    <a href="{{ route('admin.login') }}" class="text-[10px] font-bold text-slate-400 hover:text-sky-600 uppercase tracking-widest transition-colors">
                        Admin Login
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
