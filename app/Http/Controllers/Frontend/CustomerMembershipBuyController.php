<?php

namespace App\Http\Controllers\Frontend;

use App\Gateways\StripePaymentGateway;
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
        
            $user = Auth::user();
            $customer = Customer::where('user_id', $user->id)->first();
            $memberData = $request->items;
            $membership_id = $request->membership_id;
            $gender = ($memberData['gender'] === 'male') ? 1 : 0;
            $activity = ($memberData['activity'] === 'skier') ? 1 : 0;


            $charge = new StripePaymentGateway();
            $res= $charge->charge($request);

            if ($res->isSuccessful()) {
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
            }

            return $res;

    }
}
