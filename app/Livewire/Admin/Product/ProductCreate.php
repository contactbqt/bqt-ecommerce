<?php

namespace App\Livewire\Admin\Product;

use Flux\Flux;
use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductCategory;
use App\Models\ProductFamily;
use App\Models\Tag;
use Carbon\Carbon;
use Livewire\WithFileUploads;
use Str;

#[Layout('components.layouts.admin-app')]
class ProductCreate extends Component
{
    public $product_type;
    public $product_name;
    public $slug;
    public $description;
    public $image;
    public $is_featured;
    public $status;
    public $category_id;
    
    // Single Product Properties
    public $price;
    public $offer_price;
    public $stock_qty;
    public $sku_code;
    public $family_id;
    public $new_family_name;

    public $additional_details = [];

    // Tag Properties
    public $selected_tags = [];
    public $new_tag_name;
    public $allTags = [];

    use WithFileUploads;

    public function mount()
    {
        $this->product_type = get_setting('PRODUCT_TYPE_ALLOWED', 'both');
        $this->is_featured = 0;
        $this->status = 1;
        $this->allTags = Tag::all();
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
        // Load from master table based on current category
        $masters = \App\Models\CategoryProductAdditionalInfoMaster::where('category_id', $this->category_id)->get();
        
        // Group masters by title and merge their field keys for those with identical titles
        $masterData = [];
        foreach ($masters as $master) {
            if (!isset($masterData[$master->title])) {
                $masterData[$master->title] = [];
            }
            if (is_array($master->additional_info)) {
                // Merge unique keys from all categories sharing the same section title
                $masterData[$master->title] = array_unique(array_merge($masterData[$master->title], $master->additional_info));
            }
        }

        $allSections = [];
        foreach ($masterData as $title => $masterKeys) {
            $fieldsList = [];
            foreach ($masterKeys as $key) {
                $fieldsList[] = ['key' => $key, 'value' => ''];
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
            'product_name' => 'required|string|unique:products,product_name',
            'slug' => 'required|string|unique:products,slug',
            'product_type' => 'required|string',
            'is_featured' => 'required|boolean',
            'category_id' => 'required',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'status'=>'required|boolean'
        ];
        
        if ($this->product_type === 'single') {
            $rules['price'] = 'required|numeric|min:0';
            $rules['stock_qty'] = 'required|integer|min:0';
            $rules['sku_code'] = 'nullable|string|max:255';
            $rules['family_id'] = 'nullable';
            $rules['new_family_name'] = 'required_if:family_id,new|nullable|string|max:255';
        }

        return $rules;
    }

    public function createProduct()
    {
        
        $this->validate();
        //dd('hh');
        $slug_str = Str::slug($this->slug);

        if ($this->image) {
            $this->image = $this->image->store('productImages', 'public');
        }

        if ($this->product_type === 'single' && $this->family_id === 'new' && $this->new_family_name) {
            $family = ProductFamily::create([
                'name' => $this->new_family_name,
                'slug' => Str::slug($this->new_family_name)
            ]);
            $this->family_id = $family->id;
        }

        $productData = [
            'product_name' => $this->product_name,
            'slug' => $slug_str,
            'family_id' => ($this->product_type === 'single' && $this->family_id !== 'new') ? $this->family_id : null,
            'product_type' => $this->product_type,
            'description' => $this->description,
            'image' => $this->image,
            'is_featured' => $this->is_featured ? 1 : 0,
            'status' => $this->status
        ];

        if ($this->product_type === 'single') {
            $productData['price'] = $this->price;
            $productData['offer_price'] = $this->offer_price;
            $productData['stock_qty'] = $this->stock_qty;
            $productData['sku_code'] = $this->sku_code;
        }

        $product = Product::create($productData);

        // Sync Tags
        if (!empty($this->selected_tags)) {
            $product->tags()->sync($this->selected_tags);
        }

        // Insert category into product_categories table
        if(!empty($this->category_id)){
            \App\Models\ProductCategory::create([
                'product_id' => $product->id,
                'category_id' => $this->category_id
            ]);
        }

        // Save Additional Details
        foreach ($this->additional_details as $detail) {
            if (empty($detail['title'])) continue;
            
            $dataMap = [];
            foreach ($detail['fields'] as $f) {
                if (!empty($f['key'])) {
                    $dataMap[$f['key']] = $f['value'];
                }
            }

            if (!empty($dataMap)) {
                \App\Models\ProductAdditionalInfo::create([
                    'product_id' => $product->id,
                    'title' => $detail['title'],
                    'additional_info' => $dataMap
                ]);
            }
        }


        //Reset the name after creation
        $this->reset();

        // Close the modal
        Flux::modal('create-product')->close();

        // Redirect to the next step in the flow
        return redirect()->route('admin.product.attributes', $product->id)->with('message', 'Product created successfully! Proceeding to assign attributes.');
    }

    public function render()
    {
        $data = array();
        $data['categories'] = Category::where('status', 1)->get();
        $data['productFamilies'] = ProductFamily::orderBy('name')->get();
        return view('livewire.admin.product.product-create', $data);
    }
}
