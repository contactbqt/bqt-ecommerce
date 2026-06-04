<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;


class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'product_type',
        'product_name',
        'family_id',
        'sku_code',
        'slug',
        'description',
        'image',
        'price',
        'offer_price',
        'stock_qty',
        'discount_type',
        'discount_value',
        'discounted_price',
        'is_featured',
        'status',
        'average_rating',
        'total_reviews'
    ];

    public function reviews()
    {
        return $this->hasMany(ProductReview::class);
    }

    public function approvedReviews()
    {
        $query = $this->hasMany(ProductReview::class)->where('is_approved', 1);
        
        if (get_setting('VERIFIED_PURCHASE_ONLY') == '1') {
            $query->where('verified_purchase', 1);
        }
        
        return $query;
    }

    /**
     * Recalculate average rating and total reviews
     */
    public function syncRatings()
    {
        $approvedReviews = $this->approvedReviews();
        $this->total_reviews = $approvedReviews->count();
        $this->average_rating = $approvedReviews->avg('rating') ?: 0;
        $this->save();
    }

    public function product_family()
    {
        return $this->belongsTo(ProductFamily::class, 'family_id');
    }


    public function productAttributes()
    {
        return $this->hasMany(ProductAttribute::class, 'product_id', 'id');
    }

    public function product_categories()
    {
        return $this->hasMany(ProductCategory::class, 'product_id', 'id');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'product_categories', 'product_id', 'category_id');
    }


    public function product_images()
    {
        return $this->hasMany(ProductImage::class, 'product_id', 'id');
    }

    public function additional_info()
    {
        return $this->hasMany(ProductAdditionalInfo::class, 'product_id', 'id');
    }

    public function product_variants()
    {
        return $this->hasMany(ProductVariant::class, 'product_id', 'id');
    }

    public function product_varients()
    {
        // alias for typo in existing code
        return $this->hasMany(ProductVariant::class, 'product_id', 'id');
    }



    public function get_related_products($params = [])
    {
        //Search with categories
        $category_id = (is_array($params) && array_key_exists('category_id', $params) && !empty($params['category_id'])) ? $params['category_id'] : "";

        $limit = (is_array($params) && array_key_exists('limit', $params) && !empty($params['limit'])) ? $params['limit'] : "";

        $products = Product::with([
            'product_varients' => function ($query) {
                $query
                    ->where('product_variants.status', 1)
                    ->whereHas('attributesWithValues', function ($q) {
                        $q->whereHas('attributes', function ($subQuery) {
                            $subQuery->where('attributes.id', '1');
                        });
                    })
                    ->addSelect(
                        DB::raw('MIN(product_variants.id) AS id'),
                        DB::raw('MIN(product_variants.product_id) AS product_id'),
                        DB::raw('MIN(product_variants.variant_name) AS variant_name'),
                        DB::raw('MIN(product_variants.sku_code) AS sku_code'),
                        DB::raw('MIN(product_variants.price) AS price'),
                        DB::raw('MIN(product_variants.offer_price) AS offer_price'),
                        DB::raw('MIN(product_variants.stock_qty) AS stock_qty'),
                        DB::raw('MIN(product_variants.status) AS status'),
                        DB::raw('MIN(product_variant_attributes.product_variant_id) AS product_variant_id'),
                        DB::raw('MIN(product_variant_attributes.attribute_value_id) AS attribute_value_id'),
                        DB::raw('MIN(attribute_values.attribute_id) AS attribute_id'),
                        DB::raw('COUNT(*) AS total_varients')

                    )
                    ->addSelect(DB::raw('MIN(attribute_values.id) as color_id')) // Fetch the lowest color ID for grouping
                    ->join('product_variant_attributes', 'product_variant_attributes.product_variant_id', '=', 'product_variants.id')

                    ->join('attribute_values', function ($join) {
                        $join->on('product_variant_attributes.attribute_value_id', '=', 'attribute_values.id')
                            ->where('attribute_values.attribute_id', 1); // Only fetch Color attributes
                    })

                    ->join('attributes', 'attribute_values.attribute_id', '=', 'attributes.id')
                    ->groupBy(
                        'product_variants.product_id',
                        'product_variant_attributes.attribute_value_id'
                    )
                    ->with(['attributesWithValues.attributes', 'productImages.attributeValue']);
            },
            'categories',
            'product_varients.attributesWithValues'
        ])
            ->where('products.status', 1)
            ->when($category_id, function ($query) use ($category_id) {

                $query->whereHas('categories', function ($query) use ($category_id) {
                    $query->where('product_categories.category_id', $category_id);
                });

            })
            ->orderBy('products.id', 'DESC')
            ->when($limit, function ($q) use ($limit) {
                $q->take($limit);
            })
            ->get()
            ->shuffle();

        return $products;

    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'product_tags');
    }




}
