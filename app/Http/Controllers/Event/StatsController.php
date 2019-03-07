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
     * Update the specified resource in storage.
     */
    public function weekTotals()
    {
        //$event = Event::findOrFail(2);
        $event = Event::find(request('eid'));
        $debug = false;
        //$event = Event::findOrFail(request('eid'));
        //$da1 = '2018-01-01';
        //$da2 = '2018-12-30';
        //$date1 = Carbon::createFromFormat('Y-m-d H:i', $da1 . '00:00');
        //$date2 = Carbon::createFromFormat('Y-m-d H:i', $da2 . '00:00');
        $date1 = Carbon::now()->subYear();
        $date2 = Carbon::now();

        if ($debug) {
            echo "Event[$event->id]: $event->name<br>";
            echo "From:" . $date1->format('d/m/Y') . " - " . $date2->format('d/m/Y') . "<br>";
            echo "Mon:" . $date1->startOfWeek()->format('d/m/Y') . ' - ' . $date2->startOfWeek()->format('d/m/Y') . '<br>';
            echo "<h2>Dates</h2>";
        }

        //$loop_date = Carbon::createFromFormat('Y-m-d H:i', $da1 . '00:00');
        $loop_date = Carbon::now()->subYear();
        $chartdata = [];
        while ($loop_date->lt($date2)) {
            $d1 = Carbon::parse($loop_date->format('Y-m-d'))->startOfWeek();
            $d2 = Carbon::parse($loop_date->format('Y-m-d'))->endOfWeek();
            $week = $d1->format('M j'); // . ' - ' . $d2->format('d/m');
            //echo "$week: " . $loop_date->startOfWeek()->format('Y-m-d') . "-" . $loop_date->endOfWeek()->format('Y-m-d') . ".<br>";
            $instance_ids = $event->betweenDates($d1->format('Y-m-d'), $d2->format('Y-m-d'))->pluck('id')->toArray();
            if ($debug) print_r($instance_ids);
            $attendance = Attendance::whereIn('eid', $instance_ids)->get();
            $student = $new = 0;
            if ($attendance) {
                if ($debug) echo "<br>Getting attendance<br>";
                foreach ($attendance as $attend) {
                    if ($debug) echo "$attend->eid:$attend->id : " . $attend->person->type . " : [" . $attend->person->id . '] ' . $attend->person->name . "<br>";
                    if ($attend->person->isStudent) {
                        if ($attend->person->firstEvent($event->id)->start->timezone(session('tz'))->format('Y-m-d') == $attend->instance->start->timezone(session('tz'))->format('Y-m-d'))
                            $new ++;
                        else
                            $student ++;
                    }
                }
            }
            //echo "DDD: $a:$b<br>";

            $chartdata[] = ['y' => $week, 'a' => $student, 'b' => $new];
            $loop_date->addWeek();
        }

        return json_encode($chartdata);
    }

    /**
     * Update the specified resource in storage.
     */
    public function compareYear($years)
    {
        $event = Event::findOrFail(request('eid'));
        $debug = false;
        //$event = Event::findOrFail(request('eid'));
        //$da1 = '2018-01-01';
        //$da2 = '2018-12-30';
        //$date1 = Carbon::createFromFormat('Y-m-d H:i', $da1 . '00:00');
        //$date2 = Carbon::createFromFormat('Y-m-d H:i', $da2 . '00:00');
        $currentYear = Carbon::now()->format('Y');
        $date1 = Carbon::createFromFormat('Y-m-d H:i', $currentYear . '-01-01 00:00')->subYears($years - 1);
        $date2 = Carbon::createFromFormat('Y-m-d H:i', $currentYear . '-12-30 00:00');

        if ($debug) {
            echo "Event[$event->id]: $event->name<br>";
            echo "From:" . $date1->format('d/m/Y') . " - " . $date2->format('d/m/Y') . "<br>";
            echo "<h2>Dates</h2>";
        }

        $loop_date = Carbon::createFromFormat('Y-m-d H:i', $currentYear . '-01-01 00:00')->subYears($years - 1);
        $month_stats = [];
        for ($x = 0; $x < $years; $x ++) {
            for ($m = 1; $m <= 12; $m ++) {
                $year = $loop_date->format('Y');
                $month = str_pad($m, 2, '0', STR_PAD_LEFT);
                $days = Carbon::parse($loop_date->format('Y-m-d'))->endOfMonth()->format('d');
                if ($debug) echo "Getting data $year-$month  ($days)<br>";
                $instance_ids = $event->betweenDates("$year-$month-01", "$year-$month-$days")->pluck('id')->toArray();
                //if ($debug) print_r($instance_ids);
                $avg = 0;
                if (count($instance_ids)) {
                    $attendance = Attendance::whereIn('eid', $instance_ids)->get();
                    $student = 0;
                    if ($attendance) {
                        foreach ($attendance as $attend) {
                            //if ($debug) echo "$attend->eid:$attend->id : " . $attend->person->type . " : [" . $attend->person->id . '] ' . $attend->person->name . "<br>";
                            if ($attend->person->isStudent) $student ++;
                        }
                    }

                    // Average attendence by amount of events that month
                    $avg = round($student / count($instance_ids), 0, PHP_ROUND_HALF_UP);
                }
                if (isset($monthly_stats[$year]))
                    $monthly_stats[$year] .= "," . $avg;
                else
                    $monthly_stats[$year] = "," . $avg;


                $loop_date->addMonth();
            }
            //$chartdata[] = ['y' => $week, 'a' => $student, 'b' => $new];
        }

        if ($debug) print_r($monthly_stats);
        $chartdata = [];
        $months = ['1' => 'Jan', '2' => 'Feb', '3' => 'Mar', '4' => 'Apr', '5' => 'May', '6' => 'Jun', '7' => 'Jul', '8' => 'Aug', '9' => 'Sep', '10' => 'Oct', '11' => 'Nov', '12' => 'Dec'];
        foreach ($months as $month => $name) {
            $stats = [];
            foreach (array_keys($monthly_stats) as $key)
                $stats[] = explode(',', $monthly_stats[$key]);

            if ($years == 3)
                $chartdata[] = ['y' => $name, 'a' => $stats[0][$month], 'b' => $stats[1][$month], 'c' => $stats[2][$month]];

            if ($years == 5)
                $chartdata[] = ['y' => $name, 'a' => $stats[0][$month], 'b' => $stats[1][$month], 'c' => $stats[2][$month], 'd' => $stats[3][$month], 'e' => $stats[4][$month]];

        }

        return json_encode($chartdata);
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
                    if ($attend->person->isStudent) {
                        $a ++;
                    }
                    if ($attend->person->isVolunteer)
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

}
