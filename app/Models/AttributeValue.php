<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class AttributeValue extends Model
{
    use SoftDeletes;

    protected $fillable = ['attribute_id',
                            'value_name',
                            'slug',
                            'sort_order',
                            'hexa_color_code',
                            'status'
                        ];

    
    public function attributes()
    {
        return $this->belongsTo(Attribute::Class, 'attribute_id');
    }

    public function productImages()
    {
        return $this->hasMany(ProductImage::class, 'attribute_value_id');
    }
    
}
