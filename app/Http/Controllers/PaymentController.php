<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\CustomerProfile;
use App\Models\Member;
use App\Models\TransactionRecords;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Stripe\PaymentIntent;
use Stripe\Stripe;
use Stripe\Customer as StripeCustomer;
use Stripe\PaymentMethod;

class PaymentController extends Controller
{
    public function processPayment(Request $request)
    {
        // Set your Stripe Secret Key
        Stripe::setApiKey(config('stripe.sk'));
        $user = User::where('email', $request->email)->first();
        $paymentMethodId = $request->paymentMethodId;
        $paymentMethod = PaymentMethod::retrieve($paymentMethodId);
        $lastFourDigits = $paymentMethod->card->last4;
        if(!$user){
            User::create([
                'name'=>$request->name,
                'email'=>$request->email,
                'password'=>Hash::make($request->password),
                'role'=>'customer'
            ]);
            $user = User::where('email', $request->email)->first();
        }



        try {
            $stripeCustomer = StripeCustomer::create([
                'name' => $user->name,
                'email' => $user->email,
                'payment_method' => $paymentMethodId
            ]);
            // Create a PaymentIntent
            $intent = PaymentIntent::create([
                'payment_method' => $paymentMethodId,
                'amount' => $request->totalPayment*100, // Set the amount to be charged (in cents)
                'currency' => 'usd',
                'confirmation_method' => 'manual',
                'confirm' => true,
                'return_url' => 'https://127.0.0.1:8000',
                'customer' => $stripeCustomer,
            ]);


           $chargeId = $intent->latest_charge;




                  $customer=   Customer::create([
                        'name'=>$request->name,
                        'members_id'=>json_encode($request->memberIDs),
                        'user_id'=>$user->id,
                        'phone_no'=>$request->phone,
                    ]);

                    CustomerProfile::create([
                        'customer_id' => $user->id,
                        'last_four_digits' => $lastFourDigits,
                        'customer_payment_id' => $stripeCustomer->id,
                        'paymentMethodId' => $paymentMethodId
                    ]);

                    TransactionRecords::create([
                        'customer_id' => $user->id,
                        'payment' => $request->totalPayment,
                        'trx_id' => $chargeId,
                        'payment_for' => 0 // 0 for membership
                    ]);

                    foreach ($request->memberIDs as $memberID) {
                        Member::where('id',$memberID)->update([
                            'customer_id'=>$customer->id
                        ]);
                    }


                return response()->json(['success' => true, 'message' => 'Payment successful']);

            // PaymentIntent status handling
            // if ($intent->status === 'succeeded') {
            //     // Payment is successful
            // } else {
            //     // Payment is not successful
            //     return response()->json(['success' => false, 'message' => 'Payment failed']);
            // }
        } catch (\Exception $e) {
            foreach ($request->memberIDs as $memberID) {
                Member::where('id',$memberID)->delete();
            }
            // Handle any exceptions or errors
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

}
