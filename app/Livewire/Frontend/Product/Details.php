<?php

namespace App\Livewire\Frontend\Product;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\AttributeCategory;
use App\Models\Product;
use App\Models\MetaManagement;
use Darryldecode\Cart\Facades\CartFacade as Cart;

#[Layout('components.layouts.frontend')]
class Details extends Component
{

    public $product;
    public $variant;
    public $selectedVariantId;
    public $category;
    public $availableAttributes = [];
    public $selectedAttributeValues = [];
    public $allImages = [];
    public $mainImage = '';
    public $displayProductName;

    // Review Properties
    public $rating;
    public $review_text;
    public $user_has_purchased = false;
    public $user_has_reviewed = false;
    public $meta;

    public function mount($category_slug, $product_slug, $varient_id = 0)
    {
        $this->product = Product::with([
            'product_images',
            'product_categories.categories',
            'product_variants.attributes.attributeValue.attributes',
            'productAttributes.attributeValue.attributes',
            'additional_info',
            'approvedReviews.user'
        ])
        ->where('slug', $product_slug)
        ->firstOrFail();

        $this->category = Category::where('slug', $category_slug)->first();

        // Fetch Meta details for product details
        $this->meta = MetaManagement::where('section', 'product')
            ->where('item_id', $this->product->id)
            ->first();
            
        // Check purchase and review status
        if (\Auth::check()) {
            $this->user_has_purchased = \Auth::user()->hasPurchased($this->product->id);
            $this->user_has_reviewed = \App\Models\ProductReview::where('product_id', $this->product->id)
                ->where('user_id', \Auth::id())
                ->exists();
        }

        if ($this->product->product_type === 'variant') {
            // Load all variant attributes to build the selection grid
            $this->buildAttributeGrid();

            if ($varient_id > 0) {
                $this->variant = $this->product->product_variants->where('price', '>', 0)->find($varient_id);
            }
            
            if (!$this->variant) {
                $this->variant = $this->product->product_variants->where('price', '>', 0)->first();
            }
            
            if ($this->variant) {
                $this->selectedVariantId = $this->variant->id;
                // Initialize selected values from the current variant
                foreach ($this->variant->attributes as $attr) {
                    if ($attr->attributeValue && $attr->attributeValue->attributes) {
                        $this->selectedAttributeValues[$attr->attributeValue->attribute_id] = $attr->attributeValue->id;
                    }
                }
                // Set display product name for variant product
                $this->displayProductName = $this->variant->product_name ?? $this->product->product_name;
            } else {
                // Fallback for variant product if no variant is selected/found
                $this->displayProductName = $this->product->product_name;
            }
        } else {
            // Set display product name for single product
            $this->displayProductName = $this->product->product_name;
        }

        $this->updateImages();
    }

    public function updateImages()
    {
        $images = collect();
        $productImages = $this->product->product_images;

        if ($this->product->product_type === 'variant' && $this->selectedVariantId) {
            // 1. Variant specific images (by product_varient_id)
            $images = $productImages->where('product_varient_id', $this->selectedVariantId);

            // 2. If no variant specific images, check by selected attributes (like Color)
            if ($images->isEmpty()) {
                $selectedAttrValueIds = array_values($this->selectedAttributeValues);
                $images = $productImages->whereIn('attribute_value_id', $selectedAttrValueIds);
            }

            // 3. If still empty, show general images for this product (where variant and attribute are null)
            if ($images->isEmpty()) {
                $images = $productImages->whereNull('product_varient_id')->whereNull('attribute_value_id');
            }
            
            // 4. If still empty, show all product images
            if ($images->isEmpty()) {
                $images = $productImages;
            }
        } else {
            // Single product: Show all tagged images
            $images = $productImages;
        }

        // Final fallback to the main product image if no images in product_images
        if ($images->isEmpty() && $this->product->image) {
            $images = collect([['image_name' => $this->product->image]]);
        }

        // Ensure uniqueness by image_name
        $this->allImages = $images->unique('image_name')->values()->toArray();
        $this->mainImage = count($this->allImages) > 0 ? $this->allImages[0]['image_name'] : '';
    }

    protected function buildAttributeGrid()
    {
        $attributes = [];
        foreach ($this->product->product_variants as $v) {
            // Only show attributes from variants that have a price > 0
            if (!$v->price || $v->price <= 0) {
                continue;
            }

            foreach ($v->attributes as $va) {
                $attr = $va->attributeValue->attributes;
                $val = $va->attributeValue;
                
                if ($attr && $val) {
                    if (!isset($attributes[$attr->id])) {
                        $attributes[$attr->id] = [
                            'id' => $attr->id,
                            'name' => $attr->attribute_name,
                            'values' => []
                        ];
                    }
                    
                    if (!isset($attributes[$attr->id]['values'][$val->id])) {
                        $attributes[$attr->id]['values'][$val->id] = [
                            'id' => $val->id,
                            'name' => $val->value_name,
                            'hexa' => $val->hexa_color_code
                        ];
                    }
                }
            }
        }
        $this->availableAttributes = array_values($attributes);
    }

