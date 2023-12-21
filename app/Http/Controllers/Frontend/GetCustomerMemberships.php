<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GetCustomerMemberships extends Controller
{
    function index() {
     $user =  Auth::user();
     $customer = Customer::where('user_id',$user->id)->first();
     $members= $customer->members;

     return response()->json($members);
    }
}
