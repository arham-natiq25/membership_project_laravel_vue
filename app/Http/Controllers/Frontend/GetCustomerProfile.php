<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\CustomerProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GetCustomerProfile extends Controller
{
    function index() {
        $user = Auth::user();
        $cards = CustomerProfile::where('customer_id', $user->id)->get();
        return response()->json($cards);
    }
}
