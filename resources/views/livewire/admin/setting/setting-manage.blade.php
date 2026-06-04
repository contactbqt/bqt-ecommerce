<div class="relative mb-6 w-full">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <flux:heading size="xl" level="1">{{ $group->group_name }}</flux:heading>
            <flux:subheading size="lg" class="max-w-3xl">{{ $group->instruction_text }}</flux:subheading>
        </div>
        <flux:button href="{{ route('admin.setting.index') }}" variant="ghost" icon="arrow-left" wire:navigate>Back to Settings</flux:button>
    </div>

    <flux:separator variant="subtle" class="mb-10" />

    @if (session()->has('message'))
        <div class="mb-8 bg-emerald-50 border-emerald-200 text-emerald-800 p-4 rounded-2xl border flex items-center shadow-sm animate-fade-in">
            <flux:icon name="check-circle" class="w-5 h-5 mr-3 text-emerald-500" />
            <p class="font-bold text-sm">{{ session('message') }}</p>
        </div>
    @endif

    <div class="bg-white rounded-[2.5rem] border border-slate-200 shadow-sm overflow-hidden">
        <form wire:submit.prevent="save">
            <div class="p-8 md:p-12 space-y-10">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-10">
                    @foreach($settings as $setting)
                        @php $trimmedKey = trim($setting->key); @endphp
                        <div class="space-y-3" wire:key="setting-{{ $setting->id }}">
                            <div class="flex items-center justify-between">
                                <label class="text-xs font-black text-slate-900 uppercase tracking-widest">{{ $setting->field_name }}</label>
                                @if($setting->purpose)
                                    <flux:tooltip content="{{ $setting->purpose }}">
                                        <flux:icon name="information-circle" class="w-4 h-4 text-slate-400 cursor-help" />
                                    </flux:tooltip>
                                @endif
                            </div>
                            
                            @if($trimmedKey === 'SITE_LOGO')
                                <div class="flex items-center gap-6">
                                    @if($setting->value)
                                        <img src="{{ asset('storage/' . $setting->value) }}" class="w-16 h-16 object-contain rounded-xl border border-slate-100 bg-slate-50" />
                                    @endif
                                    <div class="flex-1">
                                        <flux:input 
                                            type="file" 
                                            wire:model="site_logo" 
                                            class="rounded-2xl border-slate-200 focus:border-sky-500 focus:ring-sky-500 transition-all"
                                        />
                                        <flux:error name="site_logo" />
                                        <p class="mt-2 text-[10px] text-slate-400 font-bold uppercase tracking-tight">Recommended: PNG with transparent background</p>
                                    </div>
                                </div>
                            @elseif($trimmedKey === 'FAVICON')
                                <div class="flex items-center gap-6">
                                    @if($setting->value)
                                        <img src="{{ asset('storage/' . $setting->value) }}" class="w-8 h-8 object-contain rounded border border-slate-100 bg-slate-50" />
                                    @endif
                                    <div class="flex-1">
                                        <flux:input 
                                            type="file" 
                                            wire:model="favicon" 
                                            class="rounded-2xl border-slate-200 focus:border-sky-500 focus:ring-sky-500 transition-all"
                                        />
                                        <flux:error name="favicon" />
                                        <p class="mt-2 text-[10px] text-slate-400 font-bold uppercase tracking-tight">Required: 32x32 pixels (ICO/PNG)</p>
                                    </div>
                                </div>
                            @elseif($trimmedKey === 'TIMEZONE')
                                <flux:select wire:model="settings_data.{{ $trimmedKey }}" class="rounded-2xl border-slate-200 focus:border-sky-500 focus:ring-sky-500 transition-all h-12">
                                    <option value="">Select Timezone</option>
                                    @foreach($timezones as $tz)
                                        <option value="{{ $tz }}">{{ $tz }}</option>
                                    @endforeach
                                </flux:select>
                            @elseif($trimmedKey === 'LANGUAGE')
                                <flux:select wire:model="settings_data.{{ $trimmedKey }}" class="rounded-2xl border-slate-200 focus:border-sky-500 focus:ring-sky-500 transition-all h-12">
                                    <option value="">Select Language</option>
                                    @foreach($languages as $code => $name)
                                        <option value="{{ $code }}">{{ $name }}</option>
                                    @endforeach
                                </flux:select>
                            @elseif($trimmedKey === 'CURRENCY')
                                <flux:select wire:model="settings_data.{{ $trimmedKey }}" class="rounded-2xl border-slate-200 focus:border-sky-500 focus:ring-sky-500 transition-all h-12">
                                    <option value="">Select Currency</option>
                                    @foreach($currencies as $code => $name)
                                        <option value="{{ $code }}">{{ $name }}</option>
                                    @endforeach
                                </flux:select>
                            @elseif(isset($ecommerceOptions[$trimmedKey]))
                                <flux:select wire:model="settings_data.{{ $trimmedKey }}" class="rounded-2xl border-slate-200 focus:border-sky-500 focus:ring-sky-500 transition-all h-12">
                                    <option value="">Select Option</option>
                                    @foreach($ecommerceOptions[$trimmedKey] as $value => $label)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </flux:select>
                            @elseif(in_array($trimmedKey, ['MAINTENANCE_MODE', 'SOCIAL_ENABLE', 'ENABLE_REVIEWS', 'VERIFIED_PURCHASE_ONLY']))
                                <div class="pt-2">
                                    <flux:checkbox 
                                        wire:model="settings_data.{{ $trimmedKey }}" 
                                        label="Enable {{ str_replace(['ENABLE_', '_', 'MODE'], ['', ' ', ''], $trimmedKey) }}" 
                                        description="{{ 
                                            $trimmedKey === 'MAINTENANCE_MODE' ? 'This will temporarily disable the storefront for customers.' : 
                                            ($trimmedKey === 'SOCIAL_ENABLE' ? 'Enable or disable social login options (Google, Facebook) on the login page.' : 
                                            ($trimmedKey === 'ENABLE_REVIEWS' ? 'Toggle the visibility of product reviews and the review submission form.' : 
                                            'Restrict review submission to customers who have actually purchased the product.')) 
                                        }}"
                                    />
                                </div>
                            @elseif(str_contains(strtolower($setting->field_name), 'message') || str_contains(strtolower($setting->field_name), 'description') || str_contains(strtolower($setting->field_name), 'text'))
                                <flux:textarea 
                                    wire:model="settings_data.{{ $trimmedKey }}" 
                                    rows="4"
                                    placeholder="Enter {{ strtolower($setting->field_name) }}..."
                                    class="rounded-2xl border-slate-200 focus:border-sky-500 focus:ring-sky-500 transition-all"
                                />
                            @elseif($trimmedKey === 'PRODUCT_TYPE_ALLOWED')
                                <flux:select wire:model="settings_data.{{ $trimmedKey }}" class="rounded-2xl border-slate-200 focus:border-sky-500 focus:ring-sky-500 transition-all h-12">
                                    <option value="">Select Product Type</option>
                                    @foreach($productTypes as $value => $label)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </flux:select>
                            @else
                                <flux:input 
                                    wire:model="settings_data.{{ $trimmedKey }}" 
                                    placeholder="Enter {{ strtolower($setting->field_name) }}..."
                                    class="rounded-2xl border-slate-200 focus:border-sky-500 focus:ring-sky-500 transition-all h-12"
                                />
                            @endif

                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">Key: <code class="bg-slate-50 px-1 rounded">{{ $setting->key }}</code></p>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="bg-slate-50/80 px-12 py-8 border-t border-slate-200 flex items-center justify-between">
                <p class="text-xs font-bold text-slate-500 uppercase tracking-widest">Careful with changes to system keys.</p>
                <flux:button type="submit" variant="primary" color="sky" class="px-12 h-12 shadow-xl shadow-sky-500/20 font-bold">
                    <span wire:loading.remove wire:target="save">Save Changes</span>
                    <span wire:loading wire:target="save">Saving...</span>
                </flux:button>
            </div>
        </form>
    </div>
</div>
