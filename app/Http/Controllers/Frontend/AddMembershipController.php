<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Member;
use Exception;
use Illuminate\Http\Request;
use Stripe\PaymentIntent;
use Stripe\Stripe;

class AddMembershipController extends Controller
{
    function addMember(Request $request) {

        Stripe::setApiKey(config('stripe.sk'));
        $paymentMethodId = $request->paymentMethodId;
        $customerID = $request->customerId;
        $memberData = $request->memberdata;
        $membership_id=$request->membershipId;

        $customer = Customer::where('id',$customerID)->first();
        $gender = ($memberData['gender'] === 'male') ? 1 : 0;
        $activity = ($memberData['activity'] === 'skier') ? 1 : 0;

        try {
            // Create a PaymentIntent
            $intent = PaymentIntent::create([
                'payment_method' => $paymentMethodId,
                'amount' => $request->payment*100, // Set the amount to be charged (in cents)
                'currency' => 'usd',
                'confirmation_method' => 'manual',
                'confirm' => true,
                'return_url' => 'https://127.0.0.1:8000'
            ]);
          $member =   Member::create([
                'membership_id'=>$membership_id,
                'customer_id'=>$customerID,
                'first_name'=>$memberData['fname'],
                'last_name'=>$memberData['lname'],
                'dob'=>$memberData['dob'],
                'gender'=>$gender,
                'activity'=>$activity
            ]);

            $memberID = $member->id;

            $existingMemberIds = json_decode($customer->members_id, true) ?? [];
            $existingMemberIds[] = $memberID;
            // Save the updated member IDs in the customer table
            $customer->update(['members_id' => json_encode($existingMemberIds),
                    'paymentID'=>$paymentMethodId
        ]);

            return response()->json(['success' => true, 'message' => 'Payment successfully Recieved']);

        }catch(\Exception $e){
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
    }
}
}
