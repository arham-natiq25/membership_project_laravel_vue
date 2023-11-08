<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MembershipHomeController extends Controller
{
    function __invoke()
    {
     return view('membership.index');
    }
}
