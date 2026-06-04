<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductVariant extends Model
{
    use SoftDeletes;

    protected $fillable = ['product_id',
                            'product_name',
                            'variant_name',
                            'sku_code',
                            'price',
                            'offer_price',
                            'stock_qty',
                            'status'
                        ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    } 
    
    public function productImages()
    {
        return $this->hasMany(ProductImage::class, 'product_varient_id');
    }

    public function ShoppingCart()
    {
        return $this->belongsTo(ShoppingCart::class, 'product_variant_id', 'id');
    }

    public function attributes()
    {
        return $this->hasMany(ProductVariantAttribute::class, 'product_variant_id');
    }

    public function attributesWithValues()
    {
        return $this->hasMany(ProductVariantAttribute::class, 'product_variant_id');
    }

}
