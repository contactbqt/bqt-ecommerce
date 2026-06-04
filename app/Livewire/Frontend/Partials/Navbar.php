<?php

namespace App\Livewire\Frontend\Partials;

use Livewire\Component;
use App\Models\Category;

class Navbar extends Component
{
    public $categories = [];

    public function mount()
    {
        // Recursively load mega menu categories (up to 4 levels for safety)
        $this->categories = Category::where('status', '1')
            ->where('parent_id', 0)
            ->with(['children' => function($q) {
                $q->where('status', '1')
                  ->orderBy('sort_order', 'asc')
                  ->with(['children' => function($sq) {
                      $sq->where('status', '1')
                        ->orderBy('sort_order', 'asc')
                        ->with(['children' => function($ssq) {
                            $ssq->where('status', '1')->orderBy('sort_order', 'asc');
                        }]);
                  }]);
            }])
            ->orderBy('sort_order', 'asc')
            ->get();
    }

    public function render()
    {
        return view('livewire.frontend.partials.navbar');
    }
}
