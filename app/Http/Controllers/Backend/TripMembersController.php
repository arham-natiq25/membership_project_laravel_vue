<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Location;
use App\Models\TripMember;
use Illuminate\Http\Request;

class TripMembersController extends Controller
{
    public function savetrip(Request $request) {


        $loc_id = $request->input('loc_id');
        $trip_id = $request->input('trip_id');
        $member_ids = $request->input('member');
        $location = Location::find($loc_id);
        foreach ($member_ids as $member_id) {

            if ($location->total_seats == $location->sold_seats) {
                return response()->json([
                    "error" => "Seats unavailable",
                ], 422);
            }
            else{

                TripMember::create([
                    'trip_id' => $trip_id,
                    'member_id' => $member_id,
                    'location_id' => $loc_id
                ]);

                $location->sold_seats = $location->sold_seats+1;
                $location->avaliable_seats = $location->avaliable_seats - $location->sold_seats;
                $location->save();
            }
        }
        return response()->json([
            'message' => 'You Buy Trip Successfully',
            // ... other data you want to send ...
        ]);
    }

}
