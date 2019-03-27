<?php

namespace App\Http\Controllers\Account;

use DB;
use Auth;
use Validator;
use App\User;
use App\Models\Account\Account;
use App\Models\Account\Admin;
use Kamaln7\Toastr\Facades\Toastr;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminController extends Controller {

    /**
     * Store a newly created resource in storage.
     */
    public function store()
    {
        if (request()->ajax()) {
            //$user = User::find()
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function addAdmin()
    {
        // Check authorisation
        if (Auth::user()->permissions && Auth::user()->permissions->admin) {
            if (request()->ajax()) {
                $admin = Admin::where('aid', session('aid'))->where('uid', request('uid'))->first();
                if ($admin) {
                    $admin->admin = 1;
                    $admin->save();
                } else
                    $admin = Admin::create(['aid' => session('aid'), 'uid' => request('uid'), 'admin' => 1]);

                return $admin;
            }
        }

        return abort(404);
    }
    
    /**
     * Update the specified resource in storage.
     */
    public function updatePermission($id)
    {
        $admin = Admin::find($id);

        if (request()->ajax()) {
            $admin_request = request()->all();
            $hasPermissions = (request('admin') || request('billing') || request('checkin') || request('people') || request('events') || request('groups')) ? 1 : 0;
            if ($hasPermissions) {
                //echo "has perms<br>";
                if (!$admin)
                    $admin = Admin::create($admin_request); // Create Admin
                else
                    $admin->update($admin_request); // Update Admin
                return $admin;
            } else {
                //dd('no perms');
                if ($admin)
                    $admin->delete();

                return response()->json(['success', '200']);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $admin = Admin::findOrFail($id)->delete();

        if (request()->ajax())
            return response()->json(['success', '200']);

        return abort(404);
    }


    /**
     * Get Admins (ajax)
     */
    public function getAdmins()
    {
        $account = Account::findOrFail(session('aid'));
        $admins = [];
        $admins_org = [];
        $admin_billing = [];
        // Admins
        foreach ($account->admins->sortBy('name') as $person) {
            $admin = Admin::where('aid', session('aid'))->where('uid', $person->id)->first();
            $data = [
                'id'        => $admin->id,
                'uid'       => $person->id,
                'name'      => $person->name,
                'firstname' => $person->firstname,
                'type'      => $person->type,
                'phone'     => $person->phone,
                'email'     => $person->email,
                'suburb'    => $person->suburb,
                'state'     => $person->state,
                'photo'     => $person->photoSmPath,
                'admin'     => $admin->admin,
                'billing'   => $admin->billing,
                'people'    => $admin->people,
                'checkin'   => $admin->checkin,
                'events'    => $admin->events,
                'groups'    => $admin->groups,
            ];
            $admins[] = $data;
            if ($admin->admin)
                $admins_org[] = $data;
        }

        // Active people
        $people = [];
        foreach (User::where('aid', session('aid'))->where('status', 1)->get() as $person) {
            $people[] = [
                'id'        => 0,
                'uid'       => $person->id,
                'name'      => $person->name,
                'firstname' => $person->firstname,
                'type'      => $person->type,
                'phone'     => $person->phone,
                'email'     => $person->email,
                'suburb'    => $person->suburb,
                'state'     => $person->state,
                'photo'     => $person->photoSmPath,
                'admin'     => ($person->permissions && $person->permissions->admin) ? 1 : 0,
            ];
        }

        $json = [];
        $json[] = $admins;
        $json[] = $admins_org;
        $json[] = $admin_billing;
        $json[] = $people;

        return $json;
    }

}
