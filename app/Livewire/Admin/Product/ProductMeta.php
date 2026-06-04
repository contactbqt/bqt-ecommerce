<?php

namespace App\Livewire\Admin\Product;

use Livewire\Component;
use App\Models\Product;
use Flux\Flux;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.admin-app')]
class ProductMeta extends Component
{
    public $productId;
    public $product;
    public $meta_title;
    public $meta_description;
    public $meta_keywords;

    public function mount($id)
    {
        $this->productId = $id;
        $this->product = Product::findOrFail($id);
        
        $meta = \App\Models\MetaManagement::where('section', 'product')
            ->where('item_id', $this->productId)
            ->first();
            
        if ($meta) {
            $this->meta_title = $meta->meta_title;
            $this->meta_description = $meta->meta_description;
            $this->meta_keywords = $meta->meta_keywords;
        }
    }

    public function rules()
    {
        return [
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
        ];
    }

    public function saveMeta()
    {
        $this->validate();

        \App\Models\MetaManagement::updateOrCreate(
            [
                'section' => 'product',
                'item_id' => $this->productId
            ],
            [
                'meta_title' => $this->meta_title,
                'meta_description' => $this->meta_description,
                'meta_keywords' => $this->meta_keywords,
            ]
        );

        Flux::toast('Meta information updated successfully.');
    }

    public function render()
    {
        return view('livewire.admin.product.product-meta');
    }
}
