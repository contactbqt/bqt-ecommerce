<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    public $timestamps = false;
    protected $fillable = ['product_id', 'category_id'];

    public function products()
    {
        return $this->belongsTo(Product::Class);
    }
    
    public function categories(){
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }
    
}
