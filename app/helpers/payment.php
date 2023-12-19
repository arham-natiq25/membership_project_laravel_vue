<?php

namespace App\helper;

use Stripe\Stripe;

trait paymentProcessing

{
    function createCustomer($id) {
        return $id;
        Stripe::setApiKey(config('stripe.sk'));
    }
    function proccessPayment() {
        
    }
}

