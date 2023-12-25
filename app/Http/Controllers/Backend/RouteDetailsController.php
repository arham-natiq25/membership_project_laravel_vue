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
            'route.title' => 'required',
            'route.short_description' => 'required',
            'route.long_description' => 'required',
            'addedLocations' => 'required|array|min:1',
        ]);
        $routeData = $request->input('route');
        $addedLocations = $request->input('addedLocations');


        $locationIds = [];
        foreach ($addedLocations as $locationData) {
         $location = new Location();
        $location->name = $locationData['name'];
        $location->map_link = $locationData['map_link'];
        $location->depart_time = $locationData['depart_time'];
        $location->return_time = $locationData['return_time'];
        $location->address = $locationData['address'];
        $location->description = $locationData['description'];
        $location->save();

        $locationIds[] = $location->id;
    }

        $loc_ids=json_encode($locationIds);
        $route = Route::create([
            "title"=>$routeData['title'],
            "short_description"=>$routeData['short_description'],
            "long_description"=>$routeData['long_description'],
            "locations_id"=>$loc_ids
        ]);

        foreach ($locationIds as $locationId) {
            $location = Location::find($locationId);
            $location->route_id = $route->id;
            $location->save();
        }

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
        $routeData = $request->input('route');
        $request->validate([
            'route.title' => 'required',
            'route.short_description' => 'required',
            'route.long_description' => 'required',
        ]);
        $route = Route::find($id);

        if (!$route) {
            return response()->json(['message' => 'Route not found'], 404);
        }
        $route->update([
            "title" => $routeData['title'],
            "short_description" =>$routeData['short_description'],
            "long_description" => $routeData['long_description'],
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
        $route->locations()->delete();
        $route->delete();
        return response()->json([
            'message'=>'Route Deleted successfully',
        ]);
    }
}
