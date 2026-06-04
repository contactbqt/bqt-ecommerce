<div class="flex flex-col gap-6">
    <x-auth-header :title="__('Create an account')" :description="__('Enter your details below to create your account')" />

    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')" />

    <form wire:submit="register" class="flex flex-col gap-6">
        <!-- Name -->
        <flux:input
            wire:model="name"
            :label="__('Name')"
            type="text"
            required
            autofocus
            autocomplete="name"
            :placeholder="__('Full name')"
        />

        <!-- Email Address -->
        <flux:input
            wire:model="email"
            :label="__('Email address')"
            type="email"
            required
            autocomplete="email"
            placeholder="email@example.com"
        />

        <!-- Password -->
        <flux:input
            wire:model="password"
            :label="__('Password')"
            type="password"
            required
            autocomplete="new-password"
            :placeholder="__('Password')"
            viewable
        />

        <!-- Confirm Password -->
        <flux:input
            wire:model="password_confirmation"
            :label="__('Confirm password')"
            type="password"
            required
            autocomplete="new-password"
            :placeholder="__('Confirm password')"
            viewable
        />

        <div class="flex items-center justify-end">
            <flux:button type="submit" variant="primary" class="w-full">
                {{ __('Create account') }}
            </flux:button>
        </div>
    </form>

    <div class="relative flex items-center justify-center">
        <span class="absolute inset-x-0 h-px bg-slate-200"></span>
        <span class="relative bg-white px-4 text-xs font-bold text-slate-500 uppercase tracking-widest">{{ __('Or register with') }}</span>
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

    <div class="space-x-1 rtl:space-x-reverse text-center text-sm text-zinc-600 dark:text-zinc-400">
        {{ __('Already have an account?') }}
        <flux:link :href="route('login')">{{ __('Log in') }}</flux:link>
    </div>
</div>
