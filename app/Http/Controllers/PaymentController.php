<?php

namespace App\Http\Controllers;

use App\Gateways\AuthorizenetPaymentGateway;
use App\Gateways\StripePaymentGateway;
use App\Models\Customer;
use App\Models\CustomerProfile;
use App\Models\Member;
use App\Models\Setting;
use App\Models\TransactionRecords;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Stripe\PaymentIntent;
use Stripe\Stripe;
use Stripe\Customer as StripeCustomer;
use Stripe\PaymentMethod;

class PaymentController extends Controller
{
    public function processPayment(Request $request)
    {
        $setting = Setting::find(1);

        $user = User::where('email', $request->email)->first();
        if(!$user){
             $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'customer'
            ]);
        }
        if ($setting===null || $setting->active_gateway===1) {
            $charge = new StripePaymentGateway();
            $res =  $charge->chargeGuest($request,$user);
        }elseif ($setting->active_gateway===0) {
            $charge = new AuthorizenetPaymentGateway();
            $res =  $charge->chargeGuest($request,$user);
        }


            if($res->isSuccessful()){
                $customer =   Customer::create([
                    'name' => $request->name,
                    'members_id' => json_encode($request->memberIDs),
                    'user_id' => $user->id,
                    'phone_no' => $request->phone,
                ]);
                foreach ($request->memberIDs as $memberID) {
                    Member::where('id', $memberID)->update([
                        'customer_id' => $customer->id
                    ]);
                }
            }else{
                foreach ($request->memberIDs as $memberID) {
                    Member::where('id', $memberID)->delete();
                }
            }

            return $res;
    }
}
