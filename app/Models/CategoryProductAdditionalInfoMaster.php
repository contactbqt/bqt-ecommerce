<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryProductAdditionalInfoMaster extends Model
{
    protected $table = 'category_product_additional_info_master';
    protected $fillable = ['category_id', 'title', 'additional_info'];
    protected $casts = [
        'additional_info' => 'array',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
