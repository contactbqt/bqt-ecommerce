<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShoppingCart extends Model
{
    use HasFactory;
    protected $table = 'carts';
    protected $primaryKey = 'id';

    protected $fillable = [
                            'user_id', 
                            'product_varient_id', 
                            'varient_details', 
                            'price',
                            'discounted_price',
                            'quantity',
                            'line_total'
                        ];


    public function product_varients()
    {
        return $this->hasOne(ProductVarient::class, 'id', 'product_varient_id');
    }

}
