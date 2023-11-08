<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    protected $fillable = ['member_id', 'user_id', 'phone_no', 'paymentID'];

    public function users(){
        return $this->belongsTo(Customer::class);
    }
}
