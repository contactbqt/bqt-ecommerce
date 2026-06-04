<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductAttribute extends Model
{
    public $timestamps = false;

    protected $fillable = ['product_id',
                            'attribute_id',
                            'attribute_value_id'
                        ];
    
    public function attribute()
    {
        return $this->hasOne(Attribute::class, 'id', 'attribute_id');
    }

    public function attributeValue()
    {
        return $this->hasOne(AttributeValue::class, 'id', 'attribute_value_id');
    }

    public function attributeCategory()
    {
        return $this->hasOne(AttributeCategory::class, 'id', 'attribute_id');
    }

}
