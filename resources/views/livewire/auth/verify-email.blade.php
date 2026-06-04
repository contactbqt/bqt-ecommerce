<div class="flex flex-col gap-6">
    <x-auth-header 
        :title="__('Verify Your Email')" 
        :description="__('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.')" 
    />

    @if (session('status') == 'verification-link-sent')
        <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-100">
            <flux:text class="text-center font-medium !text-emerald-700">
                {{ __('A new verification link has been sent to the email address you provided during registration.') }}
            </flux:text>
        </div>
    @endif

    <div class="flex flex-col gap-4">
        <flux:button 
            wire:click="sendVerification" 
            variant="primary" 
            class="w-full !bg-sky-500 hover:!bg-sky-600 border-none h-12 rounded-xl font-bold shadow-lg shadow-sky-200"
            wire:loading.attr="disabled"
        >
            <span wire:loading.remove wire:target="sendVerification">{{ __('Resend Verification Email') }}</span>
            <span wire:loading wire:target="sendVerification">{{ __('Sending...') }}</span>
        </flux:button>

        <div class="flex items-center justify-center gap-2 text-sm text-slate-500">
            <span>{{ __('Entered the wrong email?') }}</span>
            <button wire:click="logout" class="font-bold text-sky-600 hover:text-sky-700 transition-colors">
                {{ __('Log out') }}
            </button>
        </div>
    </div>
</div>
