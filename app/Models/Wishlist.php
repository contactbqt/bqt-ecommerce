<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    use HasFactory;

    protected $table = 'wishlists';
    protected $primaryKey = 'id';

    protected $fillable = [
                            'product_id',
                            'user_id',
                            'product_variant_id',
                            'added_date',
                            ];



    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function product_variant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id', 'id');
    }

    public function user_data(){
        return $this->belongto(User::class, 'user_id', 'id');
    }
}
