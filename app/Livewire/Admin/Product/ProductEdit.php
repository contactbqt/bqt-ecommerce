<?php

namespace App\Livewire\Admin\Product;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Product;
use App\Models\Category;
use App\Models\CategoryProductAdditionalInfoMaster;
use App\Models\ProductAdditionalInfo;
use App\Models\Tag;
use Flux\Flux;
use Livewire\WithFileUploads;
use Str;

#[Layout('components.layouts.admin-app')]
class ProductEdit extends Component
{
    public $productId;
    public $product;
    public $product_type;
    public $product_name;
    public $slug;
    public $description;
    public $image;
    public $is_featured;
    public $status;
    public $category_id;
    public $existing_image = null;
    public $deleteImg = false;
    // Single Product Properties
    public $price;
    public $offer_price;
    public $stock_qty;
    public $sku_code;
    
    public $additional_details = [];

    // Tag Properties
    public $selected_tags = [];
    public $new_tag_name;
    public $allTags = [];

    use WithFileUploads;

    public function mount($id)
    {
        $this->product = Product::findOrFail($id);
        $product = $this->product;
        $this->productId = $product->id;
        $this->product_type = $product->product_type;
        $this->product_name = $product->product_name;
        $this->slug = $product->slug;
        $this->description = $product->description;
        $this->existing_image = $product->image;
        $this->is_featured = $product->is_featured;
        $this->status = $product->status;
        $this->category_id = $product->product_categories->pluck('category_id')->first();
        
        if ($this->product_type === 'single') {
            $this->price = $product->price;
            $this->offer_price = $product->offer_price;
            $this->stock_qty = $product->stock_qty;
            $this->sku_code = $product->sku_code;
        }

        // Load Tags
        $this->allTags = Tag::all();
        $this->selected_tags = $product->tags->pluck('id')->map(fn($id) => (string)$id)->toArray();

        // Load Additional Details
        $this->loadAdditionalDetails();
    }

    public function addNewTag()
    {
        if (empty($this->new_tag_name)) {
            return;
        }

        $tag = Tag::firstOrCreate([
            'name' => trim($this->new_tag_name),
            'slug' => Str::slug($this->new_tag_name)
        ]);

        if (!in_array($tag->id, $this->selected_tags)) {
            $this->selected_tags[] = (string)$tag->id;
        }

        $this->allTags = Tag::all();
        $this->new_tag_name = '';
        
        $this->dispatch('notify', message: 'New tag added successfully!');
    }

    public function updatedCategoryId()
    {
        $this->loadAdditionalDetails();
    }

    public function loadAdditionalDetails()
    {
        $existingInfos = \App\Models\ProductAdditionalInfo::where('product_id', $this->productId)->get()->keyBy('title');
        
        // Load from master table based on current category
        $masters = \App\Models\CategoryProductAdditionalInfoMaster::where('category_id', $this->category_id)->get();
        
        // Group masters by title and merge their field keys
        $masterData = [];
        foreach ($masters as $master) {
            if (!isset($masterData[$master->title])) {
                $masterData[$master->title] = [];
            }
            if (is_array($master->additional_info)) {
                // Merge keys from all categories that share this section title
                $masterData[$master->title] = array_unique(array_merge($masterData[$master->title], $master->additional_info));
            }
        }

        $allSections = [];

        // 1. Process Master Sections (Merged from all categories)
        foreach ($masterData as $title => $masterKeys) {
            $sectionFields = [];
            $existing = $existingInfos->get($title);
            $existingDataMap = $existing ? $existing->additional_info : [];

            // Add all master keys from all categories (preserving existing values if any)
            foreach ($masterKeys as $key) {
                $sectionFields[$key] = $existingDataMap[$key] ?? '';
                if (isset($existingDataMap[$key])) {
                    unset($existingDataMap[$key]); // Mark as consumed
                }
            }

            // Add remaining keys that were only in the product for this specific section
            foreach ($existingDataMap as $key => $value) {
                $sectionFields[$key] = $value;
            }

            $fieldsList = [];
            foreach ($sectionFields as $k => $v) {
                $fieldsList[] = ['key' => $k, 'value' => $v];
            }

            $allSections[$title] = [
                'title' => $title,
                'fields' => $fieldsList
            ];
            
            $existingInfos->forget($title); // Mark this entire section title as processed
        }

        // 2. Add remaining product-specific sections not found in any of the Master categories
        foreach ($existingInfos as $title => $info) {
            $fieldsList = [];
            if (is_array($info->additional_info)) {
                foreach ($info->additional_info as $k => $v) {
                    $fieldsList[] = ['key' => $k, 'value' => $v];
                }
            }
            $allSections[$title] = [
                'title' => $title,
                'fields' => $fieldsList
            ];
        }

        $this->additional_details = array_values($allSections);
    }

