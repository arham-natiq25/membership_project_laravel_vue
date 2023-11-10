<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;

    protected $fillable = [
    'membership_id',
    'customer_id',
    'first_name',
    'last_name',
    'dob',
    'gender',
    'activity'
];

    public function memberships() {
        return $this->belongsTo(Membership::class);
    }
    public function customers()  {
        return $this->belongsTo(Customer::class);
    }
}
