<?php

namespace App\Http\Controllers\Event;

use DB;
use Auth;
use Validator;
use App\Models\Event\Attendance;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AttendanceController extends Controller {

    /**
     * Store a newly created resource in storage.
     */
    public function store()
    {
        //dd(request('method'));
        if (request()->ajax()) {
            // Ensure we don't check someone in who's already checked in
            $attend = Attendance::where('eid', request('eid'))->where('pid', request('pid'))->first();
            if (!$attend)
                $attend = Attendance::create(request()->all());

            return $attend;
        }

        return abort(404);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($id)
    {
        if (request()->ajax()) {
            $attend = Attendance::findOrFail($id);
            $attend->update(request()->all());

            return $attend;
        }

        return abort(404);
    }

    /**
     * Delete the specified resource in storage.
     */
    public function destroy()
    {

        if (request()->ajax()) {
            $attend = Attendance::where('eid', request('eid'))->where('pid', request('pid'))->delete();

            return response()->json(['success', '200']);
        }

        return abort(404);
    }


}
