<?php

namespace App\Http\Controllers\People;

use DB;
use Auth;
use Validator;
use App\User;
use App\Models\People\Household;
use App\Models\People\PeopleHistory;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HouseholdController extends Controller {

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store()
    {
        if (request()->ajax()) {
            // Create Household
            $household = Household::create(request()->all());

            // Add Members
            if (request('members')) {
                foreach (request('members') as $member)
                    DB::table('users_household')->insert(['hid' => $household->id, 'uid' => $member['uid']]);

                // Update user history for each member
                $house_before = (object) ['name' => $household->name, 'members' => []];
                $house_after = (object)  ['name' => $household->name, 'members' => $household->members->sortBy('firstname')->pluck('name')->toArray(), 'created_at' => $household->created_at];
                foreach (request('members') as $member) {
                    $user = User::findOrFail($member['uid']);
                    PeopleHistory::addHistory($user, 'household', $house_before, $house_after);
                }

            }

            return $household;
        }

        return abort(404);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($id)
    {
        if (request()->ajax()) {
            // Update Household
            $household = Household::findOrFail($id);
            $member_ids_before = $household->members->pluck('id')->toArray();
            $house_before = (object)  ['name' => $household->name, 'members' => $household->members->sortBy('firstname')->pluck('name')->toArray()];
            $household->update(request()->all());

            // Delete Members
            $member = DB::table('users_household')->where('hid', $id)->delete();
            //$member->members()->sync(request('members'));

            // Add Members
            if (request('members')) {
                foreach (request('members') as $member)
                    DB::table('users_household')->insert(['hid' => $id, 'uid' => $member['uid']]);

                // Update user history for each member
                $household = Household::findOrFail($id);
                $member_ids_after = $household->members->pluck('id')->toArray();
                $house_after = (object)  ['name' => $household->name, 'members' => $household->members->sortBy('firstname')->pluck('name')->toArray(), 'created_at' => $household->created_at];

                foreach (array_merge($member_ids_before, $member_ids_after) as $uid) {
                    $user = User::findOrFail($uid);
                    PeopleHistory::addHistory($user, 'household', $house_before, $house_after);
                }
            } else {
                // Delete household + update old members history
                $house_after = (object)  ['name' => $household->name, 'members' => [], 'created_at' => $household->created_at];
                foreach ($member_ids_before as $uid) {
                    $user = User::findOrFail($uid);
                    PeopleHistory::addHistory($user, 'household', $house_before, $house_after);
                }
                $household->delete();
            }

            return $household;
        }

        return abort(404);
    }

    /**
     * Delete the specified resource in storage.
     */

    public function destroy()
    {
        if (request()->ajax()) {
            $deleted = Household::where('id', request('id'))->delete();

            return response()->json(['success', '200']);
        }

        return abort(404);
    }

    /**
     * Get Households + Members (ajax)
     */
    public function getMembers($id)
    {
        $person = User::findOrFail($id);
        $households = [];
        $household1 = [];
        $members = [];
        $household_count = 0;
        foreach ($person->households->sortBy('name') as $household) {
            $household_count ++;
            $households[] = ['id' => $household->id, 'name' => $household->name, 'uid' => $household->uid, 'count' => $household->members->count()];
            foreach ($household->members as $member) {
                $array = [
                    'hid'    => $household->id,
                    'uid'    => $member->id,
                    'name'   => $member->name,
                    'type'   => $member->type,
                    'phone'  => $member->phone,
                    'email'  => $member->email,
                    'photo'  => $member->photoSmPath,
                    'status' => $member->status,
                ];
                $members[] = $array;
                // For 1st household also save members to its own array;
                if ($household_count == 1)
                    $household1[] = $array;
            }
        }

        // Active people
        $people = [];
        //foreach (User::where('aid', session('aid'))->where('status', 1)->get() as $person)
        foreach (User::where('aid', session('aid'))->get() as $person)
            $people[] = [
                'uid'    => $person->id,
                'name'   => $person->name,
                'type'   => $person->type,
                'phone'  => $person->phone,
                'email'  => $person->email,
                'suburb' => $person->suburb,
                'state'  => $person->state,
                'photo'  => $person->photoSmPath
            ];

        $json = [];
        $json[] = $households;
        $json[] = $household1;
        $json[] = $members;
        $json[] = $people;

        return $json;
    }

}
