<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Membership;
use Illuminate\Http\Request;

class MembershipController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index()
    {
       $memberships = Membership::all();
       return $memberships;
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
            'title'=>'required|string|max:111',
            'first_date'=>'required|date',
            'first_price'=>'required|numeric',
            'second_date'=>'required|date',
            'second_price'=>'required|numeric',
            'third_date'=>'required|date',
            'third_price'=>'required|numeric',
            'display_order'=>'required'
        ]);
        $membership = Membership::create($request->post());
        return response()->json([
            'message'=>'Membership created successfully',
            'membership'=>$membership
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Membership $membership)
    {
        return $membership;
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
    public function update(Request $request, Membership $membership)
    {
        $request->validate([
            'title'=>'required|string|max:111',
            'first_date'=>'required|date',
            'first_price'=>'required|numeric',
            'second_date'=>'required|date',
            'second_price'=>'required|numeric',
            'third_date'=>'required|date',
            'third_price'=>'required|numeric',
            'display_order'=>'required'
        ]);
        $membership->fill($request->post())->save();
        return response()->json([
            'message'=>'Membership updated successfully',
            'membership'=>$membership,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Membership $membership)
    {
        $membership->delete();
        return response()->json([
            'message'=>'Membership Deleted successfully',
        ]);
    }

}
