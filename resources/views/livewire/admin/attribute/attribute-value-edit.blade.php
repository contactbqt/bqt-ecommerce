<div>
    <flux:modal name="edit-attribute-value" variant="flyout" class="md:w-320" style="padding: 0px !important">
        <div class="model-head">
            <flux:heading size="xl" class="font-bold text-purple">Edit Attribute Value</flux:heading>
            <flux:text class="mt-2 text-purple">Edit a attribute value record.</flux:text>
        </div>
        <div class="space-y-6 p-8">
            <div class="grid grid-cols-2 gap-6">
                <flux:input wire:model="value_name" description="Attribute value name is unquie." label="Attribute Value Name" badge="Required" placeholder="Enter a attribute value name" />
                <flux:input wire:model="slug" label="Attribute Value Code" description="Attribute value code will be used in the URLs, Filter" badge="Required" placeholder="Enter a slug" />
                <flux:input wire:model="sort_order" label="Sort Order" description="Attribute value will be displayed based on that field in ascending order." placeholder="Enter a sort order" />
                
                <flux:field>
                    <flux:label>Choose Color Code</flux:label>
                    <flux:description>If the attribute type is color, then you can choose a color for this attribute value.</flux:description>
                    <div class="flex items-center gap-2">
                        <!-- Color Picker -->
                        <div class="h-full flex items-end">
                            <div x-data="{ color: @entangle('hexa_color_code') }" class="h-full flex items-end gap-2">
                                <!-- Color Picker -->
                                <input
                                    type="color"
                                    class="h-10 w-10 cursor-pointer rounded border"
                                    x-model="color"
                                />

                                <!-- Flux Input -->
                                <flux:input
                                    x-model="color"
                                    placeholder="#ffffff"
                                    class="color-input"
                                />
                            </div>
                        </div>
                        <!-- Hex Input -->
                    </div>
                </flux:field>
                
                <flux:select wire:model="status" label="Status" badge="Required">
                    <option value="">Select Status</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </flux:select>
            </div>

            <div class="flex">
                <flux:spacer />
                <flux:button wire:click="EditAttributeValue" type="submit" variant="primary" color="sky" wire:loading.attr="disabled" wire:target="EditAttributeValue">
                    <span wire:loading.remove wire:target="EditAttributeValue">Save changes</span>
                    <span wire:loading wire:target="EditAttributeValue">Saving...</span>
                </flux:button>
            </div>
        </div>
    </flux:modal>
</div>
