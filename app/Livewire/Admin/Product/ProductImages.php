<?php

namespace App\Livewire\Admin\Product;

use Livewire\Component;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductImage;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Storage;
use Flux\Flux;

#[Layout('components.layouts.admin-app')]
class ProductImages extends Component
{
    use WithFileUploads;

    public $productId;
    public $product;
    public $product_type;
    
    // For variant products
    public $variants = [];
    public $selectedVariants = [];
    
    // Uploaded photos
    public $photos = [];

    // Grouped existing images
    public $existingImages = [];

    public function mount($id)
    {
        $this->productId = $id;
        $this->product = Product::findOrFail($id);
        $this->product_type = $this->product->product_type;
        
        if ($this->product_type === 'variant') {
            $this->variants = ProductVariant::where('product_id', $this->productId)->get();
        }
        
        $this->loadImages();
    }

    public function toggleSelectAllVariants()
    {
        if (count($this->selectedVariants) === count($this->variants)) {
            $this->selectedVariants = [];
        } else {
            $this->selectedVariants = $this->variants->pluck('id')->toArray();
        }
    }

    public function loadImages()
    {
        // Fetch existing images for this product, group by image_name
        $images = ProductImage::where('product_id', $this->productId)->get();
        
        $grouped = [];
        foreach ($images as $img) {
            if (!isset($grouped[$img->image_name])) {
                $grouped[$img->image_name] = [
                    'image_name' => $img->image_name,
                    'variants' => []
                ];
            }
            
            if ($img->product_varient_id) {
                // Find variant name
                $variant = $this->variants->firstWhere('id', $img->product_varient_id);
                if ($variant) {
                    $grouped[$img->image_name]['variants'][] = $variant->variant_name;
                }
            }
        }
        
        $this->existingImages = array_values($grouped);
    }

    public function saveImages()
    {
        $this->validate([
            'photos.*' => 'image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048', // 2MB Max
        ], [
            'photos.*.image' => 'The uploaded file must be an image.',
            'photos.*.max' => 'The image must not be larger than 2MB.'
        ]);

        if (empty($this->photos)) {
            Flux::toast('Please select at least one image.', variant: 'warning');
            return;
        }

        if ($this->product_type === 'variant' && empty($this->selectedVariants)) {
            Flux::toast('Please select at least one variant for the images.', variant: 'warning');
            return;
        }

        foreach ($this->photos as $photo) {
            $path = $photo->store('productImages', 'public');
            
            if ($this->product_type === 'variant') {
                foreach ($this->selectedVariants as $variantId) {
                    ProductImage::create([
                        'product_id' => $this->productId,
                        'product_varient_id' => $variantId,
                        'image_name' => $path,
                        'sort_order' => 0,
                    ]);
                }
            } else {
                // Single product
                ProductImage::create([
                    'product_id' => $this->productId,
                    'image_name' => $path,
                    'sort_order' => 0,
                ]);
            }
        }

        // Reset inputs
        $this->photos = [];
        $this->selectedVariants = [];
        $this->loadImages();
        
        Flux::toast('Images uploaded successfully.', variant: 'success');
    }

    public function deleteImage($imageName)
    {
        // Ensure image belongs to this product before deleting
        $exists = ProductImage::where('product_id', $this->productId)
                    ->where('image_name', $imageName)->exists();
        
        if ($exists) {
            // Delete from storage
            if (Storage::disk('public')->exists($imageName)) {
                Storage::disk('public')->delete($imageName);
            }
            
            // Delete from DB (all rows mapped to this image for this product)
            ProductImage::where('product_id', $this->productId)
                        ->where('image_name', $imageName)
                        ->delete();
                        
            $this->loadImages();
            Flux::toast('Image deleted successfully.', variant: 'success');
        } else {
             Flux::toast('Image not found.', variant: 'danger');
        }
    }

    public function render()
    {
        return view('livewire.admin.product.product-images');
    }
}
