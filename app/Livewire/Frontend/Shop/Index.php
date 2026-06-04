<?php

namespace App\Livewire\Frontend\Shop;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Wishlist;
use App\Models\AttributeCategory;
use App\Models\Product;
use App\Models\MetaManagement;
use Illuminate\Support\Facades\Auth;

#[Layout('components.layouts.frontend')]
class Index extends Component
{
    use \Livewire\WithPagination;

    #[Url]
    public $search = '';

    #[Url]
    public $sort = '';

    public $category = '';

    public $category_name = '';

    #[Url(as: 'filter')]
    public $selectedAttributes = [];

    #[Url]
    public $minPrice = '';

    #[Url]
    public $maxPrice = '';

    public $minPriceRange = 0;
    public $maxPriceRange = 100000;

    public $wishlistProductIds = [];
    public $meta;

    public function mount($category = null)
    {
        if ($category) {
            $this->category = $category;
        }
        $this->cleanSelectedAttributes();
        $this->loadWishlistIds();
    }

    public function loadWishlistIds()
    {
        if (Auth::check()) {
            $this->wishlistProductIds = Wishlist::where('user_id', Auth::id())
                ->get()
                ->map(function($item) {
                    return ($item->product_variant_id ? 'v_' . $item->product_variant_id : 'p_' . $item->product_id);
                })
                ->toArray();
        } else {
            $this->wishlistProductIds = [];
        }
    }

    public function updatedSelectedAttributes()
    {
        $this->cleanSelectedAttributes();
        $this->resetPage();
    }

    protected function cleanSelectedAttributes()
    {
        if (is_array($this->selectedAttributes)) {
            foreach ($this->selectedAttributes as $attrSlug => $values) {
                if (is_array($values)) {
                    foreach ($values as $key => $val) {
                        if ($val === 'false' || $val === false || $val === '') {
                            unset($this->selectedAttributes[$attrSlug][$key]);
                        } elseif ($val === 'true') {
                            $this->selectedAttributes[$attrSlug][$key] = true;
                        }
                    }
                    if (empty($this->selectedAttributes[$attrSlug])) {
                        unset($this->selectedAttributes[$attrSlug]);
                    }
                }
            }
        }
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedSort()
    {
        $this->resetPage();
    }

    public function updatedMinPrice()
    {
        $this->resetPage();
    }

    public function updatedMaxPrice()
    {
        $this->resetPage();
    }

    public function clearAllFilters()
    {
        $this->selectedAttributes = [];
        $this->minPrice = '';
        $this->maxPrice = '';
        $this->sort = '';
        $this->resetPage();
        $this->dispatch('resetPriceSlider');
    }

    public function addToWishlist($productId, $variantId = null)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $userId = Auth::id();

        $exists = Wishlist::where('user_id', $userId)
            ->where('product_id', $productId)
            ->where('product_variant_id', $variantId)
            ->exists();

        if ($exists) {
            // Remove from wishlist
            Wishlist::where('user_id', $userId)
                ->where('product_id', $productId)
                ->where('product_variant_id', $variantId)
                ->delete();
            
            $this->loadWishlistIds();
            session()->flash('info', 'Product removed from your wishlist.');
            $this->dispatch('wishlistUpdated');
            return;
        }

        Wishlist::create([
            'user_id' => $userId,
            'product_id' => $productId,
            'product_variant_id' => $variantId,
            'added_date' => now(),
        ]);

        $this->loadWishlistIds();
        session()->flash('success', 'Product added to wishlist!');
        $this->dispatch('wishlistUpdated');
    }

