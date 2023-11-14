<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Location;
use App\Models\Route;
use Illuminate\Http\Request;

class RouteDetailsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $routes = Route::all();
        foreach ($routes as $route) {
            $loc_ids = json_decode($route->locations_id); // Decode the JSON string
            $locations = Location::whereIn('id', $loc_ids)->get();
            $route->locations = $locations;
        }
        return response()->json($routes);
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
            'title'=>'required',
            'short_description'=>'required',
            'long_description'=>'required',
            'locations_id'=>'required'
        ]);
        $loc_id = json_encode($request->locations_id);
        $route = Route::create([
            "title"=>$request->title,
            "short_description"=>$request->short_description,
            "long_description"=>$request->long_description,
            "locations_id"=>$loc_id
        ]);
        return response()->json([
            'message'=>'Route created successfully',
            'route'=>$route
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
    public function update(Request $request,$id)
    {
        $request->validate([
            'title'=>'required',
            'short_description'=>'required',
            'long_description'=>'required',
            'locations_id'=>'required'
        ]);
        $route = Route::find($id);

        if (!$route) {
            return response()->json(['message' => 'Route not found'], 404);
        }
        $loc_id = json_encode($request->locations_id);
        $route->update([
            "title" => $request->title,
            "short_description" => $request->short_description,
            "long_description" => $request->long_description,
            "locations_id" => $loc_id
        ]);

        return response()->json([
            'message' => 'Route updated successfully',
            'route' => $route
        ]);


    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Route $route)
    {
        $route->delete();
        return response()->json([
            'message'=>'Route Deleted successfully',
        ]);
    }
}
