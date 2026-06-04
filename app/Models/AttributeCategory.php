<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttributeCategory extends Model
{

    public $timestamps = false;

    protected $fillable = [
        'attribute_id',
        'category_id',
        'type',
        'display_type',
        'sort_order'
    ];

    public function attribute()
    {
        return $this->hasOne(Attribute::class, 'id', 'attribute_id');
    }

    public function filterAttributes()
    {
        return $this->hasOne(Attribute::class, 'id', 'attribute_id')->where('is_filter', '1');
    }

    public function taggedAttributeValues()
    {
        return $this->hasMany(AttributeValueCategory::class, 'attribute_id', 'attribute_id');
    }

    public function attributeValueCategories()
    {
        return $this->hasMany(AttributeValueCategory::class, 'attribute_category_id', 'id');
    }

}
