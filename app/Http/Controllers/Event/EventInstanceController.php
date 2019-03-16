<?php

namespace App\Http\Controllers\Event;

use DB;
use Auth;
use Validator;
use App\Models\Event\Event;
use App\Models\Event\EventInstance;
use Carbon\Carbon;
use Camroncade\Timezone\Facades\Timezone;
use Kamaln7\Toastr\Facades\Toastr;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EventInstanceController extends Controller {

    /**
     * Store a newly created resource in storage.
     */
    public function store()
    {
        $event = Event::findOrFail(request('eid'));

        // Validate
        $rules = ['pastdate' => 'date_format:' . session('df')];
        $mesgs = ['pastdate.date_format'    => 'The date format needs to be ' . session('df-datepicker')];
        $validator = Validator::make(request()->all(), $rules, $mesgs);
        if ($validator->fails()) {
            $validator->errors()->add('FORM', 'event');

            return back()->withErrors($validator)->withInput();
        }

        $instance_request = request()->all();

        // Convert date to UTC
        $localDate = Carbon::createFromFormat(session('df'). ' H:i', request('pastdate') . '00:00')->format('Y-m-d');
        $localDatetime = Carbon::createFromFormat(session('df'). ' H:i', request('pastdate') . '00:00')->toDateTimeString();
        $utcDateStart = Timezone::convertToUTC($localDatetime, session('tz')); // Beginning of day UTC

        $instance_request['start'] = $utcDateStart;
        $instance_request['name'] = (request('name')) ? request('name') : $event->name;

        // Verify not existing instance on same date
        $existing = EventInstance::existingLocalDate($localDate, $event->id);
        //$existing = EventInstance::where('eid', $event->id)->whereBetween('start', [$utcDateStart, $utcDateEnd])->first();
        if ($existing) {
            Toastr::error("Existing event on ".request('pastdate'), null, ["timeOut" => "10000"]);
            return redirect("/event/$existing->eid/attendance/".request('cdate'));
        }

        $instance = EventInstance::create($instance_request);
        Toastr::success("Event created");

        return redirect("/event/$instance->eid/attendance/".$instance->start->timezone('tz')->format('Y-m-d'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($id)
    {
        //dd(request()->all());
        if (request()->ajax()) {
            $instance = EventInstance::findOrFail($id);
            $instance->update(request()->all());

            return $instance;
        }

        return view('errors/404');
    }

    /**
     * Delete the specified resource in storage.
     */
    public function destroy($id)
    {
        $instance = EventInstance::findOrFail($id);
        $eid = $instance->eid;
        $localDate = Timezone::convertFromUTC($instance->start, session('tz'), session('df'));
        $instance->delete();

        Toastr::error("Deleted event on $localDate");

        return redirect("/event/$eid/attendance/0");
    }


}
