<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductAdditionalInfo extends Model
{
    protected $table = 'product_additional_info';
    protected $fillable = ['product_id', 'title', 'additional_info'];
    protected $casts = [
        'additional_info' => 'array',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
