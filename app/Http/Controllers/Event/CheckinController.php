<?php

namespace App\Http\Controllers\Event;

use DB;
use Auth;
use Validator;
use App\Models\Event\Event;
use App\Models\Event\EventInstance;
use App\Models\Event\Attendance;
use App\Models\People\People;
use App\Models\People\Household;
use Carbon\Carbon;
use Camroncade\Timezone\Facades\Timezone;
use Yajra\Datatables\Datatables;
use Kamaln7\Toastr\Facades\Toastr;
use Jenssegers\Agent\Agent;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CheckinController extends Controller {

    /**
     * Display the specified resource.
     *
     */
    public function show($id)
    {
        $event = Event::findOrFail($id);
        $nowLocal = Carbon::now()->timezone(session('tz'))->format('Y-m-d');
        $instance = EventInstance::existingLocalDate($nowLocal, $id);

        if (!$instance) {
            $instance = EventInstance::create([
                'name'       => $event->name,
                'start'      => Carbon::now()->toDateTimeString(),
                'code'       => $event->code,
                'grades'     => $event->grades,
                'background' => $event->background,
                'eid'        => $event->id,
                'aid'        => $event->aid
            ]);
        }

        return view('checkin/index', compact('event', 'instance'));
    }

    /**
     * Show the form for Registering a Student.
     */
    public function studentForm($id)
    {
        $instance = EventInstance::findOrFail($id);

        return view('checkin/register_student', compact('instance'));
    }

    /**
     * Show the form for Registering a Volunteer.
     */
    public function volunteerForm($id)
    {
        $instance = EventInstance::findOrFail($id);

        return view('checkin/register_volunteer', compact('instance'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function studentRegister($id)
    {
        $instance = EventInstance::findOrFail($id);
        $agent = new Agent();

        // Validate
        $rules = [
            'firstname'        => 'required',
            'lastname'         => 'required',
            'dob'              => 'sometimes|nullable|date_format:' . session('df'),
            'parent_id'        => 'required|not_in:0',
            'parent_firstname' => 'required_if:parent_id,add',
            'parent_lastname'  => 'required_if:parent_id,add',
            'parent_phone'     => 'required_if:parent_id,add',
            'parent_email'     => 'required_if:parent_id,add',
        ];

        // Require photo for tablets/mobile devices only
        if (!$agent->isDesktop())
            $rules['photo'] = 'required';

        $mesgs = [
            'firstname.required'           => 'The first name is required.',
            'lastname.required'            => 'The last name is required.',
            'photo.required'               => 'The photo is required.',
            'dob.date_format'              => 'The birthday format needs to be ' . session('df-datepicker'),
            'parent_id.required'           => 'The parent / guardian name is required.',
            'parent_id.not_in'             => 'The parent / guardian name is required.',
            'parent_firstname.required_if' => 'The parents first name is required.',
            'parent_lastname.required_if'  => 'The parents last name is required.',
            'parent_phone.required_if'     => 'The parents phone is required.',
            'parent_email.required_if'     => 'The parents email is required.',
        ];
        request()->validate($rules, $mesgs);

        $people_request = request()->except('photo', 'parent_id');
        $people_request['type'] = "Student";

        // Empty State field if rest of address fields are empty
        if (!request('address') && !request('suburb') && !request('postcode'))
            $people_request['state'] = null;

        $people_request['dob'] = (request('dob')) ? Carbon::createFromFormat(session('df') . ' H:i', request('dob') . '00:00')->toDateTimeString() : null;
        //dd($people_request);

        // Create Student
        $student = People::create($people_request);

        // Handle attached photo
        if (request()->photo)
            $student->attachPhoto();

        // Check student into event
        $student->checkin($instance);

        //
        // Put Student + Parent into same household
        //
        if (request('parent_id') != 'add') {
            // Existing Parent
            $parent = People::findOrFail(request('parent_id'));

            // If Parent has single house add student to it else create new one
            if ($parent->households->count() == 1) {
                DB::table('households_people')->insert(['hid' => $parent->households->first()->id, 'pid' => $student->id]);
            } else {
                // Create new household and put student + parent in
                $household = Household::create(['name' => $parent->lastname . ' Household', 'pid' => $parent->id]);
                DB::table('households_people')->insert(['hid' => $household->id, 'pid' => $student->id]);
                DB::table('households_people')->insert(['hid' => $household->id, 'pid' => $parent->id]);
            }
        } else {
            // Create new parent profile
            $parent = People::create([
                'type'      => 'Parent',
                'firstname' => request('parent_firstname'),
                'lastname'  => request('parent_lastname'),
                'phone'     => request('parent_phone'),
                'email'     => request('parent_email'),
                'address'   => $student->address,
                'suburb'    => $student->suburb,
                'state'     => $student->state,
                'postcode'  => $student->postcode,
            ]);

            // Create new household and put student + parent in
            $household = Household::create(['name' => $parent->lastname . ' Household', 'pid' => $parent->id]);
            DB::table('households_people')->insert(['hid' => $household->id, 'pid' => $student->id]);
            DB::table('households_people')->insert(['hid' => $household->id, 'pid' => $parent->id]);
        }

        Toastr::success("Student created");

        return redirect("/checkin/$instance->eid");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function volunteerRegister($id)
    {
        $instance = EventInstance::findOrFail($id);

        // Validate
        $rules = [
            'firstname' => 'required', 'lastname' => 'required', 'dob' => 'sometimes|nullable|date_format:' . session('df'),
            'wwc_no'    => 'required', 'wwc_exp' => 'required|date_format:' . session('df')];
        $mesgs = [
            'firstname.required'  => 'The first name is required.',
            'lastname.required'   => 'The last name is required.',
            'dob.date_format'     => 'The birthday format needs to be ' . session('df-datepicker'),
            'wwc_no.required'     => 'The registration no. is required',
            'wwc_exp.required'    => 'The expiry is required',
            'wwc_exp.date_format' => 'The expiry format needs to be ' . session('df-datepicker'),
        ];
        request()->validate($rules, $mesgs);

        $people_request = request()->except('photo');
        $people_request['type'] = "Volunteer";

        // Empty State field if rest of address fields are empty
        if (!request('address') && !request('suburb') && !request('postcode'))
            $people_request['state'] = null;

        $people_request['dob'] = (request('dob')) ? Carbon::createFromFormat(session('df') . ' H:i', request('dob') . '00:00')->toDateTimeString() : null;
        $people_request['wwc_exp'] = (request('wwc_exp')) ? Carbon::createFromFormat(session('df') . ' H:i', request('wwc_exp') . '00:00')->toDateTimeString() : null;

        //dd($people_request);
        $people = People::create($people_request);

        // Handle attached photo
        if (request()->photo)
            $people->attachPhoto();

        // Check student into event
        $people->checkin($instance);

        Toastr::success("Volunteer created");

        return redirect("/checkin/$instance->eid");
    }


    /**
     * Get People (ajax)
     */
    public function getPeople($eid)
    {
        $people = People::where('status', 1)->where('aid', session('aid'))->orderBy('firstname')->get();
        $instance = EventInstance::find($eid);
        $people_array = [];
        foreach ($people as $person) {
            if ($person->isStudent || $person->isVolunteer) {
                $checked_in = $checked_in2 = null;
                $attended = Attendance::where('eid', $instance->id)->where('pid', $person->id)->first();
                if ($instance && $attended)
                    $checked_in = Timezone::convertFromUTC($attended->in, session('tz')); // Need to convert to local tz due to front-end moment.js
                $people_array[] = ['pid' => $person->id, 'name' => $person->name, 'type' => $person->type, 'in' => $checked_in, 'photo' => $person->photoSmPath, 'eid' => $instance->id];
            }
        }

        return $people_array;
    }

    /**
     * Get Parents (ajax)
     */
    public function getParents()
    {
        $people = People::where('status', 1)->where('aid', session('aid'))->orderBy('firstname')->get();
        $people_array = [];
        foreach ($people as $person) {
            if ($person->isParent) {
                $people_array[] = [
                    'pid'    => $person->id,
                    'name'   => $person->name,
                    'phone'  => $person->phone,
                    'email'  => $person->email,
                    'suburb' => $person->suburb,
                    'state'  => $person->state,
                    'photo'  => $person->photoSmPath
                ];

            }
        }

        return $people_array;
    }

    public function search()
    {
        $query = (!empty($_GET['q'])) ? strtolower($_GET['q']) : null;

        if (!isset($query)) {
            die('Invalid query.');
        }

        $status = true;
        $databaseUsers = array(
            array(
                "id"       => 4152589,
                "username" => "TheTechnoMan",
                "avatar"   => "https://avatars2.githubusercontent.com/u/4152589"
            ),
            array(
                "id"       => 7377382,
                "username" => "running-coder",
                "avatar"   => "https://avatars3.githubusercontent.com/u/7377382"
            ),
            array(
                "id"       => 748137,
                "username" => "juliocastrop",
                "avatar"   => "https://avatars3.githubusercontent.com/u/748137"
            ),
            array(
                "id"       => 619726,
                "username" => "cfreear",
                "avatar"   => "https://avatars0.githubusercontent.com/u/619726"
            ),
            array(
                "id"       => 5741776,
                "username" => "solevy",
                "avatar"   => "https://avatars3.githubusercontent.com/u/5741776"
            ),
            array(
                "id"       => 906237,
                "username" => "nilovna",
                "avatar"   => "https://avatars2.githubusercontent.com/u/906237"
            ),
            array(
                "id"       => 612578,
                "username" => "Thiago Talma",
                "avatar"   => "https://avatars2.githubusercontent.com/u/612578"
            ),
            array(
                "id"       => 2051941,
                "username" => "webcredo",
                "avatar"   => "https://avatars2.githubusercontent.com/u/2051941"
            ),
            array(
                "id"       => 985837,
                "username" => "ldrrp",
                "avatar"   => "https://avatars2.githubusercontent.com/u/985837"
            ),
            array(
                "id"       => 1723363,
                "username" => "dennisgaudenzi",
                "avatar"   => "https://avatars2.githubusercontent.com/u/1723363"
            ),
            array(
                "id"       => 2649000,
                "username" => "i7nvd",
                "avatar"   => "https://avatars2.githubusercontent.com/u/2649000"
            ),
            array(
                "id"       => 2757851,
                "username" => "pradeshc",
                "avatar"   => "https://avatars2.githubusercontent.com/u/2757851"
            )
        );

        $resultUsers = [];
        foreach ($databaseUsers as $key => $oneUser) {
            if (strpos(strtolower($oneUser["username"]), $query) !== false ||
                strpos(str_replace('-', '', strtolower($oneUser["username"])), $query) !== false ||
                strpos(strtolower($oneUser["id"]), $query) !== false
            ) {
                $resultUsers[] = $oneUser;
            }
        }

        $databaseProjects = array(
            array(
                "id"       => 1,
                "project"  => "jQuery Typeahead",
                "image"    => "http://www.runningcoder.org/assets/jquerytypeahead/img/jquerytypeahead-preview.jpg",
                "version"  => "1.7.0",
                "demo"     => 10,
                "option"   => 23,
                "callback" => 6,
            ),
            array(
                "id"       => 2,
                "project"  => "jQuery Validation",
                "image"    => "http://www.runningcoder.org/assets/jqueryvalidation/img/jqueryvalidation-preview.jpg",
                "version"  => "1.4.0",
                "demo"     => 11,
                "option"   => 14,
                "callback" => 8,
            )
        );

        $resultProjects = [];
        foreach ($databaseProjects as $key => $oneProject) {
            if (strpos(strtolower($oneProject["project"]), $query) !== false) {
                $resultProjects[] = $oneProject;
            }
        }

        // Means no result were found
        if (empty($resultUsers) && empty($resultProjects)) {
            $status = false;
        }

        header('Content-Type: application/json');

        echo json_encode(array(
            "status" => $status,
            "error"  => null,
            "data"   => array(
                "user"    => $resultUsers,
                "project" => $resultProjects
            )
        ));
    }
}
