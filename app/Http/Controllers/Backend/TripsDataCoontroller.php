<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Trip;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class TripsDataCoontroller extends Controller
{
    public function archievedTrips()
    {
        $archivedTrips = Trip::with('route.locations')
            ->whereDate('trip_date', '<', Carbon::today())
            ->get();

        return Response::json($archivedTrips);
    }
    function activeTrips() {
        $activeTrips = Trip::with('route.locations')
        ->where('status', 1)
        ->whereDate('trip_date', '>=', Carbon::now())
        ->get();

        return response()->json($activeTrips);
    }
    public function search(Request $request)
    {
        $query = $request->input('query');

        // Use the "like" operator to search by trip_name
        if ($query!="") {
            $searchResults = Trip::with('route.locations')
            ->where('trip_name', 'like', '%' . $query . '%')
            ->get();

        return response()->json($searchResults);
        }
        else
        return "";

    }
}
