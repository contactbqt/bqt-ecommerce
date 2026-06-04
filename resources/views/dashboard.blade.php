<x-layouts.account>
    <div class="w-full">
        <div class="relative mb-6 w-full">
            <flux:heading size="xl" level="1">Dashboard</flux:heading>
            <flux:subheading size="lg" class="mb-6">Welcome back, {{ auth()->user()->name }}! Here's an overview of your account.</flux:subheading>
            <flux:separator variant="subtle" />
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
            <!-- Orders Card -->
            <a href="{{ route('user.orders') }}" class="block bg-sky-50 rounded-2xl p-6 border border-sky-100 hover:shadow-md transition-shadow group" wire:navigate>
                <div class="flex items-center gap-4">
                    <div class="bg-sky-100 p-3 rounded-xl group-hover:bg-sky-200 transition-colors">
                        <flux:icon name="shopping-bag" class="size-6 text-sky-600" />
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-sky-900">My Orders</h3>
                        <p class="text-sm text-sky-700">Track, return, or buy things again</p>
                    </div>
                </div>
            </a>

            <!-- Profile Card -->
            <a href="{{ route('settings.profile') }}" class="block bg-emerald-50 rounded-2xl p-6 border border-emerald-100 hover:shadow-md transition-shadow group" wire:navigate>
                <div class="flex items-center gap-4">
                    <div class="bg-emerald-100 p-3 rounded-xl group-hover:bg-emerald-200 transition-colors">
                        <flux:icon name="user" class="size-6 text-emerald-600" />
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-emerald-900">Profile Info</h3>
                        <p class="text-sm text-emerald-700">Edit name and email address</p>
                    </div>
                </div>
            </a>

            <!-- Addresses Card -->
            <a href="#" class="block bg-indigo-50 rounded-2xl p-6 border border-indigo-100 hover:shadow-md transition-shadow group">
                <div class="flex items-center gap-4">
                    <div class="bg-indigo-100 p-3 rounded-xl group-hover:bg-indigo-200 transition-colors">
                        <flux:icon name="map-pin" class="size-6 text-indigo-600" />
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-indigo-900">Address Book</h3>
                        <p class="text-sm text-indigo-700">Edit addresses for orders</p>
                    </div>
                </div>
            </a>

            <!-- Security Card -->
            <a href="{{ route('settings.password') }}" class="block bg-rose-50 rounded-2xl p-6 border border-rose-100 hover:shadow-md transition-shadow group" wire:navigate>
                <div class="flex items-center gap-4">
                    <div class="bg-rose-100 p-3 rounded-xl group-hover:bg-rose-200 transition-colors">
                        <flux:icon name="key" class="size-6 text-rose-600" />
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-rose-900">Security</h3>
                        <p class="text-sm text-rose-700">Change your password</p>
                    </div>
                </div>
            </a>
        </div>
    </div>
</x-layouts.account>