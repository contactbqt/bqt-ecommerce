<?php

namespace App\Livewire\Admin\Import;

use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Category;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\AttributeCategory;
use App\Models\AttributeValueCategory;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductCategory;
use App\Models\ProductAttribute;
use App\Models\ProductVariantAttribute;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.admin-app')]
class ImportIndex extends Component
{
    use WithFileUploads;

    public $activeTab = 'categories';
    public $importFile;
    public $importData = [];
    public $validationErrors = [];
    public $step = 1; // 1: Upload, 2: Preview, 3: Confirm

    protected $rules = [
        'importFile' => 'required|mimes:xlsx,csv,xls|max:10240',
    ];

    public function setTab($tab)
    {
        $this->activeTab = $tab;
        $this->reset(['importFile', 'importData', 'validationErrors', 'step']);
    }

    public function validateImport()
    {
        $this->validate();

        $path = $this->importFile->getRealPath();
        $data = Excel::toArray([], $path)[0];

        if (count($data) <= 1) {
            $this->addError('importFile', 'The file is empty or missing data rows.');
            return;
        }

        $headers = array_shift($data);
        $expectedHeaders = $this->getExpectedHeaders();

        // Basic header matching check
        $missingHeaders = array_diff($expectedHeaders, $headers);
        if (!empty($missingHeaders)) {
            session()->flash('error', 'Invalid template for the "' . $this->activeTab . '" tab. Missing columns: ' . implode(', ', $missingHeaders));
            return;
        }

        $this->importData = [];
        $this->validationErrors = [];
        $uploadedMainSlugs = [];

        // First pass: Collect all main product slugs being uploaded in this sheet
        if ($this->activeTab === 'products') {
            foreach ($data as $row) {
                if (empty(array_filter($row))) continue;
                $tempItem = array_combine($headers, array_slice(array_pad($row, count($headers), null), 0, count($headers)));
                if (empty($tempItem['sku_code']) && !empty($tempItem['main_product_slug'])) {
                    $uploadedMainSlugs[] = $tempItem['main_product_slug'];
                }
            }
        }

        foreach ($data as $index => $row) {
            if (empty(array_filter($row))) continue;

            // Pad row if it's shorter than headers
            if (count($row) < count($headers)) {
                $row = array_pad($row, count($headers), null);
            }
            // Trim row if it's longer than headers
            if (count($row) > count($headers)) {
                $row = array_slice($row, 0, count($headers));
            }

            $item = array_combine($headers, $row);
            
            // Check for duplicates in database
            $item['is_duplicate'] = false;
            
            if ($this->activeTab === 'products') {
                $type = strtolower($item['product_type (single, variant)'] ?? '');
                if (empty($item['sku_code'])) {
                    // It's a parent product definition (Single or Variant Parent)
                    $item['is_duplicate'] = Product::where('slug', $item['main_product_slug'])->exists();
                } else {
                    // It's a variant entry or a single product entry with SKU
                    if ($type === 'single') {
                        $item['is_duplicate'] = Product::where('slug', $item['main_product_slug'])->exists();
                    } else {
                        $item['is_duplicate'] = ProductVariant::where('sku_code', $item['sku_code'])->exists();
                    }
                }
            } elseif ($this->activeTab === 'categories' && !empty($item['slug'])) {
                $item['is_duplicate'] = Category::where('slug', $item['slug'])->exists();
            } elseif ($this->activeTab === 'attributes' && !empty($item['slug'])) {
                $item['is_duplicate'] = Attribute::where('slug', $item['slug'])->exists();
            } elseif ($this->activeTab === 'attribute_tagging' && !empty($item['category_slug']) && !empty($item['attribute_slug'])) {
                $category = Category::where('slug', $item['category_slug'])->first();
                $attribute = Attribute::where('slug', $item['attribute_slug'])->first();
                if ($category && $attribute) {
                    $itemType = strtolower($item['type (filter, variant, both)'] ?? 'filter');
                    if ($itemType === 'both') {
                        // For 'both', check if both 'filter' and 'variant' entries exist
                        $filterExists = AttributeCategory::where([
                            'category_id' => $category->id,
                            'attribute_id' => $attribute->id,
                            'type' => 'filter'
                        ])->exists();
                        $variantExists = AttributeCategory::where([
                            'category_id' => $category->id,
                            'attribute_id' => $attribute->id,
                            'type' => 'variant'
                        ])->exists();
                        $item['is_duplicate'] = $filterExists && $variantExists;
                    } else {
                        $item['is_duplicate'] = AttributeCategory::where([
                            'category_id' => $category->id,
                            'attribute_id' => $attribute->id,
                            'type' => $itemType
                        ])->exists();
                    }
                }
            }

            $errors = $this->validateRow($item, $uploadedMainSlugs);

            if (!empty($errors)) {
                $this->validationErrors[$index] = $errors;
            }

            $this->importData[$index] = $item;
        }

        $this->step = 2;
    }

