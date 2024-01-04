<?php

namespace App\Interfaces;

interface Payments {
    public function charge($request);
    public function chargeGuest($request,$user);
}
