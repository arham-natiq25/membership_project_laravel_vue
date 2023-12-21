<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\CustomerProfile;
use App\Models\Member;
use App\Models\TransactionRecords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\Customer as StripeCustomer;
use Stripe\PaymentIntent;
use Stripe\PaymentMethod;
use Stripe\Stripe;

class CustomerMembershipBuyController extends Controller
{
    function index(Request $request)
    {
        // make if else condition on the basis of type......
        Stripe::setApiKey(config('stripe.sk'));
        if ($request->method === 1) {
            $stripeCustomer =  $request->card['customer_payment_id'];
        }
            $user = Auth::user();
            $customer = Customer::where('user_id', $user->id)->first();
            $paymentMethodId = $request->paymentMethodId;
            $amount =  $request->price;
            $memberData = $request->items;
            $membership_id = $request->membership_id;
            $gender = ($memberData['gender'] === 'male') ? 1 : 0;
            $activity = ($memberData['activity'] === 'skier') ? 1 : 0;
            $paymentMethod = PaymentMethod::retrieve($paymentMethodId);
            $cardType = $paymentMethod->card->brand;
            $lastFourDigits = $paymentMethod->card->last4;

            try {
                if ($request->method === 0) {

                    $stripeCustomer = StripeCustomer::create([
                        'name' => $user->name,
                        'email' => $user->email,
                        'payment_method' => $paymentMethodId
                    ]);
                }
                $intent = PaymentIntent::create([
                    'payment_method' => $paymentMethodId,
                    'amount' => $amount * 100,
                    'currency' => 'usd',
                    'confirmation_method' => 'manual', // always manual
                    'confirm' => true, // always true,
                    'return_url' => 'https://127.0.0.1:8000', // necessary param
                    'customer' => $stripeCustomer,
                ]);
                $chargeId = $intent->latest_charge;
                $member =   Member::create([
                    'membership_id' => $membership_id,
                    'customer_id' => $customer->id,
                    'first_name' => $memberData['fname'],
                    'last_name' => $memberData['lname'],
                    'dob' => $memberData['dob'],
                    'gender' => $gender,
                    'activity' => $activity
                ]);
                $memberID = $member->id;
                $existingMemberIds = json_decode($customer->members_id, true) ?? [];
                $existingMemberIds[] = $memberID;
                $customer->update(['members_id' => json_encode($existingMemberIds)]);

                if(!$request->method===1){
                    CustomerProfile::create([
                        'customer_id' => $user->id,
                        'last_four_digits' => $lastFourDigits,
                        'customer_payment_id' => $stripeCustomer->id,
                        'paymentMethodId' => $paymentMethodId
                    ]);
                }

                TransactionRecords::create([
                    'customer_id' => $user->id,
                    'payment' => $amount,
                    'trx_id' => $chargeId,
                    'payment_for' => 0 // 1 for membership
                ]);
                return response()->json([
                    'message' => 'You Buy Membership Successfully',
                ]);
            } catch (\Throwable $th) {
                return response()->json([
                    'error' =>  $th->getMessage(),
                ], 500);
            }
    }
}
