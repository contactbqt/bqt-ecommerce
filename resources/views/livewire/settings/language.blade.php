<section class="w-full">
    @include('partials.settings-heading')
    @if(session()->has('success'))
        <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
            {{ session('success') }}
        </div>
    @endif
    <x-settings.layout :heading="__('Language')" :subheading=" __('Update the language settings for your account')">
        <flux:radio.group wire:model="language" label="Select a language" class="mb-4">
            <flux:radio value="en" label="English" checked />
            <flux:radio value="hi" label="Hindi" />
        </flux:radio.group>
        <flux:button wire:click="updateLanguage" varient="filled" class="mt-4">
            {{ __('Update Language') }}
        </flux:button>
    </x-settings.layout>
</section>
