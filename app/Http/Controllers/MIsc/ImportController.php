<?php

namespace App\Http\Controllers\Misc;

use DB;
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

class ImportController extends Controller {

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function importStudents()
    {

        // Import Students
        echo "Importing Students<br><br>";
        //$account = \App\Models\Account\Account::create(['name' => 'Young Life Hobart', 'slug' => 'yl'])->first();
        $school = School::where('name', 'Other')->first();
        if (!$school)
            $other_school = School::create(['name' => 'Other', 'grade_from' => 1, 'grade_to' => 12]);

        $row = 0;
        if (($handle = fopen(public_path("students.csv"), "r")) !== false) {
            while (($data = fgetcsv($handle, 5000, ",")) !== false) {
                $row ++;
                if ($row == 1) continue;
                $num = count($data);

                $minhub = $data[0];
                $firstname = $data[1];
                $lastname = $data[2];
                $nickname = $data[3];
                $dob = Carbon::parse($data[4]);
                if ($data[5] == 'male') $gender = 'Male';
                if ($data[5] == 'female') $gender = 'Female';
                if ($data[5] == 'not selected') $gender = '';
                $phone = $data[6];
                $texts = $data[7];
                $email = (validEmail($data[8])) ? $data[8] : null;
                $created = $data[9];
                $status = ($data[10] == 'YES') ? 0 : 1;
                $school_name = $data[11];
                $grade = $data[12];

                echo "<br>$minhub -  $firstname - $lastname - $dob - $gender - $phone - $email - $school_name - $grade - $status<br>";

                $school = School::where('name', $school_name)->first();
                if (!$school)
                    $school = School::create(['name' => $school_name, 'grade_from' => 1, 'grade_to' => 12])->first();

                $people = People::where('firstname', $firstname)->where('lastname', $lastname)->first();
                if (!$people) {
                    $people = People::create(
                        ['firstname' => $firstname, 'lastname' => $lastname, 'dob' => $dob, 'gender' => $gender, 'email' => $email,
                         'school_id' => $school->id, 'grade' => $grade, 'status' => $status, 'minhub' => $minhub, 'type' => 'Student']);
                }
            }
            fclose($handle);
        }
        echo "<br><br>Completed<br>-------------<br>";
    }

    public function importAdults()
    {
        // Import Adults
        echo "Importing Adults<br><br>";
        $account = Account::findOrFail(session('aid'));
        $row = 0;
        if (($handle = fopen(public_path("adults.csv"), "r")) !== false) {
            while (($data = fgetcsv($handle, 5000, ",")) !== false) {
                $row ++;
                if ($row == 1) continue;
                $num = count($data);

                $minhub = $data[0];
                $firstname = $data[1];
                $lastname = $data[2];
                $nickname = $data[3];
                $dob = Carbon::parse($data[4]);
                if ($data[5] == 'male') $gender = 'Male';
                if ($data[5] == 'female') $gender = 'Female';
                if ($data[5] == 'not selected') $gender = '';
                $phone = $data[6];
                $texts = $data[7];
                $email = (validEmail($data[8])) ? $data[8] : null;
                $created = $data[9];
                $status = ($data[10] == 'YES') ? 0 : 1;
                $address = $data[11];
                $address2 = $data[12];
                $suburb = $data[13];
                $state = 'TAS'; //$data[14];
                $postcode = $data[15];
                $parent = ($data[16] == 'YES') ? 1 : 0;
                $staff = ($data[17] == 'YES') ? 1 : 0;
                if ($parent && $staff) $type = 'Parent/Volunteer';
                if ($parent && !$staff) $type = 'Parent';
                if (!$parent && $staff) $type = 'Volunteer';

                echo "<br>$minhub -  $firstname - $lastname - $dob - $gender - $phone - $email - $address - $suburb - $type - $status<br>";

                $people = People::where('firstname', $firstname)->where('lastname', $lastname)->first();
                if (!$people) {
                    $people = People::create(
                        ['firstname' => $firstname, 'lastname' => $lastname, 'dob' => $dob, 'gender' => $gender, 'email' => $email,
                         'address'   => $address, 'suburb' => $suburb, 'state' => $state, 'postcode' => $postcode, 'status' => $status, 'minhub' => $minhub, 'type' => $type, 'aid' => session('aid')]);
                }
            }
            fclose($handle);
        }
        echo "<br><br>Completed<br>-------------<br>";
    }

