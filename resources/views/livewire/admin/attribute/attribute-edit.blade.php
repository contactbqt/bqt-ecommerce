<div>
    <flux:modal name="edit-attribute" variant="flyout" class="md:w-320" style="padding: 0px !important">
        <div class="model-head">
            <flux:heading size="xl" class="font-bold text-purple">Edit Attribute</flux:heading>
            <flux:text class="mt-2 text-purple">Edit attribute record.</flux:text>
        </div>
        <div class="space-y-6 p-8">
            <div class="grid grid-cols-2 gap-6">
                <flux:input wire:model="attribute_name" description="Attribute name is unquie." label="Attribute Name" badge="Required" placeholder="Enter a attribute name" />
                <flux:input wire:model="slug" label="Attribute Code" description="Attribute code will be used in the URLs, Filter" badge="Required" placeholder="Enter a slug" />
                <div>
                    <flux:fieldset>
                        <flux:legend class="text-sm font-medium">Attribute Type</flux:legend>
                        <flux:description class="text-sm text-gray-500">Two types of attributes are there based on variant creation and filter.</flux:description>
                        <flux:checkbox wire:model="is_variant" label="Use for Variants (Affects Product Price/ Stock)" />
                        <flux:checkbox wire:model="is_filter" label="Use for Filter (Left Sidebar of Shop Page)" />
                    </flux:fieldset>
                </div>
                <flux:fieldset>
                    <flux:legend class="text-sm font-medium">Display Type</flux:legend>
                    <flux:description class="text-sm text-gray-500">Attribute values will be displayed as Dropdown: Displays a dropdown list of options. Checkbox: Allows multiple selections. Radio: Displays a list of options as radio buttons.</flux:description>
                    <flux:select wire:model="display_type" badge="Required">
                        <option value="">Select Display Type</option>
                        <option value="dropdown">Dropdown</option>
                        <option value="checkbox">Checkbox</option>
                        <option value="radio">Radio</option>
                    </flux:select>
                </flux:fieldset>
                

                <flux:select wire:model="status" label="Status" badge="Required">
                    <option value="">Select Status</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </flux:select>
            </div>

            <div class="flex">
                <flux:spacer />
                <flux:button wire:click="EditAttribute" type="submit" variant="primary" color="sky" wire:loading.attr="disabled" wire:target="EditAttribute">
                    <span wire:loading.remove wire:target="EditAttribute">Save changes</span>
                    <span wire:loading wire:target="EditAttribute">Saving...</span>
                </flux:button>
            </div>
        </div>
    </flux:modal>
</div>
