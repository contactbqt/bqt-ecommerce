<?php

namespace App\Livewire\Admin\Product;

use Livewire\Component;
use App\Models\Product;
use App\Models\AttributeCategory;
use App\Models\AttributeValue;
use App\Models\ProductAttribute;
use App\Models\ProductVariant;
use App\Models\ProductVariantAttribute;
use Flux\Flux;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

#[Layout('components.layouts.admin-app')]

class ProductVariantCreate extends Component
{
    public $productId;
    public $product = [];
    public $attributeCategoryList = [];
    public $selectedVariantAttributes = []; // Inputs from checkbox
    public $generatedVariants = [];
    public $currentStep = 1;

    // Attributes that are already part of existing variants (to be disabled)
    public $usedAttributeValues = [];

    public function mount($id)
    {
        $this->productId = $id;
        $this->product = Product::with('product_categories.categories')->findOrFail($id);

        if ($this->product->product_type != 'variant') {
             return redirect()->route('admin.product.index')->with('error_message', 'This product is not a variant type.');
        }

        $categoryIds = $this->product->product_categories->pluck('category_id')->toArray();
        
        // Load attributes suitable for variants from the tagged categories
        $this->attributeCategoryList = AttributeCategory::with(['attribute', 'attributeValueCategories.attributeValue'])
            ->whereIn('category_id', $categoryIds)
            ->where('type', 'variant')
            ->whereHas('attribute', function ($query) {
                $query->where('is_variant', 1);
            })->get();
            
        // dd($this->attributeCategoryList[0]->attribute->attribute_values[0]->id);
        // Load IDs of attribute values that are already used in existing variants
        $usedIds = ProductVariantAttribute::whereHas('productVariant', function($q) use ($id) {
            $q->where('product_id', $id);
        })->pluck('attribute_value_id')->unique()->toArray();

        // STRICTLY cast to strings because HTML checkbox values are strings.
        // Guarantee sequential keys so Livewire treats it as a JSON array, not an object.
        $this->usedAttributeValues = array_values(array_map('strval', $usedIds));
        $this->selectedVariantAttributes = $this->usedAttributeValues;
    }

    public function toggleSelectAll($attributeId, $valueIds)
    {

        // We only toggle items that are NOT in the used list (since those are forced checked)
        $toggleableIds = array_diff($valueIds, $this->usedAttributeValues);

        if (empty($toggleableIds)) return;

        $allSelected = count(array_intersect($toggleableIds, $this->selectedVariantAttributes)) === count($toggleableIds);

        if ($allSelected) {
            $this->selectedVariantAttributes = array_values(array_diff($this->selectedVariantAttributes, $toggleableIds));
            // Re-add used attributes just in case diff removed them (though it shouldn't if specific)
            $this->selectedVariantAttributes = array_values(array_unique(array_merge($this->selectedVariantAttributes, $this->usedAttributeValues)));
        } else {
            $this->selectedVariantAttributes = array_values(array_unique(array_merge($this->selectedVariantAttributes, $toggleableIds)));
        }
    }

    public function generateCombinations()
    {
        // Convert boolean map to list of selected IDs
        // $selectedIds = array_keys(array_filter($this->selectedVariantAttributes, fn($val) => $val == true));

        $selectedIds = $this->selectedVariantAttributes;
        
        // ensure string comparison
        $selectedIdsComp = array_map('strval', $selectedIds);
        $usedIdsComp = array_map('strval', $this->usedAttributeValues);

        // dd($selectedIds);
        
        // Identify if user selected anything NEW
        $newlySelected = array_diff($selectedIdsComp, $usedIdsComp);

        // dd($newlySelected);
        
        if (empty($newlySelected)) {
             Flux::toast('Please select at least one new attribute to generate combinations.', variant: 'error');
             return;
        }

        // Ensure used attributes are always included
        $finalSelectedIds = array_unique(array_merge($selectedIds, $this->usedAttributeValues));

        if (empty($finalSelectedIds)) {
             $this->addError('selectedVariantAttributes', 'Please select at least one attribute.');
             return;
        }
        
        // Fetch full attribute objects
        $rows = AttributeValue::whereIn('id', $finalSelectedIds)->get();
        if ($rows->isEmpty()) return;

        // Group by Attribute ID
        $groupedAttributes = $rows->groupBy('attribute_id');
        
        // Cartesian Product
        $arrays = $groupedAttributes->map(fn($item) => $item->values())->toArray();
        $combinations = $this->cartesian($arrays);
        
        $newCount = 0;
        $skippedCount = 0;

        DB::transaction(function () use ($combinations, &$newCount, &$skippedCount) {
             foreach ($combinations as $attributes) {
                
                $attributeValueIds = array_column($attributes, 'id');
                sort($attributeValueIds);
                
                $exists = ProductVariant::where('product_id', $this->productId)
                    ->whereHas('attributes', function($q) use ($attributeValueIds) {
                         $q->whereIn('attribute_value_id', $attributeValueIds);
                    }, '=', count($attributeValueIds)) // Ensure strict match count
                    ->withCount('attributes')
                    ->having('attributes_count', '=', count($attributeValueIds))
                    ->exists();

                if ($exists) {
                    $skippedCount++;
                    continue;
                }

                $newCount++;

                // Construct Variant Name
                // We generally want the name to be "Attribute1 - Attribute2" ordering by something logic.
                // Let's rely on the incoming order from cartesian (category order).
                $variantName = implode('-', array_column($attributes, 'value_name'));
                
                $variant = ProductVariant::create([
                    'product_id' => $this->productId,
                    'variant_name' => $variantName,
                    'sku_code' => NULL,
                    'price' => 0,
                    'stock_qty' => 0,
                    'status' => 0,
                ]);
                
                foreach ($attributes as $attribute) {
                    ProductVariantAttribute::create([
                        'product_variant_id' => $variant->id,
                        'attribute_value_id' => $attribute['id'],
                    ]);
                }
             }
        });

        if ($newCount == 0 && $skippedCount > 0) {
             return redirect()->route('admin.product.variants.edit', $this->productId)
                ->with('message', "No new combinations created. $skippedCount existing variants were found.");
        } elseif ($newCount == 0) {
             Flux::toast('No attributes selected or no combinations possible.', variant: 'warning');
             return;
        }

        return redirect()->route('admin.product.variants.edit', $this->productId)
            ->with('message', "$newCount Variants generated successfully. " . ($skippedCount > 0 ? "($skippedCount skipped)" : ""));
    }

    private function cartesian($input) {
        $input = array_values($input);
        $result = [[]];

        foreach ($input as $key => $values) {
            $append = [];
            foreach ($result as $product) {
                foreach ($values as $item) {
                    $product[$key] = $item;
                    $append[] = $product;
                }
            }
            $result = $append;
        }

        return $result;
    }

    public function render()
    {
        return view('livewire.admin.product.product-variant-create');
    }
}
