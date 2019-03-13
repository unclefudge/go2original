<?php

namespace App\Http\Controllers\People;

use DB;
use Auth;
use Validator;
use App\Models\People\People;
use App\Models\People\PeopleHistory;
use App\Models\Event\Event;
use Carbon\Carbon;
use Yajra\Datatables\Datatables;
use Kamaln7\Toastr\Facades\Toastr;
use Jenssegers\Agent\Agent;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PeopleController extends Controller {

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Check authorisation
        $people = People::where('aid', session('aid'))->get()->sortBy('firstname');
        $agent = new Agent();

        return view('people/index', compact('people', 'agent'));
    }

    /**
     * Display the specified resource.
     *
     */
    public function show($id)
    {
        $people = People::findOrFail($id);

        return view('people/show', compact('people'));
    }


    /**
     * Display the specified resource.
     *
     */
    public function activity($id)
    {
        $people = People::findOrFail($id);
        $events = Event::where('recur', 1)->where('aid', session('aid'))->pluck('name', 'id')->toArray();
        asort($events);

        return view('people/activity', compact('people', 'events'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('people/create');
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store()
    {
        // Validate
        $rules = ['firstname' => 'required', 'lastname' => 'required', 'dob' => 'sometimes|nullable|date_format:' . session('df'), 'wwc_exp' => 'sometimes|nullable|date_format:' . session('df')];
        $mesgs = [
            'firstname.required' => 'The first name is required.',
            'lastname.required'  => 'The last name is required.',
            'dob.date_format'    => 'The birthday format needs to be ' . session('df-datepicker'),
            'wwc.date_format'    => 'The expiry format needs to be ' . session('df-datepicker'),
        ];

        $validator = Validator::make(request()->all(), $rules, $mesgs);

        if ($validator->fails()) {
            $validator->errors()->add('FORM', 'personal');

            return back()->withErrors($validator)->withInput();
        }
        //dd(request()->all());

        $people_request = request()->all();

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

        // Create history record
        $people = People::create($people_request);
        $x=1;
        foreach ($people->genHistoryData() as $category => $json) {
            $action = ($x == 1) ? 'created' : 'updated';
            DB::table('people_history')->insert([
                'pid'  => $people->id, 'action' => $action, 'type' => 'profile', 'subtype' => $category,
                'data' => $json, 'created_by' => Auth::user()->id, 'created_at' => Carbon::now()->toDateTimeString(), 'updated_at' => Carbon::now()->toDateTimeString()]);

            if ($x++ < 2) sleep(1); // Delay 1 sec after first loop to ensure 'Created' record is earlier then updates for order sort later on
        }

        Toastr::success("Profile created");

        return redirect("/people/$people->id");
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($id)
    {
        $people = People::findOrFail($id);
        $peopleBefore = People::findOrFail($id);

        // Validate
        $rules = ['firstname' => 'required', 'lastname' => 'required', 'dob' => 'sometimes|nullable|date_format:' . session('df'), 'wwc_exp' => 'sometimes|nullable|date_format:' . session('df')];
        $mesgs = [
            'firstname.required' => 'The first name is required.',
            'lastname.required'  => 'The last name is required.',
            'dob.date_format'    => 'The birthday format needs to be ' . session('df-datepicker'),
            'wwc.date_format'    => 'The expiry format needs to be ' . session('df-datepicker'),
        ];
        $validator = Validator::make(request()->all(), $rules, $mesgs);

        if ($validator->fails()) {
            $validator->errors()->add('FORM', 'personal');

            return back()->withErrors($validator)->withInput();
        }

        $people_request = request()->all();

        // Empty State field if rest of address fields are empty
        if (!request('address') && !request('suburb') && !request('postcode'))
            $people_request['state'] = null;

        $people_request['dob'] = (request('dob')) ? Carbon::createFromFormat(session('df') . ' H:i', request('dob') . '00:00')->toDateTimeString() : null;

        // Student details
        if (in_array(request('type'), ['Student', 'Student/Volunteer'])) {
            // Media Consent
            if (request('media_consent')) {
                if (!$people->media_consent) {
                    // New approved media request
                    $people_request['media_consent'] = request('media_consent') ? Carbon::now()->toDateTimeString() : null;
                    $people_request['media_consent_by'] = Auth::user()->id;
                }
            } else {
                if ($people->media_consent) {
                    // Previous media approval changed to denied
                    $people_request['media_consent'] = request('media_consent') ? Carbon::now()->toDateTimeString() : null;
                    $people_request['media_consent_by'] = Auth::user()->id;
                }
            }
        } else {
            $people_request['grade'] = $people_request['school_id'] = null;
            $people_request['media_consent'] = $people_request['media_consent_by'] = null;
        }

        // Volunteer details
        if (in_array(request('type'), ['Volunteer', 'Student/Volunteer', 'Parent/Volunteer']))
            $people_request['wwc_exp'] = (request('wwc_exp')) ? Carbon::createFromFormat(session('df') . ' H:i', request('wwc_exp') . '00:00')->toDateTimeString() : null;

        //dd($people_request);
        $people->update($people_request);

        // Create history record
        foreach ($people->genHistoryData($peopleBefore) as $category => $json) {
            DB::table('people_history')->insert([
                'pid'  => $people->id, 'action' => 'updated', 'type' => 'profile', 'subtype' => $category,
                'data' => $json, 'created_by' => Auth::user()->id, 'created_at' => Carbon::now()->toDateTimeString(), 'updated_at' => Carbon::now()->toDateTimeString()]);
        }

        Toastr::success("Saved changes");

        return redirect("/people/$people->id");
    }

    /**
     * Update Photo
     */
    public function updatePhoto($id)
    {
        $people = People::findOrFail($id);
        $path = storage_path("app/account/$people->aid/images/people/");   // Server path

        // Handle attached photo
        if (request()->photo)
            $people->attachPhoto();
        elseif (request('previous_photo')) {
            // Delete file
            if ($people->photo && file_exists($path . $people->photo))
                unlink($path . $people->photo);
            $people->photo = null;
            $people->save();
        }

        return redirect("/people/$people->id");
    }

    /**
     * Toggle People Status
     */
    public function status($id, $status)
    {
        //dd(request()->all());
        $people = People::findOrFail($id);
        $people->status = request('status');
        $people->save();

        if (request()->ajax())
            return response()->json(['success', '200']);

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $people = People::findOrFail($id)->delete();

        if (request()->ajax())
            return response()->json(['success', '200']);

        return redirect("/people");
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

    /**
     * Get People for specific account + Process datatables ajax request.
     */
    public function getPeople()
    {
        $types = ['Student', 'Parent', 'Parent/Volunteer', 'Volunteer', 'S', 'P', 'PV', 'V'];
        if (request('show_type')) {
            if (request('show_type') == 'Parent')
                $types = ['Parent', 'Parent/Volunteer', 'P', 'PV'];
            elseif (request('show_type') == 'Volunteer')
                $types = ['Volunteer', 'Parent/Volunteer', 'V', 'PV'];
            else
                $types = [request('show_type')];
        }
        $status = [1];
        if (request('show_inactive'))
            $status = [0, 1];

        //dd($types);
        $people = People::select([
            'people.id', 'people.type', 'people.firstname', 'people.lastname', 'people.phone', 'people.email', 'people.address', 'people.suburb', 'people.state', 'people.postcode',
            'people.grade', 'people.school_id', 'people.media_consent', 'people.wwc_no', 'people.wwc_verified', 'people.status', 'people.aid',
            'schools.id As sid', 'schools.name AS school_name',
            DB::raw('CONCAT(people.firstname, " ", people.lastname) AS full_name'),
            DB::raw('DATE_FORMAT(people.wwc_exp, "%b %Y") AS wwc_exp2')])
            ->leftJoin('schools', 'people.school_id', '=', 'schools.id')
            ->whereIn('people.type', $types)
            ->where('people.aid', session('aid'))
            ->whereIn('people.status', $status);

        $dt = Datatables::of($people)
            //->filterColumn('full_name', 'whereRaw', "CONCAT(firstname,' ',lastname) like ?", ["%$1%"])
            //->addColumn('view2', function ($people) {
            //    return '<div class="text-center"><a href="/people/' . $people->id . '"><i class="fa fa-search"></i></a></div>';
            //})
            ->editColumn('full_name', function ($people) {
                $string = $people->firstname . ' ' . $people->lastname;
                if (!$people->status)
                    $string .= "<br>** INACTIVE **";

                return $string;
            })
            ->editColumn('address', function ($people) {
                $address = '';
                if ($people->address) $address .= "$people->address, ";
                if ($people->suburb) $address .= strtoupper($people->suburb) . ", ";
                if ($people->state) $address .= "$people->state ";
                if ($people->postcode) $address .= "$people->postcode";

                return $address;
            })
            ->editColumn('media_consent', function ($people) {
                if (!in_array($people->type, ['Student', 'Student/Volenteer'])) return "<i class='fa fa-user m--font-metal'>";

                return ($people->media_consent) ? "<i class='fa fa-user m--font-success'>" : " <i class='fa fa-user-slash m--font-danger'>";
            })
            ->editColumn('wwc_exp2', function ($people) {
                return ($people->wwc_verified) ? $people->wwc_exp2 : $people->wwc_exp2 . " &nbsp; <i class='fa fa-eye-slash m--font-danger'>";
            })
            ->addColumn('action', function ($people) {
                $actions = '';
                if ($people->status)
                    $actions .= "<button class='btn dark btn-sm sbold uppercase margin-bottom btn-archive' style='background: inherit;' data-remote='/people/$people->id/status/0' data-name='$people->firstname $people->lastname'><i class='fa fa-trash-alt'></i></button>";
                else
                    $actions .= "<button class='btn dark btn-sm sbold uppercase margin-bottom btn-delete inactive-person' style='background: inherit; color:#fff' data-remote='/people/$people->id/status/1' data-name='$people->firstname $people->lastname'><i class='fa fa-trash-alt
'></i></button>";

                return $actions;
            })
            ->rawColumns(['id', 'view', 'full_name', 'name', 'phone', 'email', 'media_consent', 'wwc_exp2', 'action'])
            ->make(true);

        return $dt;
    }
}
