<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CustomerMembershipController extends Controller
{
    public function __construct() {
        $this->middleware(['auth']);
    }

    function __invoke()
    {
        return view('CustomerSide.membership.index');
    }
}
