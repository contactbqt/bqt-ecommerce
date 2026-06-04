<?php

namespace App\Livewire\Admin\Product;

use Livewire\Component;
use App\Models\Product;
use App\Models\ProductVariant;
use Flux\Flux;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

#[Layout('components.layouts.admin-app')]
class ProductVariantEdit extends Component
{
    use WithPagination;

    public $productId;
    public $product = [];
    public $variantInputs = []; 

    public function mount($id)
    {
        $this->productId = $id;
        $this->product = Product::findOrFail($id);
        
        if ($this->product->product_type != 'variant') {
             return redirect()->route('admin.product.index')->with('error_message', 'This product is not a variant type.');
        }
        
        $this->loadVariants();
    }

    public function loadVariants()
    {
        $variants = ProductVariant::where('product_id', $this->productId)->with('attributes.attributeValue')->get();
        
        $this->variantInputs = [];
        foreach($variants as $variant) {
            $this->variantInputs[$variant->id] = [
                'id' => $variant->id,
                'name' => $variant->variant_name,
                'product_name' => $variant->product_name,
                'price' => $variant->price,
                'offer_price' => $variant->offer_price,
                'sku_code' => $variant->sku_code,
                'stock_qty' => $variant->stock_qty,
                'status' => $variant->status,
                'attributes_desc' => $variant->variant_name 
            ];
        }
    }

    protected function rules()
    {
        return [
            'variantInputs.*.product_name' => 'nullable|string|max:255',
            'variantInputs.*.price' => 'required|numeric|min:0',
            'variantInputs.*.offer_price' => 'nullable|numeric|min:0',
            'variantInputs.*.sku_code' => 'nullable|string|max:255',
            'variantInputs.*.stock_qty' => 'required|integer|min:0',
            'variantInputs.*.status' => 'required|boolean',
        ];
    }
    
    protected $messages = [
        'variantInputs.*.price.required' => 'Price is required',
        'variantInputs.*.stock_qty.required' => 'Stock is required',
    ];

    public function updateVariants()
    {
        try {
            $this->validate();
        } catch (ValidationException $e) {
            Flux::toast('Validation failed. Please check the inputs.', variant: 'danger');
            throw $e;
        }

        DB::transaction(function () {
            foreach ($this->variantInputs as $variantId => $data) {
                ProductVariant::where('id', $variantId)->update([
                    'product_name' => $data['product_name'] ?: null,
                    'price' => $data['price'],
                    'offer_price' => $data['offer_price'] ?: null,
                    'sku_code' => $data['sku_code'],
                    'stock_qty' => $data['stock_qty'],
                    'status' => $data['status'],
                ]);
            }
        });

        Flux::toast('Variants updated successfully.', variant: 'success');
    }
    
    public function deleteVariant($variantId)
    {
        // Add delete logic if needed
        ProductVariant::findOrFail($variantId)->delete();
        $this->loadVariants();
        Flux::toast('Variant deleted.');
    }

    public function render()
    {
        return view('livewire.admin.product.product-variant-edit');
    }
}
