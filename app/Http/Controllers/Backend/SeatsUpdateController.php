<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Location;
use Illuminate\Http\Request;

class SeatsUpdateController extends Controller
{
    function updateSeats(Request $request) {
        // dd($request->all());

         $seats = $request->input('seats');
         $updatedLocation = $request->input('location');


        $location = Location::find($updatedLocation['id']);
        if ($location) {
            $location->total_seats =$seats;
            $location->avaliable_seats = $seats - $location->sold_seats;
           $location->save();

            return response()->json(['message' => 'Seats updated successfully', 'updatedLocation' => $location]);
        } else {
            return response()->json(['message' => 'Location not found'], 404);
        }
    }
}
