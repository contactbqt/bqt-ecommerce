<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductVarientAttribute extends Model
{
    use SoftDeletes;

    protected $fillable = ['product_variant_id',
                    'attribute_value_id'
                ];


}
