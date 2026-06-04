<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white antialiased dark:bg-linear-to-b dark:from-neutral-950 dark:to-neutral-900">
        <div class="relative grid h-dvh flex-col items-center justify-center px-8 sm:px-0 lg:max-w-none lg:grid-cols-2 lg:px-0 bg-light-doctor">
            <div class="bg-muted relative hidden h-full flex-col p-10 text-white lg:flex dark:border-e dark:border-neutral-800" style="background-image: url('{{ asset('assets/images/doctor-portal.png') }}'); background-size: cover; background-position: center;">

            </div>
            <div class="w-full lg:p-8">
                <div class="mx-auto flex w-full flex-col justify-center space-y-6 sm:w-[350px]">
                    {{-- <a href="{{ route('doctor.login') }}" class="flex flex-col items-center gap-2 font-medium" wire:navigate>
                        <span class="flex w-70 items-center justify-center rounded-md">
                            <img src="{{ asset('assets/admin/images/logo.png') }}" alt="Admin Logo" width="90%" />
                        </span>
                    </a> --}}
                    {{ $slot }}
                </div>
            </div>
        </div>
        @fluxScripts
    </body>
</html>
