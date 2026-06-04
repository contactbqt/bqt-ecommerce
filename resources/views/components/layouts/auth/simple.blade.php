<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white antialiased dark:bg-linear-to-b dark:from-neutral-950 dark:to-neutral-900">
        <div class="relative bg-color-dark grid h-dvh flex-col items-center justify-center px-8 sm:px-0 lg:max-w-none lg:grid-cols-2 lg:px-0">

            <div class="bg-muted relative hidden h-full flex-col text-white lg:flex dark:border-e dark:border-neutral-800" style="background-color: #431E02;">
                <div class="relative flex items-center min-h-screen p-10">
                    <div class="relative">
                        <div class="max-w-xl mx-auto">
                            <h2 class="text-4xl font-bold tracking-tight text-white mb-4 p-5">
                                Welcome to Patient Portal
                            </h2>
                            <div class="grid gap-6">
                                <div class="flex items-start gap-4">
                                    <div class="p-2 bg-white/10 rounded-lg">
                                        <svg class="w-6 h-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="font-semibold text-white">Appointment Booking Status</h3>
                                        <p class="text-neutral-300">Access to get appointment history, check status and upcoming appointment schedule.</p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-4">
                                    <div class="p-2 bg-white/10 rounded-lg">
                                        <svg class="w-6 h-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="font-semibold text-white">Secure Access Control</h3>
                                        <p class="text-neutral-300">Role-based permissions and enhanced security features.</p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-4">
                                    <div class="p-2 bg-white/10 rounded-lg">
                                        <svg class="w-6 h-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="font-semibold text-white">Real-time Updates</h3>
                                        <p class="text-neutral-300">Stay informed with instant notifications and live data updates.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="w-full">
                <div class="mx-auto flex w-full flex-col justify-center space-y-6 sm:w-[470px]">
                    <a href="{{ route('home') }}" class="flex flex-col items-center gap-2 font-medium" wire:navigate>
                        <span class="flex w-70 items-center justify-center rounded-md">
                            <img src="{{ asset('assets/admin/images/logo.png') }}" alt="Admin Logo" width="90%" />
                        </span>
                    </a>
                    {{ $slot }}
                </div>
            </div>
        </div>
        @fluxScripts
    </body>
</html>
