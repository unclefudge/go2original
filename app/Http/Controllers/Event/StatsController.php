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

        $loop_date = Carbon::now()->subYear();
        $chartdata = [];
        while ($loop_date->lt($date2)) {
            $d1 = Carbon::parse($loop_date->format('Y-m-d'))->startOfWeek();
            $d2 = Carbon::parse($loop_date->format('Y-m-d'))->endOfWeek();
            $week = $d1->format('M j');

            $instance_ids = $event->betweenUTCDates($d1->format('Y-m-d'), $d2->format('Y-m-d'))->pluck('id')->toArray();
            if ($debug) print_r($instance_ids);
            $attendance = Attendance::whereIn('eid', $instance_ids)->get();
            $student = $new = 0;
            if ($attendance) {
                if ($debug) echo "<br>Getting attendance<br>";
                foreach ($attendance as $attend) {
                    if ($debug) echo "$attend->eid:$attend->id : " . $attend->person->type . " : [" . $attend->person->id . '] ' . $attend->person->name . "<br>";
                    if ($attend->person->isStudent) {
                        if ($attend->person->firstEvent($event->id)->start->isSameDay($attend->instance->start))
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
                $instance_ids = $event->betweenUTCDates("$year-$month-01", "$year-$month-$days")->pluck('id')->toArray();

                $avg = 0;
                if (count($instance_ids)) {
                    $attendance = Attendance::whereIn('eid', $instance_ids)->get();
                    $student = 0;
                    if ($attendance) {
                        foreach ($attendance as $attend)
                            if ($attend->person->isStudent) $student ++;
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
        }

        // Create data array for chart
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
     * Attendance Stats for individual Student
     */
    public function studentAttendance($eid, $pid)
    {
        $event = Event::findOrFail($eid);
        $people = People::findOrFail($pid);
        $firstDate = ($people->firstEvent($eid)) ? Carbon::createFromFormat('Y-m-d H:i', $people->firstEvent($eid)->start->format('Y-m-d') . '00:00') : '';
        //echo $firstDate;
        $stats = [];
        if ($firstDate) {
            $stats['attended_first'] = $people->firstEvent($eid)->start->diffForHumans();
            $stats['attended_last'] = $people->lastEvent($eid)->start->diffForHumans();

            // Last Month
            $weeks = 4;
            $date1 = Carbon::now()->subWeeks($weeks);
            $from = ($date1->lt($firstDate)) ? $firstDate : $date1;
            $now = Carbon::now();

            $instances = $event->betweenUTCDates($from->format('Y-m-d'), $now->format('Y-m-d'));
            $count = 0;
            foreach ($instances as $instance) {
                if ($instance->personAttend($pid))
                    $count ++;
            }
            $stats['attended_month'] = "$count of " . count($instances);
            if ($count)
                $stats['attended_month'] .= (count($instances)) ? " &nbsp; (" . round($count / count($instances) * 100, 0, PHP_ROUND_HALF_UP) . '%)' : "0%";

            // Last Year
            $weeks = 52;
            $date1 = Carbon::now()->subWeeks($weeks);
            $from = ($date1->lt($firstDate)) ? $firstDate : $date1;
            $now = Carbon::now();

            $instances = $event->betweenUTCDates($from->format('Y-m-d'), $now->format('Y-m-d'));
            $count = 0;
            foreach ($instances as $instance) {
                if ($instance->personAttend($pid))
                    $count ++;
            }
            $stats['attended_year'] = "$count of " . count($instances);
            if ($count)
                $stats['attended_year'] .= (count($instances)) ? " &nbsp; (" . round($count / count($instances) * 100, 0, PHP_ROUND_HALF_UP) . '%)' : "0%";

        } else {
            $stats['attended_first'] = 'Never attended';
            $stats['attended_last'] = $stats['attended_month'] = $stats['attended_year'] = '-';
        }

        return $stats;
    }
}
