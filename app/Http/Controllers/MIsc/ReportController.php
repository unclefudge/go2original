<?php

namespace App\Http\Controllers\Misc;

use Auth;
use App\Models\Account\Account;
use App\User;
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
        $users = User::all()->sortby('name');

        echo "<h3>Outdated Active Students</h3>";
        echo "Attended less then 5 times and last was over 6 months ago<br><br>";
        echo "<table><tr><td width='150'>Name</td><td width=100>No. Attendence</td><td width=400>Last Attendance</td><td>Grade</td><td>Address</td></tr>";
        foreach ($users as $user) {
            if ($user->status && $user->isStudent) {
                $attend_last = Attendance::where('uid', $user->id)->orderBy('in', 'desc')->first();
                if ($attend_last && $attend_last->in->lt(Carbon::now()->subMonths(6))) {
                    $attend_last = $attend_last->in->format('d/m/Y');
                    $attend_count = Attendance::where('uid', $user->id)->count();

                    if ($attend_count < 5) {
                        echo "<tr style='background:#ccc'><td><a href='/people/$user->id' target='_blank'>$user->firstname $user->lastname</a></td>";
                        echo "<td>$attend_count</td><td>$attend_last</td><td>$user->grade<td>$user->address, $user->suburb, $user->state</td></tr>";
                    }
                }
            }
        }
        echo "</table>";
    }

    public function nightly()
    {
        $files = array_reverse(array_diff(scandir(storage_path('/app/log/nightly')), array('.', '..')));

        return view('report/nightly', compact('files'));
    }
}