<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Membership;
use Illuminate\Http\Request;

class GetActiveMembershipController extends Controller
{
    function index() {
        $membership = Membership::where('status',1)->first();
        return response()->json($membership);
    }
}
