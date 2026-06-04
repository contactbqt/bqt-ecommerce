<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttributeValueCategory extends Model
{
    protected $table = 'attribute_value_categories';

    protected $fillable = [
        'attribute_id',
        'attribute_category_id',
        'attribute_value_name_category',
    ];

    // public function attributeCategory()
    // {
    //     return $this->belongsTo(AttributeCategory::class, 'attribute_category_id');
    // }

    public function attributeValue()
    {
        return $this->hasOne(AttributeValue::class, 'id', 'attribute_value_name_category');
    }

}
