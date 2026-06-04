<div class="relative mb-6 w-full">
    <flux:heading size="xl" level="1">{{ __('Admin Dashboard') }}</flux:heading>
    <flux:subheading size="lg" class="mb-6">{{ __('Overview of your application insights and activity.') }}
    </flux:subheading>
    <flux:separator variant="subtle" />
    <div class="min-h-screen bg-gradient-to-br p-8">
        <div class="max-w-7xl mx-auto">

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                {{-- Total Users Card --}}
                <div
                    class="group relative bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden">
                    <div
                        class="absolute inset-0 bg-gradient-to-br from-purple-50 to-pink-50 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    </div>

                    <div class="relative p-6">
                        <div
                            class="w-14 h-14 rounded-xl bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center mb-4 transform group-hover:scale-110 group-hover:rotate-3 transition-transform duration-300">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                </path>
                            </svg>
                        </div>

                        <h3 class="text-sm font-medium text-slate-600 mb-1">Total Users</h3>

                        <div class="flex items-baseline justify-between">
                            <p class="text-3xl font-bold text-slate-900">
                                {{ number_format($totalUsers ?? 0) }}
                            </p>
                        </div>

                        <div
                            class="mt-4 h-1 rounded-full bg-gradient-to-r from-purple-500 to-pink-500 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left">
                        </div>
                    </div>
                </div>

                {{-- Admin Users Card --}}
                <div
                    class="group relative bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden">
                    <div
                        class="absolute inset-0 bg-gradient-to-br from-blue-50 to-cyan-50 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    </div>

                    <div class="relative p-6">
                        <div
                            class="w-14 h-14 rounded-xl bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center mb-4 transform group-hover:scale-110 group-hover:rotate-3 transition-transform duration-300">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                                </path>
                            </svg>
                        </div>

                        <h3 class="text-sm font-medium text-slate-600 mb-1">Admin Users</h3>

                        <div class="flex items-baseline justify-between">
                            <p class="text-3xl font-bold text-slate-900">
                                {{ number_format($adminUsers ?? 0) }}
                            </p>
                        </div>

                        <div
                            class="mt-4 h-1 rounded-full bg-gradient-to-r from-blue-500 to-cyan-500 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left">
                        </div>
                    </div>
                </div>

                {{-- Regular Users Card --}}
                <div
                    class="group relative bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden">
                    <div
                        class="absolute inset-0 bg-gradient-to-br from-orange-50 to-red-50 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    </div>

                    <div class="relative p-6">
                        <div
                            class="w-14 h-14 rounded-xl bg-gradient-to-br from-orange-500 to-red-500 flex items-center justify-center mb-4 transform group-hover:scale-110 group-hover:rotate-3 transition-transform duration-300">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                </path>
                            </svg>
                        </div>

                        <h3 class="text-sm font-medium text-slate-600 mb-1">Regular Users</h3>

                        <div class="flex items-baseline justify-between">
                            <p class="text-3xl font-bold text-slate-900">
                                {{ number_format($regularUsers ?? 0) }}
                            </p>
                        </div>

                        <div
                            class="mt-4 h-1 rounded-full bg-gradient-to-r from-orange-500 to-red-500 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left">
                        </div>
                    </div>
                </div>

                {{-- Recent Users Card --}}
                <div
                    class="group relative bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden">
                    <div
                        class="absolute inset-0 bg-gradient-to-br from-green-50 to-emerald-50 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    </div>

                    <div class="relative p-6">
                        <div
                            class="w-14 h-14 rounded-xl bg-gradient-to-br from-green-500 to-emerald-500 flex items-center justify-center mb-4 transform group-hover:scale-110 group-hover:rotate-3 transition-transform duration-300">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z">
                                </path>
                            </svg>
                        </div>

                        <h3 class="text-sm font-medium text-slate-600 mb-1">New Users (30 Days)</h3>

                        <div class="flex items-baseline justify-between">
                            <p class="text-3xl font-bold text-slate-900">
                                {{ number_format($recentUsers ?? 0) }}
                            </p>
                        </div>

                        <div
                            class="mt-4 h-1 rounded-full bg-gradient-to-r from-green-500 to-emerald-500 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
