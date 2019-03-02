<?php

namespace App\Http\Controllers\Misc;

use Auth;
use App\Models\Account\Account;
use App\Models\People\People;
use App\Models\People\School;
use App\Models\People\Household;
use App\Models\Event\Event;
use App\Models\Event\EventInstance;
use App\Models\Event\Attendance;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReportController extends Controller {

    public function __construct()
    {
        $this->middleware('auth');
    }

    function outdatedStudents()
    {
        $people = People::all()->sortby('name');

        echo "<b>Outdated Active Students<b>";
        echo "<table><tr><td width='150'>Name</td><td width=100>No. Attendence</td><td width=400>Last Attendance</td><td>Grade</td><td>Address</td></tr>";
        foreach ($people as $person) {
            if ($person->status && $person->isStudent()) {
                $attend_last = Attendance::where('pid', $person->id)->orderBy('in', 'desc')->first();
                if ($attend_last && $attend_last->in->lt(Carbon::now()->subMonths(6))) {
                    $attend_last = $attend_last->in->format('d/m/Y');
                    $attend_count = Attendance::where('pid', $person->id)->count();

                    if ($attend_count < 5) {
                        echo "<tr style='background:#ccc'><td><a href='/people/$person->id' target='_blank'>$person->firstname $person->lastname</a></td>";
                        echo "<td>$attend_count</td><td>$attend_last</td><td>$person->grade<td>$person->address, $person->suburb, $person->state</td></tr>";
                    }
                }
            }
        }
        echo "</table>";
    }
}