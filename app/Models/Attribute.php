<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attribute extends Model
{
    use SoftDeletes;

    protected $fillable = [
                            'attribute_name',
                            'slug',
                            'is_variant',
                            'is_filter',
                            'display_type',
                            'status'
                        ];

    public function attribute_values()
    {
        return $this->hasMany(AttributeValue::class, 'attribute_id');
    }

    public function productAttributes()
    {
        return $this->hasMany(ProductAttribute::class, 'attribute_id');
    }

}
