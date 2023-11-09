<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    function __construct()
    {
     $this->middleware('auth');
    }
    function __invoke()
    {
     return view('customer.index');
    }
}
