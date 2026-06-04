<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_number',
        'order_date',
        'shipping_amount',
        'tax_amount',
        'order_amount',
        'billing_name',
        'billing_address1',
        'billing_address2',
        'billing_city',
        'billing_state',
        'billing_pincode',
        'billing_country',
        'shipping_name',
        'shipping_address1',
        'shipping_address2',
        'shipping_city',
        'shipping_state',
        'shipping_pincode',
        'shipping_country',
        'shipping_phone',
        'status',
        'payment_mode',
        'txn_details',
        'delivery_date',
    ];

    public function order_details()
    {
        return $this->hasMany(OrderDetail::class, 'order_id');
    }
    public function invoice_details()
    {
        return $this->hasOne(Invoice::class, 'order_id');
    }
    public function user_details()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
