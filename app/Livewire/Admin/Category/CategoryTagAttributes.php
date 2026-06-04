<?php

namespace App\Livewire\Admin\Category;

use Livewire\Component;
use App\Models\Category;
use App\Models\Attribute;
use App\Models\AttributeCategory;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.admin-app')]
class CategoryTagAttributes extends Component
{
    public $categoryId;
    public $category;
    public $selectedFilterAttributes = [];
    public $selectedVariantAttributes = [];
    public $selectedFilterAttributeValues = [];
    public $selectedVariantAttributeValues = [];
    public $attributeDisplayTypes = []; 
    public $allAttributes = [];

    public function mount($id)
    {
        $this->categoryId = $id;
        $this->category = Category::findOrFail($id);
        
        $this->allAttributes = Attribute::with(['attribute_values' => function($q) {
            $q->orderBy('sort_order', 'asc')->orderBy('value_name', 'asc');
        }])->orderBy('attribute_name')->get();
        
        // Load currently tagged attributes
        $tagged = AttributeCategory::where('category_id', $id)->get();
        
        $this->selectedFilterAttributes = $tagged->where('type', 'filter')
            ->pluck('attribute_id')
            ->map(fn($id) => (string)$id)
            ->toArray();

        $this->selectedVariantAttributes = $tagged->where('type', 'variant')
            ->pluck('attribute_id')
            ->map(fn($id) => (string)$id)
            ->toArray();
        
        // Load values for filters
        $filterTaggedIds = $tagged->where('type', 'filter')->pluck('id')->toArray();
        $this->selectedFilterAttributeValues = \App\Models\AttributeValueCategory::whereIn('attribute_category_id', $filterTaggedIds)
            ->pluck('attribute_value_name_category')
            ->map(fn($id) => (string)$id)
            ->toArray();

        // Load values for variants
        $variantTaggedIds = $tagged->where('type', 'variant')->pluck('id')->toArray();
        $this->selectedVariantAttributeValues = \App\Models\AttributeValueCategory::whereIn('attribute_category_id', $variantTaggedIds)
            ->pluck('attribute_value_name_category')
            ->map(fn($id) => (string)$id)
            ->toArray();
        
        foreach ($tagged as $item) {
            $this->attributeDisplayTypes[$item->attribute_id] = $item->display_type;
        }

        // Pre-fill defaults for the rest
        foreach ($this->allAttributes as $attr) {
            if (!isset($this->attributeDisplayTypes[$attr->id])) {
                $this->attributeDisplayTypes[$attr->id] = 'text';
            }
        }
    }

    public function save()
    {
        // Get existing tags to cleanly delete associated children values
        $existingTags = AttributeCategory::where('category_id', $this->categoryId)->pluck('id');
        if ($existingTags->isNotEmpty()) {
            \App\Models\AttributeValueCategory::whereIn('attribute_category_id', $existingTags)->delete();
        }

        // Clear existing tags
        AttributeCategory::where('category_id', $this->categoryId)->delete();
        
        // Save Filter Attributes
        foreach (array_unique($this->selectedFilterAttributes) as $attributeId) {
            if ($attributeId) {
                $attrCat = AttributeCategory::create([
                    'category_id' => $this->categoryId,
                    'attribute_id' => $attributeId,
                    'type' => 'filter',
                    'display_type' => $this->attributeDisplayTypes[$attributeId] ?? 'text', 
                    'sort_order' => 0,
                ]);

                $attrModel = $this->allAttributes->firstWhere('id', $attributeId);
                if ($attrModel && !empty($this->selectedFilterAttributeValues)) {
                    $validIdsForAttr = $attrModel->attribute_values->pluck('id')->map(fn($id) => (string)$id)->toArray();
                    $valuesToSave = array_intersect($this->selectedFilterAttributeValues, $validIdsForAttr);
                    
                    foreach ($valuesToSave as $valId) {
                        \App\Models\AttributeValueCategory::create([
                            'attribute_category_id' => $attrCat->id,
                            'attribute_id' => $attributeId,
                            'attribute_value_name_category' => $valId
                        ]);
                    }
                }
            }
        }

        // Save Variant Attributes
        foreach (array_unique($this->selectedVariantAttributes) as $attributeId) {
            if ($attributeId) {
                $attrCat = AttributeCategory::create([
                    'category_id' => $this->categoryId,
                    'attribute_id' => $attributeId,
                    'type' => 'variant',
                    'display_type' => $this->attributeDisplayTypes[$attributeId] ?? 'text', 
                    'sort_order' => 0,
                ]);

                $attrModel = $this->allAttributes->firstWhere('id', $attributeId);
                if ($attrModel && !empty($this->selectedVariantAttributeValues)) {
                    $validIdsForAttr = $attrModel->attribute_values->pluck('id')->map(fn($id) => (string)$id)->toArray();
                    $valuesToSave = array_intersect($this->selectedVariantAttributeValues, $validIdsForAttr);
                    
                    foreach ($valuesToSave as $valId) {
                        \App\Models\AttributeValueCategory::create([
                            'attribute_category_id' => $attrCat->id,
                            'attribute_id' => $attributeId,
                            'attribute_value_name_category' => $valId
                        ]);
                    }
                }
            }
        }
        
        session()->flash('message', 'Category attributes updated successfully.');
        return redirect(request()->header('Referer'));
    }

    public function render()
    {
        return view('livewire.admin.category.category-tag-attributes');
    }
}
