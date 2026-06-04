<div class="relative mb-6 w-full">
    <flux:heading size="xl" level="1">{{ __('Meta Management') }}</flux:heading>
    <flux:subheading size="lg" class="mb-6">{{ __('Manage SEO meta tags for this product.') }}</flux:subheading>
    <flux:separator variant="subtle" />

    <div class="p-6 bg-white rounded-xl shadow-md">
        <!-- Navigation Tabs -->
        <x-product-variant-navigation :product-id="$productId" :product="$product" active-step="meta" />

        <div class="mt-6">
            <h3 class="text-lg font-semibold mb-4">SEO Information</h3>
            <div class="bg-gray-50 p-6 rounded-lg shadow-sm">
                <!-- Product Info Header -->
                <x-product-info-header :product="$product" />

                <div class="max-w-3xl space-y-6">
                    <flux:input wire:model="meta_title" label="Meta Title" placeholder="Enter meta title" description="The title of the page as it appears in search engine results." />
                    
                    <flux:textarea wire:model="meta_description" label="Meta Description" placeholder="Enter meta description" rows="4" description="A brief summary of the page content." />
                    
                    <flux:input wire:model="meta_keywords" label="Meta Keywords" placeholder="keyword1, keyword2, keyword3" description="Comma-separated list of keywords relevant to the product." />
                    
                    <div class="flex mt-6">
                        <flux:spacer />
                        <flux:button wire:click="saveMeta" variant="primary" color="sky" wire:loading.attr="disabled" wire:target="saveMeta">
                            <span wire:loading.remove wire:target="saveMeta">Save Meta Information</span>
                            <span wire:loading wire:target="saveMeta">Saving...</span>
                        </flux:button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
