<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    protected $fillable = ['members_id', 'user_id', 'phone_no', 'paymentID','name'];

    public function users(){
        return $this->belongsTo(User::class);
    }
    function members() {
        return $this->hasMany(Member::class);
    }
}