    private function getExpectedHeaders()
    {
        if ($this->activeTab === 'categories') {
            return ['name', 'slug', 'parent_slug', 'is_featured (1,0)', 'sort_order', 'status (1,0)'];
        } elseif ($this->activeTab === 'attributes') {
            return ['name', 'slug', 'display_type (dropdown, checkbox, radio)', 'is_variant (1, 0)', 'is_filter (1, 0)', 'status (active, inactive)'];
        } elseif ($this->activeTab === 'attribute_values') {
            return ['attribute_slug', 'value', 'status'];
        } elseif ($this->activeTab === 'attribute_tagging') {
            return ['category_slug', 'attribute_slug', 'attribute_value_slugs (comma separated)', 'type (filter, variant, both)', 'display_type (text, color, image)', 'sort_order'];
        } elseif ($this->activeTab === 'products') {
            return ['product_type (single, variant)', 'main_product_slug', 'product_name', 'variant_name', 'sku_code', 'category_slug', 'price', 'offer_price', 'stock_qty', 'description', 'is_featured (1,0)', 'status (1,0)', 'filter_attributes (comma separated)', 'variant_attributes (comma separated)'];
        }
        return [];
    }

    public function downloadTemplate()
    {
        $headers = $this->getExpectedHeaders();
        $filename = $this->activeTab . '_template.xlsx';

        return Excel::download(new TemplateExport($headers), $filename);
    }

    protected function validateRow($item, $uploadedMainSlugs = [])
    {
        $errors = [];
        if ($this->activeTab === 'categories') {
            if (empty($item['name'])) $errors[] = "Category Name is required";
            if (empty($item['slug'])) $errors[] = "Category Slug is required";
        } elseif ($this->activeTab === 'attributes') {
            if (empty($item['name'])) $errors[] = "Attribute Name is required";
            if (empty($item['slug'])) $errors[] = "Attribute Slug is required";
        } elseif ($this->activeTab === 'attribute_values') {
            if (empty($item['attribute_slug'])) $errors[] = "Attribute Slug is required";
            if (empty($item['value'])) $errors[] = "Value is required";
        } elseif ($this->activeTab === 'attribute_tagging') {
            if (empty($item['category_slug'])) $errors[] = "Category Slug is required";
            if (empty($item['attribute_slug'])) $errors[] = "Attribute Slug is required";
            $validTypes = ['filter', 'variant', 'both'];
            $itemType = strtolower($item['type (filter, variant, both)'] ?? 'filter');
            if (!in_array($itemType, $validTypes)) {
                $errors[] = "Type must be 'filter', 'variant', or 'both'";
            }
        } elseif ($this->activeTab === 'products') {
            $type = strtolower($item['product_type (single, variant)'] ?? '');
            if (empty($type)) $errors[] = "Product Type is required";
            if (empty($item['main_product_slug'])) $errors[] = "Main Product Slug is required";
            
            // Logic: Skip price validation only for the "Main Product Definition" row of a variant product type.
            // These rows have empty sku_code and are used to define the parent record in the products table.
            $isVariantParent = ($type === 'variant' && empty($item['sku_code']));
            
            if (!$isVariantParent) {
                if (empty($item['price'])) $errors[] = "Price is required";
            }
            
            if (empty($item['sku_code'])) {
                // Main Product Definition
                if (empty($item['product_name'])) $errors[] = "Product Name is required for main product";
            } else {
                // Variant Entry or Single with SKU
                if ($type === 'variant') {
                    if (empty($item['variant_name'])) $errors[] = "Variant Name is required";
                    
                    // Check if main product exists in DB OR is being uploaded in this sheet
                    $mainSlug = $item['main_product_slug'];
                    $existsInDb = Product::where('slug', $mainSlug)->exists();
                    $isBeingUploaded = in_array($mainSlug, $uploadedMainSlugs);

                    if (!$existsInDb && !$isBeingUploaded) {
                        $errors[] = "Main product with slug '{$mainSlug}' must be defined in the sheet (with empty SKU) or already exist in the database";
                    }
                }
            }
        }
        return $errors;
    }

