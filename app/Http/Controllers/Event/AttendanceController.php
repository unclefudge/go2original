<?php

namespace App\Http\Controllers\Event;

use DB;
use Auth;
use Validator;
use App\Models\Event\Event;
use App\Models\Event\EventInstance;
use App\Models\Event\Attendance;
use App\Models\People\People;
use Carbon\Carbon;
use Kamaln7\Toastr\Facades\Toastr;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AttendanceController extends Controller {

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Check authorisation
    }

    /**
     * Display the specified resource.
     *
     */
    public function show($id)
    {
        //
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store()
    {
        if (request()->ajax())
            return Attendance::create(request()->all());


        return view('errors/404');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        if ($request->ajax()) {
            $attend = Attendance::findOrFail($id);
            $attend->update($request->all());

            return $attend;
        }

        return view('errors/404');
    }

    /**
     * Delete the specified resource in storage.
     */
    public function destroy()
    {

        if (request()->ajax()) {
            //dd(request()->all());
            $attend = Attendance::where('eid', request('eid'))->where('pid', request('pid'))->delete();
            return $attend;
        }

        return view('errors/404');
    }


}
