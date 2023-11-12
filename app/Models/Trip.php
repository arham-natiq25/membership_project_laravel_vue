<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    use HasFactory;

    protected $fillable = [
        'trip_name',
        'trip_date',
        'booking_close_date',
        'price',
        'close_trip_booking',
        'auto_activation_date',
        'status',
        'night'
    ];
}
