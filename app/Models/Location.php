<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'map_link',
        'depart_time',
        'return_time',
        'address',
        'description',
        'route_id',
        'total_seats',
        'avaliable_seats',
        'sold_seats'
    ];
}
