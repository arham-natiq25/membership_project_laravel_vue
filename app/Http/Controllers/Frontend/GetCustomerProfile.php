<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\CustomerProfile;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GetCustomerProfile extends Controller
{
    function index() {
        $setting =Setting::find(1);
        if ($setting === null || $setting->active_gateway === 1) {
             $gateway = 1;
        } else {
             $gateway = 0;
        }
        $user = Auth::user();
        $cards = CustomerProfile::where('customer_id', $user->id)
        ->where('gateway',$gateway)
        ->get();
        return response()->json($cards);
    }
}