    public function render()
    {
        $cat_id = null;
        $categoryIds = [];
        $firstLevelCategories = collect();
        $filterAttributes = collect();

        if ($this->category) {
            $category = Category::where('slug', $this->category)->first();
            if ($category) {
                $cat_id = $category->id;
                $categoryIds = $category->getAllDescendantIds();
                $this->category_name = $category->category_name;
                // Fetch Meta details for category
                $this->meta = MetaManagement::where('section', 'category')
                    ->where('item_id', $cat_id)
                    ->first();

                //get first level children categories
                $firstLevelCategories = Category::where('parent_id', $cat_id)->get();
                //get all the filter attributes 
                $filterAttributes = AttributeCategory::with('attribute','attributeValueCategories.attributeValue')
                ->where('type', 'filter')
                ->whereHas('attribute', function ($query) {
                    $query->where('is_filter', 1);
                })
                ->where('category_id', $cat_id)->get();
            }
        } else {
            // Fetch Meta details for product listing page
            $this->meta = MetaManagement::where('section', 'product_listing')
                ->first();
                
            // If no category selected, maybe show all top level categories
            $firstLevelCategories = Category::whereNull('parent_id')->orWhere('parent_id', 0)->get();
        }

        // --- DYNAMIC PRICE RANGE CALCULATION ---
        // Base query for current view (filtered by category and search, but NOT price)
        $rangeQuery = Product::where('status', '1');
        if (!empty($categoryIds)) {
            $rangeQuery->whereHas('product_categories', function ($q) use ($categoryIds) {
                $q->whereIn('category_id', $categoryIds);
            });
        }
        if (!empty($this->search)) {
            $rangeQuery->where(function ($q) {
                $q->where('product_name', 'like', '%' . $this->search . '%')
                    ->orWhereHas('tags', function ($tq) {
                        $tq->where('name', 'like', '%' . $this->search . '%');
                    });
            });
        }

        // Get min/max for single products
        $singleStats = (clone $rangeQuery)->where('product_type', 'single')
            ->selectRaw('MIN(CASE WHEN offer_price > 0 THEN offer_price ELSE price END) as min_p, MAX(CASE WHEN offer_price > 0 THEN offer_price ELSE price END) as max_p')
            ->first();

        // Get min/max for variant products
        $variantStats = \App\Models\ProductVariant::whereHas('product', function($q) use ($categoryIds) {
            $q->where('status', '1');
            if (!empty($categoryIds)) {
                $q->whereHas('product_categories', function($pq) use ($categoryIds) {
                    $pq->whereIn('category_id', $categoryIds);
                });
            }
            if (!empty($this->search)) {
                $q->where(function ($sq) {
                    $sq->where('product_name', 'like', '%' . $this->search . '%')
                        ->orWhereHas('tags', function ($tq) {
                            $tq->where('name', 'like', '%' . $this->search . '%');
                        });
                });
            }
        })
        ->selectRaw('MIN(CASE WHEN offer_price > 0 THEN offer_price ELSE price END) as min_p, MAX(CASE WHEN offer_price > 0 THEN offer_price ELSE price END) as max_p')
        ->first();

        $potentialMin = min($singleStats->min_p ?? 1000000, $variantStats->min_p ?? 1000000);
        $potentialMax = max($singleStats->max_p ?? 0, $variantStats->max_p ?? 0);

        if ($potentialMin === 1000000) $potentialMin = 0;
        
        $this->minPriceRange = floor($potentialMin);
        $this->maxPriceRange = ceil($potentialMax);

        // Ensure reasonable range if min == max
        if ($this->minPriceRange >= $this->maxPriceRange) {
            $this->maxPriceRange = $this->minPriceRange + 100;
        }

        // Dispatch update event to frontend slider
        $this->dispatch('updatePriceRange', [
            'min' => $this->minPriceRange,
            'max' => $this->maxPriceRange,
            'currentMin' => $this->minPrice != '' ? (int)$this->minPrice : $this->minPriceRange,
            'currentMax' => $this->maxPrice != '' ? (int)$this->maxPrice : $this->maxPriceRange,
        ]);
        // --- END DYNAMIC PRICE RANGE CALCULATION ---

        //get product list that belongs to the category
        $query = Product::with(['product_images', 'product_categories.categories', 'product_variants.attributes.attributeValue.attributes', 'productAttributes.attributeValue.attributes'])
            ->where('status', '1')
            ->where(function ($q) {
                $q->where(function ($qSingle) {
                    $qSingle->where('product_type', 'single')
                            ->where('price', '>', 0);
                })->orWhere(function ($qVariant) {
                    $qVariant->where('product_type', 'variant')
                             ->whereHas('product_variants', function ($qv) {
                                 $qv->where('price', '>', 0);
                             });
                });
            });

        if (!empty($categoryIds)) {
            $query->whereHas('product_categories', function ($q) use ($categoryIds) {
                $q->whereIn('category_id', $categoryIds);
            });
        }

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('product_name', 'like', '%' . $this->search . '%')
                    ->orWhereHas('tags', function ($tq) {
                        $tq->where('name', 'like', '%' . $this->search . '%');
                    });
            });
        }

        if ($this->minPrice !== '') {
            $query->where(function ($q) {
                $q->where(function ($qSingle) {
                    $qSingle->where('product_type', 'single')
                        ->where(function ($qs) {
                            $qs->where(function ($qso) {
                                $qso->where('offer_price', '>', 0)->where('offer_price', '>=', $this->minPrice);
                            })->orWhere(function ($qsp) {
                                $qsp->where(function ($inner) {
                                    $inner->where('offer_price', '<=', 0)->orWhereNull('offer_price');
                                })->where('price', '>=', $this->minPrice);
                            });
                        });
                })->orWhere(function ($qVariant) {
                    $qVariant->where('product_type', 'variant')
                        ->whereHas('product_variants', function ($qv) {
                            $qv->where(function ($qvo) {
                                $qvo->where('offer_price', '>', 0)->where('offer_price', '>=', $this->minPrice);
                            })->orWhere(function ($qvp) {
                                $qvp->where(function ($inner) {
                                    $inner->where('offer_price', '<=', 0)->orWhereNull('offer_price');
                                })->where('price', '>=', $this->minPrice);
                            });
                        });
                });
            });
        }

        if ($this->maxPrice !== '') {
            $query->where(function ($q) {
                $q->where(function ($qSingle) {
                    $qSingle->where('product_type', 'single')
                        ->where(function ($qs) {
                            $qs->where(function ($qso) {
                                $qso->where('offer_price', '>', 0)->where('offer_price', '<=', $this->maxPrice);
                            })->orWhere(function ($qsp) {
                                $qsp->where(function ($inner) {
                                    $inner->where('offer_price', '<=', 0)->orWhereNull('offer_price');
                                })->where('price', '<=', $this->maxPrice);
                            });
                        });
                })->orWhere(function ($qVariant) {
                    $qVariant->where('product_type', 'variant')
                        ->whereHas('product_variants', function ($qv) {
                            $qv->where(function ($qvo) {
                                $qvo->where('offer_price', '>', 0)->where('offer_price', '<=', $this->maxPrice);
                            })->orWhere(function ($qvp) {
                                $qvp->where(function ($inner) {
                                    $inner->where('offer_price', '<=', 0)->orWhereNull('offer_price');
                                })->where('price', '<=', $this->maxPrice);
                            });
                        });
                });
            });
        }

        $selectedGroups = [];
        if (!empty($this->selectedAttributes)) {
            foreach ($this->selectedAttributes as $attrSlug => $valueSlugs) {
                if (is_array($valueSlugs)) {
                    $normalizedSlugs = [];
                    foreach ($valueSlugs as $key => $val) {
                        if (is_bool($val) || $val === 'true' || $val === 'false') {
                            if (filter_var($val, FILTER_VALIDATE_BOOLEAN)) {
                                $normalizedSlugs[] = $key;
                            }
                        } else {
                            $normalizedSlugs[] = $val;
                        }
                    }
                    $normalizedSlugs = array_filter($normalizedSlugs);
                    if (!empty($normalizedSlugs)) {
                        $selectedGroups[$attrSlug] = $normalizedSlugs;
                    }
                } else if ($valueSlugs) {
                    $selectedGroups[$attrSlug] = [$valueSlugs];
                }
            }
        }

        if (!empty($selectedGroups)) {
            $query->where(function($q) use ($selectedGroups) {
                // Condition for variant products: at least one variant must match ALL selected attributes
                $q->where(function($variantQuery) use ($selectedGroups) {
                    $variantQuery->where('product_type', 'variant');
                    foreach ($selectedGroups as $attrSlug => $slugs) {
                        $variantQuery->where(function($subQ) use ($attrSlug, $slugs) {
                            $subQ->whereHas('productAttributes.attributeValue', function($aq) use ($attrSlug, $slugs) {
                                $aq->whereIn('slug', $slugs)
                                   ->whereHas('attributes', function($attrQ) use ($attrSlug) {
                                       $attrQ->where('slug', $attrSlug);
                                   });
                            })->orWhereHas('product_variants.attributes.attributeValue', function($aq) use ($attrSlug, $slugs) {
                                $aq->whereIn('slug', $slugs)
                                   ->whereHas('attributes', function($attrQ) use ($attrSlug) {
                                       $attrQ->where('slug', $attrSlug);
                                   });
                            });
                        });
                    }
                });

                // Condition for single products: product must have ALL selected attributes
                $q->orWhere(function($singleQuery) use ($selectedGroups) {
                    $singleQuery->where('product_type', 'single');
                    foreach ($selectedGroups as $attrSlug => $slugs) {
                        $singleQuery->whereHas('productAttributes.attributeValue', function($aq) use ($attrSlug, $slugs) {
                            $aq->whereIn('slug', $slugs)
                               ->whereHas('attributes', function($attrQ) use ($attrSlug) {
                                   $attrQ->where('slug', $attrSlug);
                               });
                        });
                    }
                });
            });
        }

        // Apply Sorting
        switch ($this->sort) {
            case 'price_low_to_high':
                // Complex sort considering variants and offers
                $query->orderByRaw('
                    LEAST(
                        COALESCE((SELECT MIN(CASE WHEN offer_price > 0 THEN offer_price ELSE price END) FROM product_variants WHERE product_id = products.id AND price > 0), 1000000),
                        CASE WHEN product_type = "single" THEN (CASE WHEN offer_price > 0 THEN offer_price ELSE price END) ELSE 1000000 END
                    ) ASC
                ');
                break;
            case 'price_high_to_low':
                $query->orderByRaw('
                    GREATEST(
                        COALESCE((SELECT MAX(CASE WHEN offer_price > 0 THEN offer_price ELSE price END) FROM product_variants WHERE product_id = products.id AND price > 0), 0),
                        CASE WHEN product_type = "single" THEN (CASE WHEN offer_price > 0 THEN offer_price ELSE price END) ELSE 0 END
                    ) DESC
                ');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'name_a_z':
                $query->orderBy('product_name', 'asc');
                break;
            case 'name_z_a':
                $query->orderBy('product_name', 'desc');
                break;
            default:
                $query->orderBy('id', 'desc');
                break;
        }

        $products = $query->paginate(12);

        return view('livewire.frontend.shop.index', compact('firstLevelCategories', 'filterAttributes', 'products', 'selectedGroups'));
    }
}