    public function selectAttributeValue($attributeId, $valueId)
    {
        $this->selectedAttributeValues[$attributeId] = $valueId;
        $this->findMatchingVariant();
        $this->updateImages();
    }

    protected function findMatchingVariant()
    {
        $matched = false;
        foreach ($this->product->product_variants as $v) {
            // Skip variants with price 0 or null
            if (!$v->price || $v->price <= 0) {
                continue;
            }

            $variantAttrMap = $v->attributes->pluck('attribute_value_id', 'attributeValue.attribute_id')->toArray();
            
            $match = true;
            foreach ($this->selectedAttributeValues as $attrId => $valId) {
                if (!isset($variantAttrMap[$attrId]) || $variantAttrMap[$attrId] != $valId) {
                    $match = false;
                    break;
                }
            }
            
            if ($match) {
                $this->variant = $v;
                $this->selectedVariantId = $v->id;
                
                // Update URL without refreshing the page
                $this->js("window.history.replaceState({}, '', '" . route('product.details', [
                    'category_slug' => $this->category->slug ?? 'general',
                    'product_slug' => $this->product->slug,
                    'varient_id' => $v->id
                ]) . "')");

                // Update display product name
                $this->displayProductName = $v->product_name ?? $this->product->product_name;
                
                $matched = true;
                break;
            }
        }
        
        if (!$matched) {
            $this->variant = null;
            $this->selectedVariantId = null;
            $this->displayProductName = $this->product->product_name;
        }
    }

    public function selectVariant($variantId)
    {
        $this->selectedVariantId = $variantId;
    }

    public function isValueValid($attributeId, $valueId)
    {
        // Temporarily simulate selecting this value to see if any valid variant (price > 0) exists
        $tempSelections = $this->selectedAttributeValues;
        $tempSelections[$attributeId] = $valueId;

        foreach ($this->product->product_variants as $v) {
            if (!$v->price || $v->price <= 0) {
                continue;
            }

            $variantAttrMap = $v->attributes->pluck('attribute_value_id', 'attributeValue.attribute_id')->toArray();
            
            $match = true;
            foreach ($tempSelections as $aId => $vId) {
                if (!isset($variantAttrMap[$aId]) || $variantAttrMap[$aId] != $vId) {
                    $match = false;
                    break;
                }
            }

            if ($match) {
                return true;
            }
        }

        return false;
    }

    public function addToCart($redirectToCart = false)
    {
        if ($this->product->product_type === 'variant' && !$this->variant) {
            session()->flash('error', 'Please select a variant first.');
            return;
        }

        $this->dispatch('addToCart', 
            productId: $this->product->id, 
            variantId: $this->variant ? $this->variant->id : null, 
            quantity: 1,
            redirectToCart: $redirectToCart
        );
    }

    public function submitReview()
    {
        if (!\Auth::check()) {
            return redirect()->route('login');
        }

        // Global check for reviews
        if (get_setting('ENABLE_REVIEWS') != '1') {
            session()->flash('review_error', 'Product reviews are currently disabled.');
            return;
        }

        // Check for verified purchase restriction
        if (get_setting('VERIFIED_PURCHASE_ONLY') == '1' && !$this->user_has_purchased) {
            session()->flash('review_error', 'Only verified purchasers can submit reviews for this product.');
            return;
        }

        $this->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review_text' => 'required|string|min:10',
        ]);

        if ($this->user_has_reviewed) {
            session()->flash('review_error', 'You have already reviewed this product.');
            return;
        }

        \App\Models\ProductReview::create([
            'product_id' => $this->product->id,
            'user_id' => \Auth::id(),
            'rating' => $this->rating,
            'review' => $this->review_text,
            'is_approved' => 0, // Pending admin approval
            'verified_purchase' => $this->user_has_purchased,
        ]);

        $this->user_has_reviewed = true;
        $this->rating = null;
        $this->review_text = '';

        session()->flash('review_success', 'Thank you! Your review has been submitted and is awaiting approval.');
    }

    public function render()
    {
        return view('livewire.frontend.product.details', [
            'relatedProducts' => $this->product->get_related_products(['limit' => 4, 'category_id' => $this->category->id ?? null])
        ]);
    }
}
