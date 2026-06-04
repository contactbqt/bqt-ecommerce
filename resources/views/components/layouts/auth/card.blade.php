<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('partials.head')
</head>
<body class="min-h-screen bg-white antialiased selection:bg-sky-500 selection:text-white">
    <div class="flex min-h-screen overflow-hidden">
        <!-- Left Side: Modern Image/Branding -->
        <div class="hidden lg:flex lg:w-1/2 relative bg-slate-900">
            <!-- Background Image -->
            <img src="https://images.unsplash.com/photo-1441986300917-64674bd600d8?q=80&w=1920&auto=format&fit=crop" 
                 alt="Admin Login Background" 
                 class="absolute inset-0 w-full h-full object-cover opacity-60">
            
            <!-- Gradient Overlay -->
            <div class="absolute inset-0 bg-gradient-to-tr from-sky-900/90 via-slate-900/70 to-transparent"></div>
            
            <!-- Branding Content -->
            <div class="relative z-10 w-full flex flex-col justify-between p-16">
                <div>
                    <a href="{{ route('home') }}" class="text-4xl font-black tracking-tighter text-white">
                        <img src="{{ asset('assets/images/logo.png') }}" alt="DigiCart" class="w-50">
                    </a>
                </div>
                
                <div>
                    <h2 class="text-4xl font-bold text-white mb-6 leading-tight">
                        Powering the next generation of <span class="text-sky-400 italic">E-commerce</span> management.
                    </h2>
                    <p class="text-lg text-slate-300 max-w-md font-light">
                        Access your control center to manage orders, inventory, and customer experiences with ease and precision.
                    </p>
                </div>
                
                <div class="flex items-center gap-6 text-white/50 text-sm">
                    <span class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        Secure Access
                    </span>
                    <span class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        High Performance
                    </span>
                </div>
            </div>
        </div>

        <!-- Right Side: Login Form -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8 sm:p-12 md:p-16 lg:p-20 bg-white">
            <div class="w-full max-w-md">
                <!-- Mobile Branding -->
                <div class="lg:hidden mb-12 text-center">
                    <a href="{{ route('home') }}" class="text-4xl font-black tracking-tighter text-slate-900">
                        Digi<span class="text-sky-500">Cart</span>
                    </a>
                    <p class="text-slate-500 text-sm mt-2">Admin Dashboard</p>
                </div>

                <!-- Main Slot (Form) -->
                <div class="animate-in fade-in slide-in-from-bottom-4 duration-700">
                    {{ $slot }}
                </div>
                
                <!-- Footer Info -->
                <div class="mt-12 pt-8 border-t border-slate-100 text-center">
                    <p class="text-slate-400 text-xs tracking-widest uppercase font-bold">
                        &copy; {{ date('Y') }} DigiCart Control Center
                    </p>
                </div>
            </div>
        </div>
    </div>
    @fluxScripts
</body>
</html>