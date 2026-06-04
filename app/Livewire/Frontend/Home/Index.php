<?php

namespace App\Livewire\Frontend\Home;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Product;
use App\Models\Category;


#[Layout('components.layouts.frontend')]
class Index extends Component
{
    public $featuredProducts = [];
    public $featuredCategories = [];

    public function mount()
    {
        $this->featuredProducts = Product::with(['product_images', 'product_categories.categories', 'product_variants'])
            ->where('status', '1')
            ->orderBy('id', 'desc')
            ->take(8)
            ->get();
            
        $this->featuredCategories = Category::where('status', '1')
            ->where('is_featured', 1)
            ->orderBy('id', 'desc')
            ->take(8)
            ->get();

        if ($this->featuredCategories->isEmpty()) {
            $this->featuredCategories = Category::where('is_featured', 1)
                ->orderBy('id', 'desc')
                ->take(14)
                ->get();
        }
    }

    public function render()
    {
        return view('livewire.frontend.home.index');
    }
}
