<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Member;
use Illuminate\Http\Request;

class CustomerDetailsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $customers = Customer::all();

        // Fetching member details for each customer
        foreach ($customers as $customer) {
            $memberIds = json_decode($customer->members_id);
            $members = Member::whereIn('id', $memberIds)->get();
            $customer->members = $members; // Attach member details to each customer
        }

        return response()->json($customers);
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        return $customer;
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($customerId)
    {
        $customer = Customer::findOrFail($customerId);
        $memberIds = json_decode($customer->members_id);

        foreach ($memberIds as $mem) {
            Member::where('id',$mem)->delete();
        }
        // Delete associated members

        // Delete the customer
        $customer->delete();

        return response()->json(['message' => 'Customer and associated members deleted successfully']);
    }
}
