<?php

namespace App\Livewire\Admin\Product;

use Livewire\Component;
use App\Models\Product;
use Flux\Flux;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;

#[Layout('components.layouts.admin-app')]
class ProductIndex extends Component
{
    use WithPagination;

    public $productId;
    public $searchName = '';
    public $product = '';
    public $status = '';

    public function render()
    {
        $data = array();
        $data['productList'] = Product::with('product_categories.categories')->when($this->searchName, function ($query) {
                $query->where(function ($q) {
                    $q->where('product_name', 'like', '%' . $this->searchName . '%')
                        ->orWhereHas('tags', function ($tq) {
                            $tq->where('name', 'like', '%' . $this->searchName . '%');
                        });
                });
            })
            ->orderBy('id', 'desc')
            ->paginate(config('constants.pagination_limit'));
        //dd($data['productList']);
                
        return view('livewire.admin.product.product-index', $data);
    }

    public function deleteConfirmModal($id)
    {
        $this->productId = $id;
        Flux::modal('delete-product')->show();
    }

    public function search()
    {
        $this->resetPage();
    }

    public function resetSearch()
    {
        $this->searchName = '';
        $this->resetPage();
    }

    public function delete()
    {
        $product = Product::findOrFail($this->productId);
        $familyId = $product->family_id;
        
        $product->delete();

        // If the product belonged to a family, check if that family is now empty
        if ($familyId) {
            $otherProductsInFamily = Product::where('family_id', $familyId)->exists();
            if (!$otherProductsInFamily) {
                \App\Models\ProductFamily::where('id', $familyId)->delete();
            }
        }

        // Redirect to the index page
        return redirect(request()->header('Referer'))->with('message', 'Product deleted successfully.');
    }




}
