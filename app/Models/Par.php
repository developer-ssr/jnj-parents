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

    protected $dates = [
        'visited_at'
    ];

    protected $casts = [
        'is_complete' => 'boolean'
    ];
}
