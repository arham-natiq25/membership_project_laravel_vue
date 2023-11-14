<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Route extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'short_description',
        'long_description',
        'locations_id'
    ];

    protected $casts = [
        'locations_id' => 'array',
    ];
}
