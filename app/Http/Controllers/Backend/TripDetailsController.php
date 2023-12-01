<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Route;
use App\Models\Trip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TripDetailsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

    $trips = Trip::with('route.locations')->paginate(3); // Adjust the number of items per page as needed
    return response()->json($trips);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'trip_name'=>'required|string|max:25',
            'trip_date'=>'required',
            'booking_close_date'=>'required',
            'price'=>'required|numeric',
            'close_trip_booking'=>'required',
            'auto_activation_date'=>'required',
            'route_id'=>'required'

        ]);
        $trip = Trip::create($request->post());
        return response()->json([
            'message'=>'Trip created successfully',
            'trip'=>$trip
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Trip $trip)
    {
        $request->validate([
            'trip_name'=>'required|string|max:25',
            'trip_date'=>'required',
            'booking_close_date'=>'required',
            'price'=>'required|numeric',
            'close_trip_booking'=>'required',
            'auto_activation_date'=>'required',

        ]);
        $trip->fill($request->post())->save();
        return response()->json([
            'message'=>'Trip updated successfully',
            'membership'=>$trip,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Trip $trip)
    {
        $trip->delete();
        return response()->json([
            'message'=>'Trip Deleted successfully',
        ]);
    }
}
