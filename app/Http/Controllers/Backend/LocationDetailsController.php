<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Location;
use Illuminate\Http\Request;

class LocationDetailsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $locations = Location::all();
        return $locations;
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
            'name'=>'required|max:25',
            'map_link'=>'required',
            'depart_time'=>'required',
            'return_time'=>'required',
            'address'=>'required|max:300',
            'description'=>'required'
        ]);
        $location = Location::create($request->post());
        return response()->json([
            'message'=>'Location created successfully',
            'location'=>$location
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
    public function update(Request $request,Location $location)
    {
        $request->validate([
            'name'=>'required|max:25',
            'map_link'=>'required',
            'depart_time'=>'required',
            'return_time'=>'required',
            'address'=>'required|max:300',
            'description'=>'required'
        ]);
        $location->fill($request->post())->save();
        return response()->json([
            'message'=>'Location Updated successfully',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Location $location)
    {
        $location->delete();
        return response()->json([
            'message'=>'Location Deleted successfully',
        ]);
    }
}
