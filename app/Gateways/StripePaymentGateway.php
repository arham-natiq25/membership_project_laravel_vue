<?php

namespace App\Gateways;

use App\Interfaces\Payments;
use App\Mail\TripPurchaseMail;
use App\Models\Customer;
use App\Models\CustomerProfile;
use App\Models\EmailManager;
use App\Models\Location;
use App\Models\Member;
use App\Models\TransactionRecords;
use App\Models\Trip;
use App\Models\TripMember;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Stripe\Customer as StripeCustomer;
use Stripe\PaymentIntent;
use Stripe\PaymentMethod;
use Stripe\Stripe;

class StripePaymentGateway implements Payments {

    function charge($request)
    {
        Stripe::setApiKey(config('stripe.sk'));
        if($request->method===1){
            $paymentMethodId = $request->card['paymentMethodId'];
            $stripeCustomer=$request->card['customer_payment_id'];
        }
        elseif($request->method===0){
            $paymentMethodId = $request->paymentMethodId;
        }

        $customer_id = $request->input('customer_id');
        $trip_id = $request->input('trip_id');
        $trip = Trip::where('id',$trip_id)->first();
        $customer = Customer::where('id', $customer_id)->first();
        // get user email nd name here
        $user = User::where('id', $customer->user_id)->first();
        $paymentMethod = PaymentMethod::retrieve($paymentMethodId);
        $lastFourDigits = $paymentMethod->card->last4;

        try {
            if($request->method===0){
                $stripeCustomer = StripeCustomer::create([
                    'email'=>$user->email,
                    'name'=>$user->name,
                    'payment_method'=>$paymentMethodId
                ]);
            }
            $intent = PaymentIntent::create([
                'payment_method' => $paymentMethodId, // from frontend
                'amount' => $trip->price*100, // Set the amount to be charged (in cents)
                'currency' => 'usd',
                'confirmation_method' => 'manual', // always manual
                'confirm' => true, // always true,
                'return_url' => 'https://127.0.0.1:8000', // necessary param
                'customer' => $stripeCustomer,
            ]);
                $chargeId = $intent->latest_charge;

                if($request->method===0){
                    CustomerProfile::create([
                        'customer_id'=>$user->id,
                        'last_four_digits'=>$lastFourDigits,
                        'customer_payment_id'=>$stripeCustomer->id,
                        'paymentMethodId'=>$paymentMethodId
                    ]);
                }

                TransactionRecords::create([
                    'customer_id'=>$user->id,
                    'payment'=>$trip->price,
                    'trx_id'=>$chargeId,
                    'payment_for'=>$request->payment_for // 1 for trip
                ]);


                return response()->json([
                    'message' => 'You Buy Trip Successfully',
                ]);


        } catch (\Throwable $th) {
            return response()->json([
                'error' =>  $th->getMessage(),
            ], 500);
        }


        // get customer ..




        // // Get member details for the customer

        // // Send the email



    }
}
