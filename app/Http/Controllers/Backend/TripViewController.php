<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TripViewController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }
    public function __invoke()
    {
        return view('trip.view');
    }
}
