<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailManager extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'path_of_email'
    ];
}
