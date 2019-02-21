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

class EventInstanceController extends Controller {

    /**
     * Store a newly created resource in storage.
     */
    public function store()
    {
        $event = Event::findOrFail(request('eid'));

        $instance_request = request()->all();
        $instance_request['start'] = Carbon::createFromFormat(session('df'). ' H:i', request('pastdate') . '00:00')->toDateTimeString();
        $instance_request['name'] = (request('name')) ? request('name') : $event->name;

        // Verify not existing instance on same date
        $existing = EventInstance::where('eid', $event->id)->whereDate('start', $instance_request['start'])->first();
        if ($existing) {
            Toastr::error("Existing event on ".$existing->start->format(session('df')), null, ["timeOut" => "10000"]);
            return redirect("/event/$existing->eid/attendance/".request('cdate'));
        }


        //dd($instance_request);
        $instance = EventInstance::create($instance_request);

        Toastr::success("Event created");

        return redirect("/event/$instance->eid/attendance/".$instance->start->format('Y-m-d'));
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
        $date = $instance->start->format(session('df'));
        $instance->delete();

        Toastr::error("Deleted event on $date");

        return redirect("/event/$eid/attendance/0");
    }


}
