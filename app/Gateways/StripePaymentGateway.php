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
use Illuminate\Support\Facades\Auth;
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

        if ($request->payment_for===1) {
            $customer_id = $request->input('customer_id');
            $trip_id = $request->input('trip_id');
            $trip = Trip::where('id',$trip_id)->first();
            $price = $trip->price;
            $customer = Customer::where('id', $customer_id)->first();
            $user = User::where('id', $customer->user_id)->first();
        }elseif ($request->payment_for===0) {
            $user = Auth::user();
            $price = $request->price;

        }
        // get user email nd name here
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
                'amount' => $price*100 , // Set the amount to be charged (in cents)
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
                    'payment'=>$price,
                    'trx_id'=>$chargeId,
                    'payment_for'=>$request->payment_for // 1 for trip
                ]);

                if ($request->payment_for===0) {
                    return response()->json([
                        'message' => 'You Buy Membership Successfully',
                    ]);
                }
                elseif ($request->payment_for===1) {
                    return response()->json([
                        'message' => 'You Buy Trip Successfully',
                    ]);
                }
        } catch (\Throwable $th) {
            return response()->json([
                'error' =>  $th->getMessage(),
            ], 500);
        }
    }
}
