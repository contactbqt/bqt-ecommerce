@props(['productId', 'product', 'activeStep' => null])

@php
    // Determine the product type safely
    $type = is_array($product) ? ($product['product_type'] ?? '') : ($product->product_type ?? '');
    
    // Determine the current route to set active state
    $currentRoute = Route::currentRouteName();
    
    $isStep1 = $activeStep === 'info' || $currentRoute === 'admin.product.edit';
    $isStep2 = $activeStep === 'attributes' || $currentRoute === 'admin.product.attributes';
    $isStep3 = $activeStep === 'combinations' || $currentRoute === 'admin.product.variants.create';
    $isStep4 = $activeStep === 'variants' || in_array($currentRoute, ['admin.product.variants', 'admin.product.variants.edit']);
    $isStep5 = $activeStep === 'images' || $currentRoute === 'admin.product.images';
    $isStep6 = $activeStep === 'meta' || $currentRoute === 'admin.product.meta';
@endphp

<div class="flex items-center justify-between mb-4 bg-gray-100 p-4 rounded-lg overflow-x-auto">
    <div class="flex items-center gap-2 whitespace-nowrap">
        {{-- 1. Product Information --}}
        <flux:button 
            href="{{ route('admin.product.edit', $productId) }}" 
            variant="{{ $isStep1 ? 'primary' : 'ghost' }}">
            Product Information
        </flux:button>

        {{-- 2. Product Attributes --}}
        <flux:button 
            href="{{ route('admin.product.attributes', $productId) }}" 
            variant="{{ $isStep2 ? 'primary' : 'ghost' }}">
            Product Attributes
        </flux:button>

        {{-- 3 & 4. Combinations & Variants (Dependent on Type) --}}
        @if($type === 'variant')
            <flux:button 
                href="{{ route('admin.product.variants.create', $productId) }}" 
                variant="{{ $isStep3 ? 'primary' : 'ghost' }}">
                Create Combinations
            </flux:button>

            <flux:button 
                href="{{ route('admin.product.variants.edit', $productId) }}" 
                variant="{{ $isStep4 ? 'primary' : 'ghost' }}">
                Variants & Pricing
            </flux:button>
        @endif

        {{-- 5. Manage Images --}}
        <flux:button 
            href="{{ route('admin.product.images', $productId) }}" 
            variant="{{ $isStep5 ? 'primary' : 'ghost' }}">
            Manage Images
        </flux:button>

        {{-- 6. Meta Management --}}
        <flux:button 
            href="{{ route('admin.product.meta', $productId) }}" 
            variant="{{ $isStep6 ? 'primary' : 'ghost' }}">
            Meta Management
        </flux:button>
    </div>
</div>
