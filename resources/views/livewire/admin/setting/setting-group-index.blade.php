<div class="relative mb-6 w-full">
    <div class="mb-6">
        <flux:heading size="xl" level="1">{{ __('System Settings') }}</flux:heading>
        <flux:subheading size="lg">{{ __('Manage your eCommerce platform configurations by groups.') }}</flux:subheading>
    </div>

    <flux:separator variant="subtle" class="mb-8" />

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($groups as $group)
            <a href="{{ route('admin.setting.manage', $group->slug_name) }}" wire:navigate class="group relative bg-white rounded-3xl p-8 border border-slate-200 hover:border-sky-500 hover:shadow-xl hover:shadow-sky-500/5 transition-all duration-300 flex flex-col h-full">
                <div class="flex items-center justify-between mb-6">
                    <div class="w-12 h-12 bg-sky-50 rounded-2xl flex items-center justify-center text-sky-600 group-hover:bg-sky-600 group-hover:text-white transition-all duration-300">
                        <flux:icon name="cog-6-tooth" class="w-6 h-6" />
                    </div>
                    <span class="text-[10px] font-black bg-slate-100 text-slate-500 px-3 py-1 rounded-full uppercase tracking-widest">
                        {{ $group->settings_count }} Fields
                    </span>
                </div>

                <h3 class="text-xl font-black text-slate-900 mb-3 group-hover:text-sky-600 transition-colors uppercase tracking-tight">
                    {{ $group->group_name }}
                </h3>
                
                <p class="text-sm text-slate-500 leading-relaxed font-medium line-clamp-3 flex-1">
                    {{ $group->instruction_text }}
                </p>

                <div class="mt-8 flex items-center text-sky-600 text-xs font-black uppercase tracking-widest group-hover:gap-2 transition-all">
                    Manage Settings
                    <svg class="w-4 h-4 ml-1 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                    </svg>
                </div>
            </a>
        @endforeach
    </div>
</div>
