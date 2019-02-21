<?php

namespace App\Http\Controllers\Event;

use DB;
use Auth;
use Validator;
use App\Models\Event\Event;
use App\Models\Event\EventInstance;
use App\Models\Event\Attendance;
use App\Models\People\People;
use App\Http\Utilities\Slim;
use Carbon\Carbon;
use Yajra\Datatables\Datatables;
use Kamaln7\Toastr\Facades\Toastr;
use Intervention\Image\Facades\Image;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CheckinController extends Controller {

    /**
     * Display the specified resource.
     *
     */
    public function show($id)
    {
        $now_local = Carbon::now()->timezone(Auth::user()->timezone)->format('Y-m-d');
        $event = Event::findOrFail($id);
        $instance = EventInstance::where('eid', $id)->whereDate('start', $now_local)->first();

        if (!$instance) {
            $instance = EventInstance::create([
                'name'       => $event->name,
                'start'      => Carbon::now()->timezone(Auth::user()->timezone)->toDateTimeString(),
                'code'       => $event->code,
                'grades'     => $event->grades,
                'background' => $event->background,
                'eid'        => $event->id,
                'aid'        => $event->aid
            ]);
        }
        //echo($today_local);
        //dd($instance);

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
        echo 'volunteer';
    }

    /**
     * Store a newly created resource in storage.
     */
    public function studentRegister($id)
    {
        $instance = EventInstance::findOrFail($id);

        // Validate
        $rules = ['firstname' => 'required', 'lastname' => 'required', 'photo' => 'required'];
        $mesgs = [
            'firstname.required' => 'The first name is required.',
            'lastname.required'  => 'The last name is required.',
            'photo.required'     => 'The last name is required.',
        ];
        request()->validate($rules, $mesgs);

        $people_request = request()->except('photo');
        $people_request['aid'] = 1; // Auth::user()->aid;

        // Empty State field if rest of address fields are empty
        if (!request('address') && !request('suburb') && !request('postcode'))
            $people_request['state'] = null;

        $people_request['dob'] = (request('dob')) ? Carbon::createFromFormat(session('df') . ' H:i', request('dob') . '00:00')->toDateTimeString() : null;

        //dd(request()->all());
        //dd($people_request);
        $people = People::create($people_request);

        // Handle attached photo
        if (request()->photo) {
            // Pass Slim's getImages the name of your file input, and since we only care about one image, use Laravel's head() helper to get the first element
            $image = head(Slim::getImages('photo'));

            // Grab the ouput data (data modified after Slim has done its thing)
            if (isset($image['output']['data'])) {
                $name = $people->id . '.' . pathinfo($image['output']['name'], PATHINFO_EXTENSION);;   // Original file name = $image['output']['name'];
                $data = $image['output']['data'];  // Base64 of the image
                $path = storage_path('app/public/people/photos/');   // Server path
                $filepath = $path . $name;

                // Save the file to the server
                $file = Slim::saveFile($data, $name, $path, false);

                $people->photo = $name;
                $people->save();

                // Save the image as a thumbnail of 90x90 + 30x30
                if (exif_imagetype($filepath)) {
                    Image::make($filepath)->resize(90, 90)->save(storage_path('app/public/people/thumbs/t90-' . $name));
                    Image::make($filepath)->resize(50, 50)->save(storage_path('app/public/people/thumbs/t50-' . $name));
                } else
                    Toastr::error("Bad image");

            }
        }

        // Check student into event
        $attend = Attendance::create(['eid' => $instance->id, 'pid' => $people->id, 'in' => Carbon::now()->timezone(Auth::user()->timezone)->format('Y-m-d H:i:s')]);

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
        $rules = ['firstname' => 'required', 'lastname' => 'required'];
        $mesgs = [
            'firstname.required' => 'The first name is required.',
            'lastname.required'  => 'The last name is required.',
        ];
        request()->validate($rules, $mesgs);

        dd(request()->all());

        $people_request = request()->all();
        $people_request['aid'] = 1; // Auth::user()->aid;

        // Empty State field if rest of address fields are empty
        if (!request('address') && !request('suburb') && !request('postcode'))
            $people_request['state'] = null;

        $people_request['dob'] = (request('dob')) ? Carbon::createFromFormat(session('df') . ' H:i', request('dob') . '00:00')->toDateTimeString() : null;

        // Student details
        if (in_array(request('type'), ['Student', 'Student/Volunteer'])) {
            // Media Consent
            if (request('media_consent')) {
                $people_request['media_consent'] = Carbon::now()->toDateTimeString();
                $people_request['media_consent_by'] = Auth::user()->id;
            } else
                $people_request['media_consent'] = null;

        } else {
            $people_request['grade'] = $people_request['school_id'] = null;
            $people_request['media_consent'] = $people_request['media_consent_by'] = null;
        }

        // Volunteer details
        if (in_array(request('type'), ['Volunteer', 'Student/Volunteer', 'Parent/Volunteer']))
            $people_request['wwc_exp'] = (request('wwc_exp')) ? Carbon::createFromFormat(session('df') . ' H:i', request('wwc_exp') . '00:00')->toDateTimeString() : null;

        //dd($people_request);
        $people = People::create($people_request);

        Toastr::success("Profile created");

        return redirect("/people/$people->id");
    }


    /**
     * Get People (ajax)
     */
    public function getPeople($id)
    {
        $people = People::where('status', 1)->where('aid', 1)->orderBy('firstname')->get();
        $instance = EventInstance::find($id);
        $people_array = [];
        foreach ($people as $person) {
            $checked_in = $checked_in2 = null;
            $attended = Attendance::where('eid', $instance->id)->where('pid', $person->id)->first();
            if ($instance && $attended)
                $checked_in = $attended->in->format('Y-m-d H:i:s');
            $people_array[] = ['pid' => $person->id, 'name' => $person->name, 'in' => $checked_in, 'photo' => Storage::url('app/people/thumbs/t90-190.jpg') ,'eid' => $instance->id];
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
