<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddressBook extends Model
{
    use HasFactory;
    protected $table = 'address_books';
    protected $primaryKey = 'id';

    protected $fillable = [
            'user_id',
            'title',
            'address1',
            'address2',
            'country',
            'city',
            'state',
            'pincode',
            'is_default'
    ];


}
