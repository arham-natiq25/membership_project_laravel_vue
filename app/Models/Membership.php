<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Membership extends Model
{
    use HasFactory;

    protected $fillable = [
      'title',
      'first_date',
      'first_price',
      'second_date',
      'second_price',
      'third_date',
      'third_price',
      'limit',
      'status',
      'display_order'
    ];
}
