<div>
    <flux:modal.trigger name="create-tag">
        <flux:button variant="ghost" icon:variant="solid" icon="plus">Create Tag</flux:button>
    </flux:modal.trigger>

    <flux:modal name="create-tag" variant="flyout" class="md:w-320" style="padding: 0px !important">
        <div class="model-head">
            <flux:heading size="xl" class="font-bold text-purple">Create a New Tag</flux:heading>
            <flux:text class="mt-2 text-purple">Create a tag record.</flux:text>
        </div>
        <div class="space-y-6 p-8">
            <div class="grid grid-cols-2 gap-6">
                <flux:input wire:model="tag_name" description="Tag name is unquie." label="Tag Name" badge="Required" placeholder="Enter a tag name" />
                <flux:input wire:model="slug" label="Tag Code" description="Tag code will be used in the URLs, Filter" badge="Required" placeholder="Enter a slug" />
            </div>

            <div class="flex">
                <flux:spacer />
                <flux:button wire:click="createTag" type="submit" variant="primary" color="sky" wire:loading.attr="disabled" wire:target="createTag">
                    <span wire:loading.remove wire:target="createTag">Save changes</span>
                    <span wire:loading wire:target="createTag">Saving...</span>
                </flux:button>
            </div>

        </div>
    </flux:modal>
</div>
