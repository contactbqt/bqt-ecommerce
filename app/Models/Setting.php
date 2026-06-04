<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Setting extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'settings';
    protected $primaryKey = 'id';

    protected $fillable = [
        'setting_group_id',
        'field_name',
        'key',
        'value',
        'purpose',
    ];

    public function group()
    {
        return $this->belongsTo(SettingGroup::class, 'setting_group_id', 'id');
    }
}
