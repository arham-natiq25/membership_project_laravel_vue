<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Mail\TripPurchaseMail;
use App\Models\Customer;
use App\Models\Location;
use App\Models\Member;
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

        // dd($request->all());
        $loc_id = $request->input('loc_id');
        $customer_id = $request->input('customer_id');
        $trip_id = $request->input('trip_id');
        $member_ids = $request->input('member');
        $location = Location::find($loc_id);
        foreach ($member_ids as $member_id) {

            TripMember::create([
                'trip_id' => $trip_id,
                'member_id' => $member_id,
                'location_id' => $loc_id
            ]);
        }

        $tripDetails = Trip::find($trip_id)->toArray();

        // Retrieve the customer's email using the relationship
        $customer = Customer::where('id',$customer_id)->first();
        $user = User::where('id',$customer->user_id)->first();
        $customerEmail = $user->email;

        $customerDetails = $customer->toArray();
        // // Get member details for the customer
        $members = Member::whereIn('id', $member_ids)->get()->toArray();

        // // Send the email
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





        return response()->json([
            'message' => 'You Buy Trip Successfully',
        ]);
    }
    public function getTrips()
    {
        $tripmembers = TripMember::all();
        return   $tripmembers;
    }
}
