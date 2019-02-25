<?php

namespace App\Http\Controllers\People;

use DB;
use Auth;
use Validator;
use App\Models\People\People;
use App\Models\People\Household;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HouseholdController extends Controller {

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
                    DB::table('households_people')->insert(['hid' => $household->id, 'pid' => $member['pid']]);
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
            $household->update(request()->all());

            // Delete Members
            $member = DB::table('households_people')->where('hid', $id)->delete();
            //$member->members()->sync(request('members'));

            // Add Members
            if (request('members')) {
                foreach (request('members') as $member)
                    DB::table('households_people')->insert(['hid' => $id, 'pid' => $member['pid']]);
            } else
                $household->delete();

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
        $person = People::findOrFail($id);
        $households = [];
        $household1 = [];
        $members = [];
        $household_count = 0;
        foreach ($person->households->sortBy('name') as $household) {
            $household_count ++;
            $households[] = ['id' => $household->id, 'name' => $household->name, 'pid' => $household->pid, 'count' => $household->members->count()];
            foreach ($household->members as $member) {
                $array = [
                    'hid'    => $household->id,
                    'pid'    => $member->id,
                    'name'   => $member->name,
                    'type'   => $member->type,
                    'phone'  => $member->phone,
                    'email'  => $member->email,
                    'photo'  => $member->photo_path,
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
        foreach (People::where('aid', session('aid'))->where('status', 1)->get() as $person)
            $people[] = [
                'pid'    => $person->id,
                'name'   => $person->name,
                'type'   => $person->type,
                'phone'  => $person->phone,
                'email'  => $person->email,
                'suburb' => $person->suburb,
                'state'  => $person->state,
                'photo'  => $person->photo_path
            ];

        $json = [];
        $json[] = $households;
        $json[] = $household1;
        $json[] = $members;
        $json[] = $people;

        return $json;
    }

}
