<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Stripe\PaymentIntent;
use Stripe\PaymentMethod;
use Stripe\Stripe;

class SaveTripCardController extends Controller
{
    function index(Request $request) {

        Stripe::setApiKey(config('stripe.sk'));
        $paymentMethodId = $request->card['paymentMethodId'];
        $loc_id = $request->input('loc_id');
        $customer_id = $request->input('customer_id');
        $trip_id = $request->input('trip_id');
        $trip = Trip::where('id',$trip_id)->first();
        $customer = Customer::where('id', $customer_id)->first();
        // get user email nd name here
        $user = User::where('id', $customer->user_id)->first();

        $member_ids = $request->input('member');
        $location = Location::find($loc_id);
        $paymentMethod = PaymentMethod::retrieve($paymentMethodId);
        $cardType = $paymentMethod->card->brand;
        $lastFourDigits = $paymentMethod->card->last4;
        $Stripecustomer = $request->card['customer_payment_id'];

        try {
            $intent = PaymentIntent::create([
                'payment_method' => $paymentMethodId, // from frontend
                'amount' => $trip->price*100, // Set the amount to be charged (in cents)
                'currency' => 'usd',
                'confirmation_method' => 'manual', // always manual
                'confirm' => true, // always true,
                'return_url' => 'https://127.0.0.1:8000', // necessary param
                'customer' => $Stripecustomer,
            ]);
                $chargeId = $intent->latest_charge;
                foreach ($member_ids as $member_id) {

                    TripMember::create([
                        'trip_id' => $trip_id,
                        'member_id' => $member_id,
                        'location_id' => $loc_id
                    ]);
                }
                $tripDetails = Trip::find($trip_id)->toArray();
                $customerEmail = $user->email;
                $customerDetails = $customer->toArray();
                $members = Member::whereIn('id', $member_ids)->get()->toArray();
                $emailContent = view('emails.tripPurchase', compact('tripDetails', 'customerDetails', 'members'))->render();

                $folderPath = public_path('emails');
                if (!file_exists($folderPath)) {
                    mkdir($folderPath, 0777, true);
                }



                $fileName = 'trip_purchase_' . now()->format('Ymd_His') . '.html';
                $filePath = $folderPath . '/' . $fileName;
                file_put_contents($filePath, view('emails.tripPurchase', compact('tripDetails', 'customerDetails', 'members'))->render());

                Mail::to($customerEmail)
                    ->send(new TripPurchaseMail($tripDetails, $customerDetails, $members, $fileName));


                EmailManager::create([
                    'customer_id' => $customer_id,
                    'path_of_email' => "emails/" . $fileName
                ]);

                TransactionRecords::create([
                    'customer_id'=>$user->id,
                    'payment'=>$trip->price,
                    'trx_id'=>$chargeId,
                    'payment_for'=>1 // 1 for trip
                ]);


                return response()->json([
                    'message' => 'You Buy Trip Successfully',
                ]);


        } catch (\Throwable $th) {
            return response()->json([
                'error' =>  $th->getMessage(),
            ], 500);
        }



    }
}
