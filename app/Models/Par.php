<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Par extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "parents";

    protected $guarded = [];

    protected $casts = [
        'is_complete' => 'boolean',
        'visited_at' => 'date',
        'info' => 'array'
    ];
}
