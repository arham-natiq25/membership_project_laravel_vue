<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Trip;
use App\Models\TripMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GetLoginCustomerTrips extends Controller
{
    function index() {
        $user = Auth::user();
        $customer = Customer::where('user_id',$user->id)->first();
        $members = $customer->members;

        $tripMembers = [];

        foreach ($members as $member) {
            // Retrieve trip members for each member
            $tripMembers = TripMember::where('member_id', $member->id)->get();

            // foreach ($tripMembers as $tripMember) {
            //     $tripMembersData[$tripMember->trip_id][] = [
            //         'trip' => Trip::find($tripMember->trip_id),
            //         'member' => $member,
            //         'trip_member' => $tripMember,
            //     ];
            // }

            // Append the trip members data to the array
            $tripMembersDataRec[$member->id] = $tripMembers;
        }
        $uniqueTripIds = collect($tripMembersDataRec)->flatten(1)->unique('trip_id')->pluck('trip_id')->toArray();
        $tripsData = Trip::whereIn('id', $uniqueTripIds)->get();

        return response()->json($tripsData);

    }
}