    public function addDetailSection()
    {
        $this->additional_details[] = [
            'title' => '',
            'fields' => [['key' => '', 'value' => '']]
        ];
    }

    public function removeDetailSection($index)
    {
        unset($this->additional_details[$index]);
        $this->additional_details = array_values($this->additional_details);
    }

    public function addDetailField($sectionIdx)
    {
        $this->additional_details[$sectionIdx]['fields'][] = ['key' => '', 'value' => ''];
    }

    public function removeDetailField($sectionIdx, $fieldIdx)
    {
        unset($this->additional_details[$sectionIdx]['fields'][$fieldIdx]);
        $this->additional_details[$sectionIdx]['fields'] = array_values($this->additional_details[$sectionIdx]['fields']);
    }

    protected function rules()
    {
        $rules = [
            'product_name' => 'required|string|unique:products,product_name,'. $this->productId.',id',
            'slug' => 'required|string|unique:products,slug,'. $this->productId.',id',
            'product_type' => 'required|string',
            'is_featured' => 'required|boolean',
            'category_id' => 'required',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'status'=>'required|boolean'
        ];

        if ($this->image) { // Only validate if a file was actually chosen
            $rules['image'] = 'image';
        }
        
        if ($this->product_type === 'single') {
            $rules['price'] = 'required|numeric|min:0';
            $rules['stock_qty'] = 'required|integer|min:0';
            $rules['sku_code'] = 'nullable|string|max:255';
        }

        return $rules;

    }


    public function updateGeneralInfo()
    {
        $this->validate();

        $slug = $this->slug ? Str::slug($this->slug) : Str::slug($this->product_name);

        $productData = Product::findOrFail($this->productId);
        $productData->product_name = $this->product_name;
        $productData->slug = $slug;
        $productData->description = $this->description;
        $productData->is_featured = $this->is_featured;
        $productData->status = $this->status;
        
        if ($this->product_type === 'single') {
            $productData->price = $this->price;
            $productData->offer_price = $this->offer_price;
            $productData->stock_qty = $this->stock_qty;
            $productData->sku_code = $this->sku_code;
        }

        if ($this->image) {
            if ($this->existing_image) {
                $oldImagePath = public_path('storage/' . $this->existing_image);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
            $productData->image = $this->image->store('productImages', 'public_uploads');
            $this->existing_image = $productData->image;
            $this->image = null; // Clear temp image
        } else {
            if ($this->deleteImg && $this->existing_image) {
                $oldImagePath = public_path('storage/' . $this->existing_image);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
                $productData->image = null;
                $this->existing_image = null;
                $this->deleteImg = false;
            }
        }

        $productData->save();

        // Sync Tags
        $productData->tags()->sync($this->selected_tags);

        \App\Models\ProductCategory::where('product_id', $this->productId)->delete();
        if(!empty($this->category_id)){
            \App\Models\ProductCategory::create([
                'product_id' => $this->productId,
                'category_id' => $this->category_id
            ]);
        }

        session()->flash('info_message', 'Product information updated successfully.');
    }

    public function updateAdditionalSpecs()
    {
        // Save Additional Details
        \App\Models\ProductAdditionalInfo::where('product_id', $this->productId)->delete();
        foreach ($this->additional_details as $detail) {
            if (empty($detail['title'])) continue;
            
            $dataMap = [];
            foreach ($detail['fields'] as $f) {
                if (!empty($f['key'])) {
                    $dataMap[$f['key']] = $f['value'];
                }
            }

            \App\Models\ProductAdditionalInfo::create([
                'product_id' => $this->productId,
                'title' => $detail['title'],
                'additional_info' => $dataMap
            ]);
        }

        session()->flash('specs_message', 'Additional specifications updated successfully.');
    }

    public function render()
    {
        $data = array();
        $data['categories'] = Category::where('status', 1)->get();
        return view('livewire.admin.product.product-edit', $data);
    }
}
