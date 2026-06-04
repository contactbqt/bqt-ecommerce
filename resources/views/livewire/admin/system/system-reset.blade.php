<div class="p-6">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-slate-800">{{ __('Module-wise Data Reset') }}</h2>
        <p class="text-sm text-slate-500">{{ __('Select modules to reset their data') }}</p>
    </div>

    @if (session()->has('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded shadow-sm">
            {{ session('error') }}
        </div>
    @endif

    <div class="mb-6 p-4 bg-amber-50 border border-amber-300 rounded-lg shadow-sm">
        <div class="flex items-start gap-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-amber-600 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <div>
                <h4 class="text-amber-800 font-semibold">Warning: Data Loss Risk</h4>
                <p class="text-amber-700 text-sm mt-1">Once reset, you can't retrieve the records. Please take a backup of the tables before resetting to avoid any unwanted situations.</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm overflow-hidden mb-6 border border-slate-200">
        <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
            <h3 class="text-lg font-medium text-slate-800">{{ __('Select Modules to Reset') }}</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($modules as $moduleName => $tables)
                    <div class="border rounded-lg p-4 transition-all duration-200 {{ in_array($moduleName, $selectedModules) ? 'border-indigo-500 bg-indigo-50 ring-1 ring-indigo-500' : 'border-slate-200 hover:border-slate-300' }}">
                        <label class="flex items-center cursor-pointer group">
                            <input
                                type="checkbox"
                                wire:model.live="selectedModules"
                                value="{{ $moduleName }}"
                                class="w-5 h-5 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 transition-colors"
                            >
                            <span class="ml-3 text-lg font-medium text-slate-700 capitalize group-hover:text-indigo-600 transition-colors">{{ $moduleName }}</span>
                        </label>
                        <div class="mt-3 ml-8">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Tables affected:</p>
                            <ul class="space-y-1">
                                @foreach($tables as $table)
                                    <li class="text-xs text-slate-500 flex items-center gap-1.5">
                                        <span class="w-1 h-1 bg-slate-300 rounded-full"></span>
                                        {{ $table }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endforeach
            </div>

            @if(count($selectedModules) > 0)
                <div class="mt-6 p-3 bg-slate-50 border border-slate-200 rounded text-xs text-slate-600 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Selected: <strong>{{ count($selectedModules) }}</strong> module(s) ({{ implode(', ', $selectedModules) }})</span>
                </div>
            @endif
        </div>
    </div>

    @error('selectedModules')
        <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-600 text-sm rounded flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            {{ $message }}
        </div>
    @enderror

    @if(count($selectedModules) > 0)
        <div class="bg-white rounded-lg shadow-sm overflow-hidden mb-6 border border-slate-200 animate-in fade-in slide-in-from-top-2 duration-300" wire:key="confirmation-box">
            <div class="px-6 py-4 border-b border-red-100 bg-red-50 flex items-center gap-3">
                <div class="bg-red-100 p-1.5 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m0 0h.01M12 11V7m0 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-red-800 uppercase tracking-wide">{{ __('Final Confirmation Required') }}</h3>
                    <p class="text-xs text-red-600">Type <span class="font-mono bg-red-100 px-1 rounded text-red-800 font-bold">RESET DATA</span> in the field below.</p>
                </div>
            </div>
            <div class="p-6">
                <input
                    type="text"
                    wire:model.live.debounce.250ms="confirmation"
                    placeholder="Type RESET DATA here..."
                    class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-lg font-mono tracking-widest placeholder:tracking-normal placeholder:font-sans @error('confirmation') border-red-500 @enderror"
                    autocomplete="off"
                    wire:key="confirmation-input"
                >
                @error('confirmation')
                    <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>
        </div>

        <div class="flex justify-end gap-3" wire:key="action-buttons">
            <button
                type="button"
                wire:click="resetModules"
                wire:loading.attr="disabled"
                class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg shadow-sm text-white transition-all duration-200 {{ $confirmation === 'RESET DATA' ? 'bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500' : 'bg-slate-300 cursor-not-allowed' }}"
                @if($confirmation !== 'RESET DATA') disabled @endif
            >
                <span wire:loading.remove wire:target="resetModules" class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    {{ __('Execute Module Reset') }}
                </span>
                <span wire:loading wire:target="resetModules" class="flex items-center gap-2">
                    <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    {{ __('Processing Reset...') }}
                </span>
            </button>
        </div>
    @endif
</div>
