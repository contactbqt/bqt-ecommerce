<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MetaManagement extends Model
{
    use HasFactory;
    protected $table = 'meta_management';
    protected $primaryKey = 'id';

    protected $fillable = ['section', 
                            'item_id', 
                            'meta_title', 
                            'meta_keywords',
                            'meta_description'
                            ];
}
