<?php

namespace App\Livewire\Admin\Product;

use Livewire\Component;
use App\Models\Product;
use App\Models\AttributeCategory;
use App\Models\AttributeValue;
use App\Models\ProductAttribute;


use Flux\Flux;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;
use DB;

#[Layout('components.layouts.admin-app')]
class ProductAttributeIndex extends Component
{
    use WithPagination;

    public $productId;
    public $product_type;
    public $product = [];
    public $attributeCategoryList = [];
    public $selectedAttributes = [];
    public $attributeSelections = [];

    public function mount($id)
    {
        $this->productId = $id;
        $this->product = Product::with('product_categories.categories', 'productAttributes.attribute', 'productAttributes.attributeValue')->findOrFail($id);
        $this->product_type = $this->product->product_type;

        $categoryIds = $this->product->product_categories->pluck('category_id')->toArray();
        $this->attributeCategoryList = AttributeCategory::with(['attribute', 'attributeValueCategories.attributeValue'])
            ->whereIn('category_id', $categoryIds)
            ->where('type', 'filter')
            ->whereHas('attribute', function ($query) {
                $query->where('is_filter', 1);
            })->get();
        
        $this->selectedAttributes = ($this->product) ? $this->product->productAttributes->pluck('attribute_value_id')->toArray() : [];
        
        if ($this->product_type === 'single') {
            foreach ($this->product->productAttributes as $pa) {
                $this->attributeSelections[$pa->attribute_id] = $pa->attribute_value_id;
            }
        }
        
    }

    public function toggleSelectAll($attributeId, $valueIds)
    {
        // Check if all these values are currently selected
        $allSelected = count(array_intersect($valueIds, $this->selectedAttributes)) === count($valueIds);

        if ($allSelected) {
            // Deselect all
            $this->selectedAttributes = array_diff($this->selectedAttributes, $valueIds);
        } else {
            // Select all (merge new ones avoiding duplicates)
            $this->selectedAttributes = array_unique(array_merge($this->selectedAttributes, $valueIds));
        }
    }

    public function saveProductAttributes($shouldContinue = false)
    {
        $finalAttributes = [];
        
        if ($this->product_type === 'single') {
            foreach ($this->attributeSelections as $attrId => $valId) {
                if (!empty($valId)) {
                    $finalAttributes[] = (int)$valId;
                }
            }
        } else {
            $finalAttributes = $this->selectedAttributes;
        }

        if(empty($finalAttributes)){
            return redirect()->route('admin.product.attributes', $this->productId)->with('error_message', 'Please select at least one attribute value.');
        }
        else{
            $rows = AttributeValue::query()
                    ->whereIn('id', $finalAttributes)
                    ->get(['id', 'attribute_id']);

            $result = $rows
                    ->groupBy('attribute_id')
                    ->map(fn ($items) => $items->pluck('id')->values()->toArray())
                    ->toArray();

            $attr_arr = [];
            foreach ($result as $attributeId => $valueIds) {
                foreach ($valueIds as $valueId) {
                    $attr_arr[] = [
                        'product_id' => $this->productId,
                        'attribute_id' => $attributeId,
                        'attribute_value_id' => $valueId,
                    ];
                }
            }

            DB::transaction(function () use ($attr_arr) {
                // Delete existing attributes for this product
                ProductAttribute::query()
                    ->where('product_id', $this->productId)
                    ->delete();
                ProductAttribute::query()->insert($attr_arr);
            });

            if ($shouldContinue) {
                if ($this->product_type === 'variant') {
                    return redirect()->route('admin.product.variants.create', $this->productId)->with('message', 'Product attributes updated successfully!');
                } else {
                    return redirect()->route('admin.product.images', $this->productId)->with('message', 'Product attributes updated successfully!');
                }
            }

            // Redirect to the index page (Stay)
            return redirect()->route('admin.product.attributes', $this->productId)->with('message', 'Product attributes updated successfully!');   


        }
    }

    public function render()
    {
        $data = array();
        return view('livewire.admin.product.product-attribute-index', $data);
    }





}
