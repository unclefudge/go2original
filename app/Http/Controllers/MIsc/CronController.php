<?php

namespace App\Http\Controllers\Misc;

use DB;
use Mail;
use File;
use Carbon\Carbon;
use App\User;
use App\Models\Account\Account;
use App\Models\People\People;
use App\Models\People\School;
use App\Models\People\Household;
use App\Models\Event\Event;
use App\Models\Event\EventInstance;
use App\Models\Event\Attendance;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CronController extends Controller {

    static public function nightly()
    {
        echo "<h1> Nightly Batch Job - " . Carbon::now()->format('d/m/Y g:i a') . "</h1>";
        $logfile = storage_path('app/log/nightly/' . Carbon::now()->format('Ymd') . '.txt');
        $log = "Nightly Batch Job\n--------------\n\n";
        $bytes_written = File::put($logfile, $log);
        if ($bytes_written === false) die("Error writing to file");

        CronController::cleanup();

        echo "<h1>ALL DONE - NIGHTLY COMPLETE</h1>";
        $log .= "\nALL DONE - NIGHTLY COMPLETE\n\n\n";

        $bytes_written = File::append($logfile, $log);
        if ($bytes_written === false) die("Error writing to file");
    }

    static public function verifyNightly()
    {
        $logfile = storage_path('app/log/nightly/' . Carbon::now()->format('Ymd') . '.txt');
        //echo "Log: $log<br>";
        if (strpos(file_get_contents($logfile), "ALL DONE - NIGHTLY COMPLETE") !== false) {
            //echo "successful";
            //Mail::to('support@openhands.com.au')->send(new \App\Mail\Misc\VerifyNightly("was Successful"));
        } else {
            //echo "failed";
            //Mail::to('support@openhands.com.au')->send(new \App\Mail\Misc\VerifyNightly("Failed"));
        }
    }

    /*
    * Add non-attendees to the non-compliant list
    */
    static public function cleanup()
    {
        $log = '';

        echo "<h2>Clean-up</h2>";
        $log .= "Deleting events old then 2 days with no attendees\n";
        $log .= "-------------------------------------------------------------------------\n\n";

        $events = EventInstance::all();
        foreach ($events as $event) {
            if ($event->attendance->count() < 1) {
                echo "Deleting [$event->id] $event->name (".$event->start->format('Y-m-d').")<br>";
                $event->delete();
            }
        }
    }
}