    public function importEvents()
    {
        // Import Events
        echo "Importing Events<br><br>";
        $account = Account::findOrFail(session('aid'));
        $row = 0;
        if (($handle = fopen(public_path("events.csv"), "r")) !== false) {
            while (($data = fgetcsv($handle, 5000, ",")) !== false) {
                $row ++;
                if ($row == 1) continue;
                $num = count($data);


                // Carbon::createFromFormat(session('df') . ' H:i', request('dob') . '00:00')->toDateTimeString()
                $minhub = $data[0];
                $instance_name = $data[1];
                $event_name = $data[2];
                // Start date - skip bad dates
                if ($data[3]) {
                    $split = explode(' ', $data[3]);
                    $date = ($split[0]) ? $split[0] : '';
                    $time = ($split[1]) ? $split[1] : '';
                    //echo "$date $time<br>";
                    $start = (preg_match('/[0-9][0-9]\/[0-9][0-9]\/[0-9][0-9][0-9][0-9]/', $date)) ? Carbon::createFromFormat('m/d/Y H:i', $date . '00:00')->timezone(session('tz'))->toDateTimeString() : null;
                } else
                    $start = '';

                //$start = ($data[3]) ? Carbon::parse($data[3]) : null; //$data[3]; //Carbon::parse($data[3]);
                $end = $data[4]; //Carbon::parse($data[4]);
                $collection_id = $data[5];
                $repeat_date = $data[6]; //Carbon::parse($data[6]);
                $repeat_int = $data[7];
                $students = explode(';', $data[8]);
                $adults = explode(';', $data[9]);

                if (!$event_name || !$start) {
                    echo "*** BAD DATA ***<br>";
                    echo "$minhub -  $event_name - $instance_name - $start<br><br>";
                } else {
                    // Find or Create Event
                    $event = Event::where('name', $event_name)->where('aid', session('aid'))->first();
                    if (!$event)
                        $event = Event::create(['name' => $event_name, 'recur' => 0, 'start' => $start, 'status' => 1]);
                    else {
                        // recurring event
                        $event->recur = 1;
                        $event->save();
                    }

                    // Create Event Instance
                    $instance_name = ($instance_name) ? $instance_name : $event_name; // Some Instances didn't have name so use Event instead
                    $instance = EventInstance::where('eid', $event->id)->where('start', $start)->where('aid', session('aid'))->first();
                    if (!$instance)
                        $instance = EventInstance::create(['name' => $instance_name, 'start' => $start, 'status' => 1, 'eid' => $event->id]);

                    // Add Student attendance to event
                    foreach ($students as $student) {
                        $person = People::where('minhub', $student)->first();
                        if ($person) {
                            // Check person into event
                            $attend = Attendance::where('eid', $instance->id)->where('pid', $person->id)->first();
                            if (!$attend)
                                $attend = Attendance::create(['eid' => $instance->id, 'pid' => $person->id, 'in' => $start, 'method' => 'imported']);
                        }
                    }
                }
            }
            fclose($handle);
        }
        echo "<br><br>Completed<br>-------------<br>";
    }

