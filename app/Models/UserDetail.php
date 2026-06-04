<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class UserDetail extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'contact_number',
        'gender',
        'address',
        'city',
        'state',
        'country',
        'pincode',
        'profile_image',
        'degree',
        'year_of_experience',
        'self_description',
        'area_of_expertise',
        'special_interests',
        'is_featured',
        'sort_order'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
