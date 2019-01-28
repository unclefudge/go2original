<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;

class HomeController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }

    public function metronic()
    {
        return view('metronic');
    }

    public function fudge()
    {
        return view('fudge');
    }

    public function event()
    {
        return view('event/index');
    }
    public function group()
    {
        return view('group/index');
    }

    public function importStudents()
    {

        // Import Students
        echo "Importing Students<br><br>";
        $account = \App\Models\Account\Account::create(['name' => 'Young Life Hobart', 'slug' => 'yl'])->first();
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

                $school = \App\Models\People\School::where('name', $school_name)->first();
                if (!$school)
                    $school = \App\Models\People\School::create(['name' => $school_name, 'aid' => 1])->first();

                $people = \App\Models\People\People::where('firstname', $firstname)->where('lastname', $lastname)->first();
                if (!$people) {
                    $people = \App\Models\People\People::create(
                        ['firstname' => $firstname, 'lastname' => $lastname, 'dob' => $dob, 'gender' => $gender, 'email' => $email,
                         'school_id' => $school->id, 'grade' => $grade, 'status' => $status, 'minhub' => $minhub, 'type' => 'Student', 'aid' => $account->id,])->first();
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
        //$account = \App\Models\Account\Account::create(['name' => 'Young Life Hobart', 'slug' => 'yl'])->first();
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
                $parent = ($data[16] == 'YES') ? 0 : 1;
                $staff = ($data[17] == 'YES') ? 0 : 1;

                echo "<br>$minhub -  $firstname - $lastname - $dob - $gender - $phone - $email - $school_name - $grade - $status<br>";

                $school = \App\Models\People\School::where('name', $school_name)->first();
                if (!$school)
                    $school = \App\Models\People\School::create(['name' => $school_name, 'aid' => 1])->first();

                $people = \App\Models\People\People::where('firstname', $firstname)->where('lastname', $lastname)->first();
                if (!$people) {
                    $people = \App\Models\People\People::create(
                        ['firstname' => $firstname, 'lastname' => $lastname, 'dob' => $dob, 'gender' => $gender, 'email' => $email,
                         'school_id' => $school->id, 'grade' => $grade, 'status' => $status, 'minhub' => $minhub, 'type' => 'Student', 'aid' => $account->id,])->first();
                }
            }
            fclose($handle);
        }
        echo "<br><br>Completed<br>-------------<br>";
    }
}
