<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TripMembersController extends Controller
{
   public function savetrip(Request $request) {
        dd($request->all());
    }
}
