<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TripMember extends Model
{
    use HasFactory;

    protected $table = "trip_members";
    protected $primaryKey = "id";
    protected $fillable =[
        'trip_id',
        'member_id',
        'location_id'
    ];
}
