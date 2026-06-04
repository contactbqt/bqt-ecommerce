<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductVariantAttribute extends Model
{
    use SoftDeletes;

    protected $fillable = ['product_variant_id',
                            'attribute_value_id'
                        ];

    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }

    public function attributeValue()
    {
        return $this->belongsTo(AttributeValue::class, 'attribute_value_id');
    }

    public function attributes()
    {
        return $this->hasOneThrough(
            Attribute::class,
            AttributeValue::class,
            'id', // Local key on AttributeValue
            'id', // Local key on Attribute
            'attribute_value_id', // Foreign key on ProductVariantAttribute
            'attribute_id' // Foreign key on AttributeValue
        );
    }

}
