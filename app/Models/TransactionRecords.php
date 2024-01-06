<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionRecords extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'payment',
        'trx_id',
        'payment_for',
        'gateway'
    ];
}
