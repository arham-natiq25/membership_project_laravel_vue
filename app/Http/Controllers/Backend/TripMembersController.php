<?php

namespace App\Http\Controllers\Backend;

use App\Gateways\AuthorizenetPaymentGateway;
use App\Gateways\StripePaymentGateway;
use App\helper\paymentProcessing;
use App\Http\Controllers\Controller;
use App\Mail\TripPurchaseMail;
use App\Models\Customer;
use App\Models\CustomerProfile;
use App\Models\EmailManager;
use App\Models\Location;
use App\Models\Member;
use App\Models\Setting;
use App\Models\TransactionRecords;
use App\Models\Trip;
use App\Models\TripMember;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class TripMembersController extends Controller
{
    public function savetrip(Request $request)
    {
        $setting = Setting::find(1);
        $loc_id = $request->input('loc_id');
        $customer_id = $request->input('customer_id');
        $trip_id = $request->input('trip_id');
        $trip = Trip::where('id', $trip_id)->first();
        $customer = Customer::where('id', $customer_id)->first();
        // get user email nd name here
        $user = User::where('id', $customer->user_id)->first();

        $member_ids = $request->input('member');
        $location = Location::find($loc_id);
 ;
        if ($setting===null || $setting->active_gateway===1) {
            $charge = new StripePaymentGateway();
            $res = $charge->charge($request);
        }elseif ($setting->active_gateway===0) {
            $charge = new AuthorizenetPaymentGateway();
            $res = $charge->charge($request);
        }
        // IF CHARGE IS SUCCESSFULL THEN SAVE DETAILS
        if ($res->isSuccessful()) {
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
        }

        return $res;


    }
    public function getTrips()
    {
        $tripmembers = TripMember::all();
        return   $tripmembers;
    }
}
