<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\PaymentIntent;
use Stripe\Stripe;

class PaymentController extends Controller
{
    public function processPayment(Request $request)
    {
        // Set your Stripe Secret Key
        Stripe::setApiKey(config('stripe.sk'));

        $paymentMethodId = $request->paymentMethodId;

        try {
            // Create a PaymentIntent
            $intent = PaymentIntent::create([
                'payment_method' => $paymentMethodId,
                'amount' => 1000, // Set the amount to be charged (in cents)
                'currency' => 'usd',
                'confirmation_method' => 'manual',
                'confirm' => true,
            ]);

            // PaymentIntent status handling
            if ($intent->status === 'succeeded') {
                // Payment is successful
                return response()->json(['success' => true, 'message' => 'Payment successful']);
            } else {
                // Payment is not successful
                return response()->json(['success' => false, 'message' => 'Payment failed']);
            }
        } catch (\Exception $e) {
            // Handle any exceptions or errors
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

}
