<div class="relative mb-6 w-full">
    <div class="mb-6">
        <flux:heading size="xl" level="1">{{ __('Import Module') }}</flux:heading>
        <flux:subheading size="lg">{{ __('Import categories, attributes, and products via Excel/CSV') }}</flux:subheading>
    </div>

    <flux:separator variant="subtle" class="mb-6" />

    <!-- Success/Error Messages -->
    @if (session()->has('message'))
        <div class="mb-6 bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-r-lg shadow-sm flex items-center">
            <svg class="w-5 h-5 text-emerald-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <p class="text-emerald-800 font-medium">{{ session('message') }}</p>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-6 bg-rose-50 border-l-4 border-rose-500 p-4 rounded-r-lg shadow-sm flex items-center">
            <svg class="w-5 h-5 text-rose-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
            </svg>
            <p class="text-rose-800 font-medium">{{ session('error') }}</p>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden">
        <!-- Tabs -->
        <div class="flex border-b border-gray-100 bg-slate-50">
            @php
                $tabs = [
                    'categories' => 'Categories',
                    'attributes' => 'Attributes',
                    'attribute_values' => 'Attribute Values',
                    'attribute_tagging' => 'Attribute Tagging',
                    'products' => 'Products',
                ];
            @endphp
            @foreach($tabs as $key => $label)
                <button 
                    wire:click="setTab('{{ $key }}')"
                    class="px-6 py-4 text-sm font-bold transition-colors {{ $activeTab === $key ? 'bg-white border-b-2 border-sky-500 text-sky-600' : 'text-slate-500 hover:text-slate-700 hover:bg-slate-100' }}"
                >
                    {{ $label }}
                </button>
            @endforeach
        </div>

        <div class="p-8">
            <!-- Step 1: Upload & Instructions -->
            @if($step === 1)
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                    <div class="space-y-6">
                        <h3 class="text-lg font-bold text-slate-900">Step 1: Upload your file</h3>
                        <p class="text-slate-600 text-sm">Download the template, fill it with your data, and upload it here.</p>
                        
                        <div class="flex flex-col gap-4">
                            <flux:button 
                                wire:click="downloadTemplate" 
                                variant="ghost" 
                                icon="arrow-down-tray" 
                                class="w-full justify-start"
                            >
                                Download {{ $tabs[$activeTab] }} Template
                            </flux:button>

                            <div class="space-y-2">
                                <flux:input 
                                    type="file" 
                                    wire:model="importFile" 
                                    label="Choose Excel/CSV File" 
                                    accept=".xlsx,.csv,.xls"
                                />
                                @error('importFile') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <flux:button 
                                wire:click="validateImport" 
                                variant="primary" 
                                color="sky" 
                                class="w-full"
                                wire:loading.attr="disabled"
                                wire:target="importFile, validateImport"
                            >
                                <span wire:loading.remove wire:target="validateImport">Validate & Preview</span>
                                <span wire:loading wire:target="validateImport">Processing...</span>
                            </flux:button>
                        </div>
                    </div>

                    <div class="bg-slate-50 rounded-xl p-6 border border-slate-100">
                        <h4 class="font-bold text-slate-900 mb-4 flex items-center">
                            <flux:icon.information-circle class="w-5 h-5 mr-2 text-sky-500" />
                            Import Instructions
                        </h4>
                        <ul class="space-y-3 text-sm text-slate-600">
                            <li class="flex items-start">
                                <span class="font-bold text-sky-500 mr-2">•</span>
                                <span>Use the unique slug/SKU for identifying records (upsert).</span>
                            </li>
                            <li class="flex items-start">
                                <span class="font-bold text-sky-500 mr-2">•</span>
                                <span>Human-readable names and slugs are preferred over IDs.</span>
                            </li>
                            @if($activeTab === 'categories')
                                <li class="flex items-start">
                                    <span class="font-bold text-sky-500 mr-2">•</span>
                                    <span>For <strong>parent_slug</strong>, ensure the parent category already exists or is included earlier in the file.</span>
                                </li>
                            @elseif($activeTab === 'attributes')
                                <li class="flex items-start">
                                    <span class="font-bold text-sky-500 mr-2">•</span>
                                    <span><strong>display_type</strong> should be one of: dropdown, checkbox, radio.</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="font-bold text-sky-500 mr-2">•</span>
                                    <span><strong>is_variant</strong> and <strong>is_filter</strong> should be 1 (Yes) or 0 (No).</span>
                                </li>
                            @elseif($activeTab === 'attribute_values')
                                <li class="flex items-start">
                                    <span class="font-bold text-sky-500 mr-2">•</span>
                                    <span><strong>attribute_slug</strong> must match an existing attribute's slug.</span>
                                </li>
                            @elseif($activeTab === 'attribute_tagging')
                                <li class="flex items-start">
                                    <span class="font-bold text-sky-500 mr-2">•</span>
                                    <span><strong>category_slug</strong> and <strong>attribute_slug</strong> must exist.</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="font-bold text-sky-500 mr-2">•</span>
                                    <span><strong>type</strong> must be "filter", "variant", or "both" (creates separate entries for each).</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="font-bold text-sky-500 mr-2">•</span>
                                    <span><strong>attribute_value_slugs</strong> should be comma-separated slugs (e.g., red, green, blue).</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="font-bold text-sky-500 mr-2">•</span>
                                    <span>These values must already exist in the <strong>Attribute Values</strong> table.</span>
                                </li>
                            @elseif($activeTab === 'products')
                                <li class="flex items-start">
                                    <span class="font-bold text-sky-500 mr-2">•</span>
                                    <span><strong>product_type</strong> should be "single" or "variant".</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="font-bold text-sky-500 mr-2">•</span>
                                    <span><strong>category_slug</strong> must match an existing category's slug.</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="font-bold text-sky-500 mr-2">•</span>
                                    <span><strong>price</strong> is required for both types.</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="font-bold text-sky-500 mr-2">•</span>
                                    <span><strong>filter_attributes</strong>: comma-separated <code>attr_slug:val_slug</code> pairs (e.g., color:red, size:size-s).</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="font-bold text-sky-500 mr-2">•</span>
                                    <span><strong>variant_attributes</strong>: comma-separated IDs or Slugs (only for variant entries).</span>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            @endif

            <!-- Step 2: Preview & Validation -->
            @if($step === 2)
                <div class="space-y-6">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-bold text-slate-900">Step 2: Preview Data</h3>
                        <div class="flex gap-2">
                            <flux:button wire:click="$set('step', 1)" variant="ghost">Cancel</flux:button>
                            <flux:button 
                                wire:click="confirmImport" 
                                variant="primary" 
                                color="sky"
                                :disabled="!empty($validationErrors)"
                            >
                                Confirm & Import
                            </flux:button>
                        </div>
                    </div>

                    @if(!empty($validationErrors))
                        <div class="bg-rose-50 border border-rose-200 text-rose-800 p-4 rounded-lg text-sm">
                            <strong>Validation Errors Found:</strong> Please fix the errors in your file and upload again.
                        </div>
                    @endif

                    <div class="overflow-x-auto border border-gray-200 rounded-lg shadow-sm">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-slate-50">
                                <tr>
                                    @if(count($importData) > 0)
                                        @foreach(array_keys(reset($importData)) as $header)
                                            @if($header !== 'is_duplicate')
                                                <th class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">{{ $header }}</th>
                                            @endif
                                        @endforeach
                                        <th class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Status</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($importData as $index => $row)
                                    <tr class="{{ isset($validationErrors[$index]) ? 'bg-rose-50' : ($row['is_duplicate'] ? 'bg-amber-50' : '') }}">
                                        @foreach($row as $key => $value)
                                            @if($key !== 'is_duplicate')
                                                <td class="px-4 py-3 text-sm text-slate-600 whitespace-nowrap">{{ $value }}</td>
                                            @endif
                                        @endforeach
                                        <td class="px-4 py-3 text-sm whitespace-nowrap">
                                            @if(isset($validationErrors[$index]))
                                                <div class="text-rose-600 font-medium">
                                                    @foreach($validationErrors[$index] as $error)
                                                        <div class="flex items-center">
                                                            <flux:icon.x-circle class="w-4 h-4 mr-1" />
                                                            {{ $error }}
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @elseif($row['is_duplicate'])
                                                <div class="text-amber-600 font-medium flex items-center">
                                                    <flux:icon.exclamation-triangle class="w-4 h-4 mr-1" />
                                                    Duplicate (Will be skipped)
                                                </div>
                                            @else
                                                <div class="text-emerald-600 font-medium flex items-center">
                                                    <flux:icon.check-circle class="w-4 h-4 mr-1" />
                                                    Ready
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
