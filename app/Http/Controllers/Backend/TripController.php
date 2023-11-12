<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TripController extends Controller
{
    public function __construct() {
        $this->middleware(['auth']);
    }
    function __invoke()
    {
        return view('trip.index');
    }
}
