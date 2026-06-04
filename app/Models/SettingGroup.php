<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SettingGroup extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'setting_groups';
    protected $primaryKey = 'id';

    protected $fillable = [
        'group_name',
        'slug_name',
        'instruction_text',
    ];

    public function settings()
    {
        return $this->hasMany(Setting::class, 'setting_group_id', 'id');
    }
}
