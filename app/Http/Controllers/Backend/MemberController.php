<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator as FacadesValidator;

class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $members = Member::all();
        return $members;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {


        $validator = FacadesValidator::make($request->all(), [
            'memberships.*.fname' => 'required',
            'memberships.*.lname' => 'required',
            'memberships.*.dob' => 'required|date',
            'memberships.*.gender' => 'required|in:male,female',
            'memberships.*.activity' => 'required|in:skier,snowboarder',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $memberships = $request->input('memberships');

        foreach ($memberships as $membership) {
            // Validate the data for each membership - similar to how you did with FormRequest or Validator
            $gender = ($membership['gender'] === 'male') ? 1 : 0;
            $activity = ($membership['activity'] === 'skier') ? 1 : 0;

            // Create or update the model instance
            $member = new Member([
                'first_name' => $membership['fname'],
                'last_name' => $membership['lname'],
                'dob' => $membership['dob'],
                'gender' => $gender,
                'activity' => $activity,
                'membership_id' => $request->membership_id
            ]);

            $member->save(); // Save the record for each membership
        }

        return response()->json([
            'message' => 'Memberships saved successfully',

        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Member $member)
    {
        return $member;
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
    public function update(Request $request, Member $member)
    {
        $member->fill($request->post())->save();
        return response()->json([
            'message' => 'Member Updated Successfully',
            'member' => $member
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Member $member)
    {
        $member->delete();
        return response()->json([
            'message' => 'Membership deleted successfully',
        ]);
    }
}
