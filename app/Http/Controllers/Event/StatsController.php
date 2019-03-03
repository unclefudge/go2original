<?php

namespace App\Http\Controllers\Event;

use DB;
use Auth;
use Validator;
use App\Models\Event\Event;
use App\Models\Event\EventInstance;
use App\Models\Event\Attendance;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StatsController extends Controller {

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Check authorisation
    }


    /**
     * Update the specified resource in storage.
     */
    public function weekTotals()
    {
        $event = Event::findOrFail(2);
        $debug = false;
        //$event = Event::findOrFail(request('eid'));
        $da1 = '2018-01-01';
        $da2 = '2018-12-30';
        $date1 = Carbon::createFromFormat('Y-m-d H:i', $da1 . '00:00');
        $date2 = Carbon::createFromFormat('Y-m-d H:i', $da2 . '00:00');

        if ($debug) {
            echo "Event[$event->id]: $event->name<br>";
            echo "From:" . $date1->format('d/m/Y') . " - " . $date2->format('d/m/Y') . "<br>";
            echo "Mon:" . $date1->startOfWeek()->format('d/m/Y') . ' - ' . $date2->startOfWeek()->format('d/m/Y') . '<br>';
            echo "<h2>Dates</h2>";
        }

        $loop_date = Carbon::createFromFormat('Y-m-d H:i', $da1 . '00:00');
        $chartdata = [];
        while ($loop_date->lt($date2)) {
            $d1 = Carbon::parse($loop_date->format('Y-m-d'))->startOfWeek();
            $d2 = Carbon::parse($loop_date->format('Y-m-d'))->endOfWeek();
            $week = $d1->format('M j'); // . ' - ' . $d2->format('d/m');
            //echo "$week: " . $loop_date->startOfWeek()->format('Y-m-d') . "-" . $loop_date->endOfWeek()->format('Y-m-d') . ".<br>";
            $instance_ids = $event->betweenDates($d1->format('Y-m-d'), $d2->format('Y-m-d'))->pluck('id')->toArray();
            if ($debug) print_r($instance_ids);
            $attendance = Attendance::whereIn('eid', $instance_ids)->get();
            $a = $b = 0;
            $b = 4;
            if ($attendance) {
                if ($debug) echo "<br>Getting attendance<br>";
                foreach ($attendance as $attend) {
                    if ($debug) echo "$attend->eid:$attend->id : " . $attend->person->type . " : [" . $attend->person->id . '] '. $attend->person->name . "<br>";
                    if ($attend->person->isStudent()) {
                        $a ++;
                    }
                    if ($attend->person->isVolunteer())
                        $b ++;
                }
            }
            //echo "DDD: $a:$b<br>";

            $chartdata[] = ['y' => $week, 'a' => $a, 'b' => $b];
            $loop_date->addWeek();
        }

        return json_encode($chartdata);
        //if (request()->ajax()) {

        /*
         * { y: '2006', a: 100, b: 90 },
            { y: '2007', a: 75,  b: 65 },
            { y: '2008', a: 50,  b: 40 },
            { y: '2009', a: 75,  b: 65 },
            { y: '2010', a: 50,  b: 40 },
            { y: '2011', a: 75,  b: 65 },
            { y: '2012', a: 100, b: 90 }
         */
        $chartdata2 = array(array('y' => "2011", 'a' => 20, 'b' => 60), array('y' => "2012", 'a' => 18, 'b' => 21), array('y' => "2013", 'a' => 32, 'b' => 74), array('y' => "2014", 'a' => 38, 'b' => 63));

        echo json_encode($chartdata);
        echo "<h3>orig</h3>";
        echo json_encode($chartdata2);
        //return json_encode($chartdata);

        //$range = CarbonCarbon::now()->subDays(30);
        /*
                $stats = DB::table('events_attendance')
                    ->whereIn('eid', '>=', $instance_ids)
                    ->groupBy('eid')
                    ->orderBy('date', 'ASC')
                    ->get([
                        DB::raw('Date(in) as date'),
                        DB::raw('COUNT(*) as value')
                    ])
                    ->toJSON();

                //}
        */

        //return abort(404);
    }

    /**
     * Update the specified resource in storage.
     */
    public function weekTotals2()
    {
        $event = Event::findOrFail(2);
        $debug = false;
        //$event = Event::findOrFail(request('eid'));
        $da1 = '2018-01-01';
        $da2 = '2018-12-30';
        $date1 = Carbon::createFromFormat('Y-m-d H:i', $da1 . '00:00');
        $date2 = Carbon::createFromFormat('Y-m-d H:i', $da2 . '00:00');

        if ($debug) {
            echo "Event[$event->id]: $event->name<br>";
            echo "From:" . $date1->format('d/m/Y') . " - " . $date2->format('d/m/Y') . "<br>";
            echo "Mon:" . $date1->startOfWeek()->format('d/m/Y') . ' - ' . $date2->startOfWeek()->format('d/m/Y') . '<br>';
            echo "<h2>Dates</h2>";
        }

        $loop_date = Carbon::createFromFormat('Y-m-d H:i', $da1 . '00:00');
        $chartdata = [];
        while ($loop_date->lt($date2)) {
            $d1 = Carbon::parse($loop_date->format('Y-m-d'))->startOfWeek();
            $d2 = Carbon::parse($loop_date->format('Y-m-d'))->endOfWeek();
            $week = $d1->format('M j'); // . ' - ' . $d2->format('d/m');
            //echo "$week: " . $loop_date->startOfWeek()->format('Y-m-d') . "-" . $loop_date->endOfWeek()->format('Y-m-d') . ".<br>";
            $instance_ids = $event->betweenDates($d1->format('Y-m-d'), $d2->format('Y-m-d'))->pluck('id')->toArray();
            if ($debug) print_r($instance_ids);
            $attendance = Attendance::whereIn('eid', $instance_ids)->get();
            $a = $b = 0;
            $b = 4;
            if ($attendance) {
                if ($debug) echo "<br>Getting attendance<br>";
                foreach ($attendance as $attend) {
                    if ($debug) echo "$attend->id : " . $attend->person->type . " : " . $attend->person->name . "<br>";
                    if ($attend->person->isStudent()) {
                        $a ++;
                    }
                    if ($attend->person->isVolunteer())
                        $b ++;
                }
            }
            //echo "DDD: $a:$b<br>";

            $chartdata[] = ['y' => $week, 'a' => $a, 'b' => $b];
            $loop_date->addWeek();

        }

        return json_encode($chartdata);
        //if (request()->ajax()) {

        /*
         * { y: '2006', a: 100, b: 90 },
            { y: '2007', a: 75,  b: 65 },
            { y: '2008', a: 50,  b: 40 },
            { y: '2009', a: 75,  b: 65 },
            { y: '2010', a: 50,  b: 40 },
            { y: '2011', a: 75,  b: 65 },
            { y: '2012', a: 100, b: 90 }
         */
        $chartdata2 = array(array('y' => "2011", 'a' => 20, 'b' => 60), array('y' => "2012", 'a' => 18, 'b' => 21), array('y' => "2013", 'a' => 32, 'b' => 74), array('y' => "2014", 'a' => 38, 'b' => 63));

        echo json_encode($chartdata);
        echo "<h3>orig</h3>";
        echo json_encode($chartdata2);
        //return json_encode($chartdata);

        //$range = CarbonCarbon::now()->subDays(30);
        /*
                $stats = DB::table('events_attendance')
                    ->whereIn('eid', '>=', $instance_ids)
                    ->groupBy('eid')
                    ->orderBy('date', 'ASC')
                    ->get([
                        DB::raw('Date(in) as date'),
                        DB::raw('COUNT(*) as value')
                    ])
                    ->toJSON();

                //}
        */

        //return abort(404);
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
