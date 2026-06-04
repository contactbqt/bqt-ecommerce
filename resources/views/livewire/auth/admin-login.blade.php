<div class="flex flex-col gap-8">
    <div>
        <h1 class="text-3xl font-black text-slate-900 tracking-tight mb-2">Sign in</h1>
        <p class="text-slate-500 font-medium">Enter your credentials to access the admin panel.</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="text-left" :status="session('status')" />

    <form wire:submit="login" class="flex flex-col gap-6">
        <!-- Email Address -->
        <div class="space-y-2">
            <flux:input
                wire:model="email"
                :label="__('Email address')"
                type="email"
                required
                autofocus
                autocomplete="email"
                placeholder="admin@novamart.com"
                class="!h-12"
            />
        </div>

        <!-- Password -->
        <div class="space-y-2">
            <div class="relative">
                <flux:input
                    wire:model="password"
                    :label="__('Password')"
                    type="password"
                    required
                    autocomplete="current-password"
                    :placeholder="__('Password')"
                    viewable
                    class="!h-12"
                />

                @if (Route::has('password.request'))
                    <flux:link class="absolute end-0 top-0 text-sm font-semibold text-sky-600 hover:text-sky-700" :href="route('password.request')" wire:navigate>
                        {{ __('Forgot?') }}
                    </flux:link>
                @endif
            </div>
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between">
            <flux:checkbox wire:model="remember" :label="__('Keep me logged in')" class="text-slate-600" />
        </div>

        <div class="pt-2">
            <flux:button variant="primary" type="submit" icon-trailing="arrow-right" class="w-full !h-12 !bg-sky-600 hover:!bg-sky-700 !text-white !font-bold !rounded-xl !shadow-lg !shadow-sky-200 transition-all active:scale-[0.98]">
                {{ __('Access Dashboard') }}
            </flux:button>
        </div>
    </form>
</div>
