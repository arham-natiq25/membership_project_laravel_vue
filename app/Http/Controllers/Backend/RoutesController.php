<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RoutesController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }
    function __invoke()
    {
        return view('routes.index');
    }
}