    public function confirmImport()
    {
        if (!empty($this->validationErrors)) {
            session()->flash('error', 'Please fix validation errors before importing.');
            return;
        }

        DB::beginTransaction();

        try {
            if ($this->activeTab === 'categories') {
                foreach ($this->importData as $item) {
                    // Skip duplicates
                    if ($item['is_duplicate'] ?? false) continue;

                    $parentId = 0;
                    if (!empty($item['parent_slug'])) {
                        $parent = Category::where('slug', $item['parent_slug'])->first();
                        $parentId = $parent ? $parent->id : 0;
                    }

                    Category::create([
                        'slug' => $item['slug'],
                        'category_name' => $item['name'],
                        'parent_id' => $parentId,
                        'is_featured' => $item['is_featured'] ?? 0,
                        'sort_order' => $item['sort_order'] ?? 0,
                        'status' => (isset($item['status']) && ($item['status'] == 'inactive' || $item['status'] == '0')) ? 0 : 1,
                    ]);
                }
            } elseif ($this->activeTab === 'attributes') {
                foreach ($this->importData as $item) {
                    // Skip duplicates
                    if ($item['is_duplicate'] ?? false) continue;

                    Attribute::create([
                        'slug' => $item['slug'],
                        'attribute_name' => $item['name'],
                        'is_variant' => $item['is_variant (1, 0)'] ?? 0,
                        'is_filter' => $item['is_filter (1, 0)'] ?? 0,
                        'display_type' => $item['display_type (dropdown, checkbox, radio)'] ?? 'dropdown',
                        'status' => $item['status (active, inactive)'] ?? 'active',
                    ]);
                }
            } elseif ($this->activeTab === 'attribute_values') {
                foreach ($this->importData as $item) {
                    $attribute = Attribute::where('slug', $item['attribute_slug'])->first();
                    if ($attribute) {
                        AttributeValue::updateOrCreate(
                            [
                                'attribute_id' => $attribute->id,
                                'slug' => $item['slug'],
                            ],
                            [
                                'value_name' => $item['value'],
                                'status' => $item['status'] ?? 'active',
                            ]
                        );
                    }
                }
            } elseif ($this->activeTab === 'attribute_tagging') {
                foreach ($this->importData as $item) {
                    $category = Category::where('slug', $item['category_slug'])->first();
                    $attribute = Attribute::where('slug', $item['attribute_slug'])->first();

                    if ($category && $attribute) {
                        // Determine which types to create
                        $typesToCreate = [];
                        $itemType = strtolower($item['type (filter, variant, both)'] ?? 'filter');

                        if ($itemType === 'both') {
                            $typesToCreate = ['filter', 'variant'];
                        } else {
                            $typesToCreate = [$itemType];
                        }

                        foreach ($typesToCreate as $type) {
                            // 1. Create or Update AttributeCategory entry for each type
                            $attrCategory = AttributeCategory::updateOrCreate(
                                [
                                    'category_id' => $category->id,
                                    'attribute_id' => $attribute->id,
                                    'type' => $type,
                                ],
                                [
                                    'display_type' => $item['display_type (text, color, image)'] ?? 'text',
                                    'sort_order' => $item['sort_order'] ?? 0,
                                ]
                            );

                            // 2. Handle AttributeValueCategory entries
                            if (!empty($item['attribute_value_slugs (comma separated)'])) {
                                $valueSlugs = explode(',', $item['attribute_value_slugs (comma separated)']);
                                foreach ($valueSlugs as $valueSlug) {
                                    $valueSlug = trim($valueSlug);
                                    if (!empty($valueSlug)) {
                                        $attrValue = AttributeValue::where('attribute_id', $attribute->id)
                                            ->where('slug', $valueSlug)
                                            ->first();

                                        if ($attrValue) {
                                            AttributeValueCategory::updateOrCreate([
                                                'attribute_id' => $attribute->id,
                                                'attribute_category_id' => $attrCategory->id,
                                                'attribute_value_name_category' => $attrValue->id,
                                            ]);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            } elseif ($this->activeTab === 'products') {
                foreach ($this->importData as $item) {
                    // Skip duplicates
                    if ($item['is_duplicate'] ?? false) continue;

                    $productType = strtolower($item['product_type (single, variant)'] ?? 'single');
                    
                    // Filter Attributes for ProductAttribute table
                    $filterAttrsRaw = $item['filter_attributes (comma separated)'] ?? '';
                    $filterAttrs = !empty($filterAttrsRaw) ? explode(',', $filterAttrsRaw) : [];
                    
                    // Variant Attributes for ProductVariantAttribute table
                    $variantAttrsRaw = $item['variant_attributes (comma separated)'] ?? '';
                    $variantAttrs = !empty($variantAttrsRaw) ? explode(',', $variantAttrsRaw) : [];

                    if (empty($item['sku_code'])) {
                        // This is a Main Product Definition (Single or Variant Parent)
                        $product = Product::create([
                            'slug' => $item['main_product_slug'],
                            'product_name' => $item['product_name'],
                            'product_type' => $productType,
                            'sku_code' => null,
                            'description' => $item['description'] ?? null,
                            'price' => !empty($item['price']) ? $item['price'] : 0,
                            'offer_price' => $item['offer_price'] ?? null,
                            'stock_qty' => 0,
                            'is_featured' => $item['is_featured (1,0)'] ?? 0,
                            'status' => $item['status (1,0)'] ?? 1,
                        ]);

                        // 1. Tag with categories
                        if (!empty($item['category_slug'])) {
                            $category = Category::where('slug', $item['category_slug'])->first();
                            if ($category) {
                                ProductCategory::updateOrCreate([
                                    'product_id' => $product->id,
                                    'category_id' => $category->id
                                ]);
                            }
                        }

                        // 2. Tag with filter attributes (ProductAttribute table)
                        foreach ($filterAttrs as $attrPair) {
                            $attrPair = trim($attrPair);
                            if (str_contains($attrPair, ':')) {
                                [$attrSlug, $valSlug] = explode(':', $attrPair);
                                $attribute = Attribute::where('slug', trim($attrSlug))->first();
                                if ($attribute) {
                                    $attrValue = AttributeValue::where('attribute_id', $attribute->id)
                                        ->where('slug', trim($valSlug))
                                        ->first();
                                    
                                    if ($attrValue) {
                                        ProductAttribute::updateOrCreate([
                                            'product_id' => $product->id,
                                            'attribute_id' => $attribute->id,
                                            'attribute_value_id' => $attrValue->id,
                                        ]);
                                    }
                                }
                            } else {
                                // Fallback to previous logic if no colon is present
                                $attrValue = is_numeric($attrPair) 
                                    ? AttributeValue::find($attrPair) 
                                    : AttributeValue::where('slug', $attrPair)->first();

                                if ($attrValue) {
                                    ProductAttribute::updateOrCreate([
                                        'product_id' => $product->id,
                                        'attribute_id' => $attrValue->attribute_id,
                                        'attribute_value_id' => $attrValue->id,
                                    ]);
                                }
                            }
                        }
                    } else {
                        // This is a Specific Product Entry (Single Product with SKU or a Variant Entry)
                        if ($productType === 'single') {
                            $product = Product::create([
                                'slug' => $item['main_product_slug'],
                                'product_name' => $item['product_name'],
                                'product_type' => 'single',
                                'sku_code' => $item['sku_code'],
                                'description' => $item['description'] ?? null,
                                'price' => $item['price'],
                                'offer_price' => $item['offer_price'] ?? null,
                                'stock_qty' => $item['stock_qty'] ?? 0,
                                'is_featured' => $item['is_featured (1,0)'] ?? 0,
                                'status' => $item['status (1,0)'] ?? 1,
                            ]);

                            // Tag with categories
                            if (!empty($item['category_slug'])) {
                                $category = Category::where('slug', $item['category_slug'])->first();
                                if ($category) {
                                    ProductCategory::updateOrCreate([
                                        'product_id' => $product->id,
                                        'category_id' => $category->id
                                    ]);
                                }
                            }

                            // Tag with filter attributes (ProductAttribute table)
                            foreach ($filterAttrs as $attrPair) {
                                $attrPair = trim($attrPair);
                                if (str_contains($attrPair, ':')) {
                                    [$attrSlug, $valSlug] = explode(':', $attrPair);
                                    $attribute = Attribute::where('slug', trim($attrSlug))->first();
                                    if ($attribute) {
                                        $attrValue = AttributeValue::where('attribute_id', $attribute->id)
                                            ->where('slug', trim($valSlug))
                                            ->first();
                                        
                                        if ($attrValue) {
                                            ProductAttribute::updateOrCreate([
                                                'product_id' => $product->id,
                                                'attribute_id' => $attribute->id,
                                                'attribute_value_id' => $attrValue->id,
                                            ]);
                                        }
                                    }
                                } else {
                                    // Fallback to previous logic
                                    $attrValue = is_numeric($attrPair) 
                                        ? AttributeValue::find($attrPair) 
                                        : AttributeValue::where('slug', $attrPair)->first();

                                    if ($attrValue) {
                                        ProductAttribute::updateOrCreate([
                                            'product_id' => $product->id,
                                            'attribute_id' => $attrValue->attribute_id,
                                            'attribute_value_id' => $attrValue->id,
                                        ]);
                                    }
                                }
                            }
                        } elseif ($productType === 'variant') {
                            $mainProduct = Product::where('slug', $item['main_product_slug'])->first();
                            if ($mainProduct) {
                                $variant = ProductVariant::create([
                                    'sku_code' => $item['sku_code'],
                                    'product_id' => $mainProduct->id,
                                    'product_name' => $mainProduct->product_name,
                                    'variant_name' => $item['variant_name'],
                                    'price' => $item['price'],
                                    'offer_price' => $item['offer_price'] ?? null,
                                    'stock_qty' => $item['stock_qty'] ?? 0,
                                    'status' => $item['status (1,0)'] ?? 1,
                                ]);

                                // Tag with filter attributes (ProductAttribute table) for the main product
                                foreach ($filterAttrs as $attrPair) {
                                    $attrPair = trim($attrPair);
                                    if (str_contains($attrPair, ':')) {
                                        [$attrSlug, $valSlug] = explode(':', $attrPair);
                                        $attribute = Attribute::where('slug', trim($attrSlug))->first();
                                        if ($attribute) {
                                            $attrValue = AttributeValue::where('attribute_id', $attribute->id)
                                                ->where('slug', trim($valSlug))
                                                ->first();
                                            
                                            if ($attrValue) {
                                                ProductAttribute::updateOrCreate([
                                                    'product_id' => $mainProduct->id,
                                                    'attribute_id' => $attribute->id,
                                                    'attribute_value_id' => $attrValue->id,
                                                ]);
                                            }
                                        }
                                    } else {
                                        // Fallback to previous logic
                                        $attrValue = is_numeric($attrPair) 
                                            ? AttributeValue::find($attrPair) 
                                            : AttributeValue::where('slug', $attrPair)->first();

                                        if ($attrValue) {
                                            ProductAttribute::updateOrCreate([
                                                'product_id' => $mainProduct->id,
                                                'attribute_id' => $attrValue->attribute_id,
                                                'attribute_value_id' => $attrValue->id,
                                            ]);
                                        }
                                    }
                                }

                                // Handle variant specific attributes (ProductVariantAttribute table)
                                foreach ($variantAttrs as $attrValueId) {
                                    $attrValueId = trim($attrValueId);
                                    if (!empty($attrValueId)) {
                                        $attrValue = is_numeric($attrValueId) 
                                            ? AttributeValue::find($attrValueId) 
                                            : AttributeValue::where('slug', $attrValueId)->first();

                                        if ($attrValue) {
                                            ProductVariantAttribute::create([
                                                'product_variant_id' => $variant->id,
                                                'attribute_value_id' => $attrValue->id,
                                            ]);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }

            DB::commit();
            session()->flash('message', 'Import completed successfully.');
            $this->reset(['importFile', 'importData', 'validationErrors', 'step']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Import failed: ' . $e->getMessage());
            session()->flash('error', 'Error during import: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.import.import-index');
    }
}