    public function formHouseholds()
    {
        $people = People::all()->sortby('lastname');

        echo "<h1>Form Households<h1>";
        echo "<table><tr><td width=50>#H</td><td width='200'>Last name</td><td width='150'>First name</td><td width=100>Type</td><td width=60>Grade</td><td>Address</td></tr>";
        foreach ($people as $person) {
            if ($person->type != 'Volunteer') {
                $person->address = trim($person->address);
                $person->suburb = trim($person->suburb);
                $person->postcode = trim($person->postcode);
                $person->state = ($person->suburb || $person->postcode || $person->address) ? 'TAS' : '';
                if (!$person->postcode) {
                    if ($person->suburb == 'CLARENDON VALE') $person->postcode = '7019';
                    if ($person->suburb == 'ROKEBY') $person->postcode = '7019';
                    if ($person->suburb == 'WEST MOONAH') $person->postcode = '7009';
                    if ($person->suburb == 'MIDWAY POINT') $person->postcode = '7171';
                    if ($person->suburb == 'GAGEBROOK') $person->postcode = '7030';
                    if ($person->suburb == 'KINGSTON') $person->postcode = '7050';
                    if ($person->suburb == 'GLENORCHY') $person->postcode = '7030';
                    if ($person->suburb == 'SANDFORD') $person->postcode = '7020';
                    if ($person->suburb == 'HOWRAH') $person->postcode = '7018';
                    if ($person->suburb == 'HONEYWOOD') $person->postcode = '7010';
                    if ($person->suburb == 'CLARENCE CITY') $person->postcode = '7018';
                    if ($person->suburb == 'LENAH VALLEY') $person->postcode = '7008';
                    if ($person->suburb == 'PONTVILLE') $person->postcode = '7030';
                    if ($person->suburb == 'RISDON VALE') $person->postcode = '7018';
                    if ($person->suburb == 'TRANMERE') $person->postcode = '7030';
                    if ($person->suburb == 'AUSTINS FERRY') $person->postcode = '7011';
                    if ($person->suburb == 'LINDISFARNE') $person->postcode = '7015';
                    if ($person->suburb == 'TAROONA') $person->postcode = '7053';
                }
                $person->save();

                $house = ($person->households->count() > 0) ? 'Y' : '';
                $bg = ($person->status) ? '#eee' : '#aaa';
                $bg2 = ($house) ? '#FFFF00' : $bg;
                echo "<tr style='background: $bg'><td style='text-align:center; background: $bg2'>$house</td><td>$person->lastname</td><td><a href='/people/$person->id' target='_blank'>$person->firstname</a></td>
            <td>$person->type</td><td>$person->grade</td><td>$person->address, $person->suburb $person->postcode</td></tr>";
            }
        }
        echo "</table>";
    }

    public function copyAddress()
    {
        $households = Household::all()->sortby('name');

        echo "<h1>Copy Household Address<h1>";
        echo "<table><tr><td width='150'>Name</td><td width=100>Type</td><td width=400>Address</td><td>Copy address from</td></tr>";
        foreach ($households as $household) {
            echo "<tr style='background:#000; color:#fff'><td width='250' colspan=4>$household->name</td></tr>";

            foreach ($household->members as $member) {
                $bg = ($member->status) ? '#eee' : '#aaa';
                echo "<tr style='background: $bg'><td><a href='/people/$member->id' target='_blank'>$member->firstname $member->lastname</a></td>
                <td>$member->type</td><td>$member->address, $member->suburb, $member->state</td><td>";

                foreach ($household->members as $m) {
                    echo "<a href='/copy-address/$m->id/$member->id' target='_blank'>$m->firstname</a>, &nbsp; ";
                }
                echo "</td></tr>";
            }
        }
        echo "</table>";
    }

    public function copyAddressDone($from, $to)
    {
        $from = People::find($from);
        $to = People::find($to);

        echo "<h3>Copied address from $from->firstname to $to->firstname</h3>";
        if (!$to->address) $to->address = $from->address;
        if (!$to->suburb) $to->suburb = $from->suburb;
        if (!$to->postcode) $to->postcode = $from->postcode;
        if (!$to->state) $to->state = $from->state;

        $to->save();
    }

    public function quick()
    {
        /*
        echo "<h3>Fix double attendance</h3>";
        $x = 0;
        $attendance = Attendance::all();
        foreach($attendance as $attend) {
            $at = Attendance::where('eid', $attend->eid)->where('pid', $attend->pid)->get();
            if ($at->count() > 1) {
                echo $attend->instance->name . " : " . $attend->person->name . "<br>";
                $attend->delete();
                $x++;
            }
        }
        echo "<br>Count: $x<br>";
        */
    }
}