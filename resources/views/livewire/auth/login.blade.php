<div class="flex flex-col gap-6">
    <x-auth-header :title="__('Log in to your account')" :description="__('Enter your email and password below to log in')" />

    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')" />

    <form wire:submit="login" class="flex flex-col gap-6">
        <!-- Email Address -->
        <flux:input
            wire:model="email"
            :label="__('Email address')"
            type="email"
            required
            autofocus
            autocomplete="email"
            placeholder="email@example.com"
        />

        <!-- Password -->
        <div class="relative">
            <flux:input
                wire:model="password"
                :label="__('Password')"
                type="password"
                required
                autocomplete="current-password"
                :placeholder="__('Password')"
                viewable
            />

            @if (Route::has('password.request'))
                <flux:link class="absolute end-0 top-0 text-sm" :href="route('password.request')">
                    {{ __('Forgot your password?') }}
                </flux:link>
            @endif
        </div>

        <!-- Remember Me -->
        <flux:checkbox wire:model="remember" :label="__('Remember me')" />

        <div class="flex items-center justify-end">
            <flux:button variant="primary" type="submit" class="w-full">{{ __('Log in') }}</flux:button>
        </div>
    </form>

    @if(get_setting('SOCIAL_ENABLE') == '1')
        <div class="relative flex items-center justify-center">
            <span class="absolute inset-x-0 h-px bg-slate-200"></span>
            <span class="relative bg-white px-4 text-xs font-bold text-slate-500 uppercase tracking-widest">{{ __('Or continue with') }}</span>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <flux:button :href="route('social.redirect', 'google')" variant="ghost" class="w-full border-slate-200">
                <img src="https://www.svgrepo.com/show/355037/google.svg" class="w-4 h-4 mr-2" alt="Google">
                {{ __('Google') }}
            </flux:button>
            <flux:button :href="route('social.redirect', 'facebook')" variant="ghost" class="w-full border-slate-200">
                <img src="https://www.svgrepo.com/show/448224/facebook.svg" class="w-4 h-4 mr-2" alt="Facebook">
                {{ __('Facebook') }}
            </flux:button>
        </div>
    @endif

    @if (Route::has('register'))
        <div class="space-x-1 rtl:space-x-reverse text-center text-sm text-zinc-600 dark:text-zinc-400">
            {{ __('Don\'t have an account?') }}
            <flux:link :href="route('register')">{{ __('Sign up') }}</flux:link>
        </div>
    @endif
</div>
