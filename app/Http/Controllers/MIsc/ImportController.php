<?php

namespace App\Http\Controllers\Misc;

use DB;
use Auth;
use App\Models\Account\Account;
use App\Models\People\People;
use App\Models\People\School;
use App\Models\People\Household;
use App\Models\People\PeopleHistory;
use App\Models\Event\Event;
use App\Models\Event\EventInstance;
use App\Models\Event\Attendance;
use Camroncade\Timezone\Facades\Timezone;
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
                    echo "$date $time<br>";
                    $start = (preg_match('/[0-9][0-9]\/[0-9][0-9]\/[0-9][0-9][0-9][0-9]/', $date)) ? Timezone::convertToUTC("$date $time:00", session('tz')) : null;
                } else
                    $start = '';

                $end = $data[4]; //Carbon::parse($data[4]);
                $collection_id = $data[5];
                $repeat_date = $data[6]; //Carbon::parse($data[6]);
                $repeat_int = $data[7];
                $students = explode(';', $data[8]);
                $adults = explode(';', $data[9]);

                echo "Event: $instance_name &nbsp; &nbsp; Date:$start<br>";
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
        echo "<h3>Insert profile history</h3>";
        $people = People::all();
        $fillable = [
            'type', 'gender', 'dob', 'email', 'phone', 'instagram',
            'grade', 'school_id', 'wwc_no', 'wwc_exp', 'wwc_verified', 'wwc_verified_by',
            'media_consent', 'media_consent_by', 'notes'];
        $address = ['address', 'address2', 'suburb', 'state', 'postcode', 'country'];
        $dates = ['dob', 'wwc_exp', 'wwc_verified', 'media_consent'];
        foreach ($people as $person) {
            $array = [];
            // Name
            if ($person->firstname || $person->lastname) {
                $array['Name'] = '';
                if ($person->firstname) $array['Name'] .= "$person->firstname";
                if ($person->lastname) $array['Name'] .= " $person->lastname";
            }

            // Address
            if ($person->address || $person->suburb || $person->state || $person->postcode) {
                $array['Address'] = '';
                if ($person->address) $array['Address'] .= "$person->address<br>";
                if ($person->suburb) $array['Address'] .= "$person->suburb, ";
                if ($person->state) $array['Address'] .= "$person->state ";
                if ($person->postcode) $array['Address'] .= "$person->postcode";
            }

            $ends = array('th','st','nd','rd','th','th','th','th','th','th');
            foreach ($fillable as $key) {
                $KEY = ucfirst($key);
                if ($key == 'dob') $KEY = 'Birthdate';
                if (isset($person[$key]) && $person[$key]) {
                    if (in_array($key, $dates))
                        $array[$KEY] = $person[$key]->format(session('df'));
                    elseif ($key == 'school_id')
                        $array['School'] = $person->school->name;
                    elseif ($key == 'grade') {
                        if (is_numeric($person->grade)) {
                            if ($person->grade > 12)
                                $array[$KEY] = "Young Adult";
                            elseif (($person->grade %100) >= 11 && ($person->grade %100) <= 13)
                                $array[$KEY] = $person->grade. 'th';
                            else
                                $array[$KEY] = $person->grade. $ends[$person->grade % 10];
                        } else
                            $array[$KEY] = $person->grade;
                    }
                    else
                        $array[$KEY] = $person[$key];
                }
            }
            echo "<br><br>$person->name<br>";
            /* {"1": {"after": "Clifton TAS 7000", "field": "Address", "before": "44 Church St"}, "2": {"after": "", "field": "Birthdate", "before": "1973-10-11"}} */
            print_r($array);
            $json = "{";
            $x=1;
            foreach ($array as $key => $val){
                $json .= '"'.($x++).'": {"field": "'.$key.'", "before": "", "after": "'.$val.'"}, ';
            }
            $json = rtrim($json, ', ');
            $json .= "}";
            echo "<br>$json<br>";
            $history = DB::table('people_history')->insert([
                'pid' => $person->id,
                'action' => 'created',
                'type' => 'profile',
                'subtype' => 'personal',
                'data' => $json,
                'created_by' => ($person->firstEvent() && $person->firstEvent()->start->lt(Carbon::now()->subWeeks(4))) ? 2 : 3,
                'created_at' => ($person->firstEvent() && $person->firstEvent()->start->lt($person->created_at)) ? $person->firstEvent()->start : $person->created_at,
                'updated_at' => ($person->firstEvent() && $person->firstEvent()->start->lt($person->created_at)) ? $person->firstEvent()->start : $person->updated_at,
            ]);
        }


        /*
                echo "<h3>Testing timezone out dates</h3>";
                $x = 0;
                $instances = EventInstance::where('eid', 1)->get();
                foreach ($instances as $instance) {
                    $tas = Carbon::createFromFormat('Y-m-d H:i', $instance->start->format('Y-m-d') . '19:00')->toDateTimeString();
                    $utc = Timezone::convertToUTC($tas, session('tz'));
                    echo "<br><br>$instance->name<br>O: " . $instance->start->toDateTimeString() . " L: $tas =>  U: $utc<br>";
                    echo "O: ".$instance->start->toDateTimeString() . " L: " . $instance->startLocal->toDateTimeString();
                }

                $d1 = Timezone::convertToUTC('2018-11-16 00:00:00', session('tz')); //Carbon::parse("2018-11-16 18:00:00");
                $d2 = Timezone::convertToUTC('2018-11-16 23:59:00', session('tz')); //Carbon::parse("2018-11-16 20:00:00");
                echo "<br><br><br>Between $d1 - $d2<br>";
                $instances = EventInstance::where('eid', 1)->whereBetween('start', [$d1, $d2])->get();
                //dd($instances->all());
                foreach ($instances as $instance) {
                    //echo "<br><br>$instance->name<br>O: " . $instance->start->toDateTimeString() ."<br>";
                    $tas = Carbon::createFromFormat('Y-m-d H:i', $instance->start->format('Y-m-d') . '19:00')->toDateTimeString();
                    $utc = Timezone::convertToUTC($tas, session('tz'));
                    echo "<br><br>$instance->name<br>O: " . $instance->start->toDateTimeString() . " L: $tas =>  U: $utc<br>";
                    echo "O: ".$instance->start->toDateTimeString() . " L: " . $instance->startLocal->toDateTimeString();
                }*/
        /*
        echo "<h3>Fix event dates</h3>";
        $x = 0;
        $instances = EventInstance::where('eid', 1)->get();
        foreach ($instances as $instance) {
            $tas = Carbon::createFromFormat('Y-m-d H:i', $instance->start->format('Y-m-d') . '19:00')->toDateTimeString();
            $utc = Timezone::convertToUTC($tas, session('tz'));
            echo "<br>$instance->name<br>" . $instance->start->toDateTimeString() . " => $tas =>  $utc<br>";
            $instance->start = $utc;
            $instance->save();
        }
        }*/

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