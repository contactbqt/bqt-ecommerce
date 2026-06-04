<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DbBackupLog extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'file_name',
        'file_size',
        'path',
        'created_by',
        'created_at',
        'status',
        'error_message',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];
}
