<?php

namespace App\Http\Controllers\People;

use DB;
use Auth;
use Validator;
use App\User;
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
        $users = User::where('aid', session('aid'))->get()->sortBy('firstname');
        $agent = new Agent();

        return view('people/index', compact('users', 'agent'));
    }

    /**
     * Display the specified resource.
     *
     */
    public function show($id)
    {
        $user = User::findOrFail($id);

        return view('people/show', compact('user'));
    }


    /**
     * Display the specified resource.
     *
     */
    public function activity($id)
    {
        $user = User::findOrFail($id);
        $events = Event::where('recur', 1)->where('aid', session('aid'))->pluck('name', 'id')->toArray();
        asort($events);

        return view('people/activity', compact('user', 'events'));
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

        $user_request = request()->all();

        // Empty State field if rest of address fields are empty
        if (!request('address') && !request('suburb') && !request('postcode'))
            $user_request['state'] = null;

        $user_request['dob'] = (request('dob')) ? Carbon::createFromFormat(session('df') . ' H:i', request('dob') . '00:00')->toDateTimeString() : null;


        // Student details
        if (in_array(request('type'), ['Student', 'Student/Volunteer'])) {
            // Media Consent
            if (request('media_consent')) {
                $user_request['media_consent_at'] = Carbon::now()->toDateTimeString();
                $user_request['media_consent_by'] = Auth::user()->id;
            } else
                $user_request['media_consent_at'] = $user_request['media_consent_by'] = null;

        } else {
            $user_request['grade_id'] = $user_request['school_id'] = null;
            $user_request['media_consent'] = $user_request['media_consent_by'] = null;
        }

        // Volunteer details
        if (in_array(request('type'), ['Volunteer', 'Student/Volunteer', 'Parent/Volunteer']))
            $user_request['wwc_exp'] = (request('wwc_exp')) ? Carbon::createFromFormat(session('df') . ' H:i', request('wwc_exp') . '00:00')->toDateTimeString() : null;

        // Create history record
        $user = User::create($user_request);
        PeopleHistory::addHistory($user, 'profile');

        Toastr::success("Profile created");

        return redirect("/people/$user->id");
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($id)
    {
        $user = User::findOrFail($id);
        $userBefore = User::findOrFail($id);

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

        $user_request = request()->all();

        // Empty State field if rest of address fields are empty
        if (!request('address') && !request('suburb') && !request('postcode'))
            $user_request['state'] = null;

        $user_request['dob'] = (request('dob')) ? Carbon::createFromFormat(session('df') . ' H:i', request('dob') . '00:00')->toDateTimeString() : null;

        // Student details
        if (in_array(request('type'), ['Student', 'Student/Volunteer'])) {
            if (request('media_consent') && request('media_consent') != $user->media_consent) {
                // Change in media consent
                $user_request['media_consent_at'] = Carbon::now()->toDateTimeString();
                $user_request['media_consent_by'] = Auth::user()->id;

            }
        } else {
            $user_request['grade_id'] = $user_request['school_id'] = null;
            $user_request['media_consent_at'] = $user_request['media_consent_by'] = null;
        }

        // Volunteer details
        if (in_array(request('type'), ['Volunteer', 'Student/Volunteer', 'Parent/Volunteer']))
            $user_request['wwc_exp'] = (request('wwc_exp')) ? Carbon::createFromFormat(session('df') . ' H:i', request('wwc_exp') . '00:00')->toDateTimeString() : null;

        //dd($user_request);
        $user->update($user_request);
        PeopleHistory::addHistory($user, 'profile', $userBefore);

        Toastr::success("Saved changes");

        return redirect("/people/$user->id");
    }

    /**
     * Update Photo
     */
    public function updatePhoto($id)
    {
        $user = User::findOrFail($id);
        $path = storage_path("app/account/$user->aid/images/users/");   // Server path

        // Handle attached photo
        if (request()->photo)
            $user->attachPhoto();
        elseif (request('previous_photo')) {
            // Delete file
            if ($user->photo && file_exists($path . $user->photo))
                unlink($path . $user->photo);
            $user->photo = null;
            $user->save();
        }

        return redirect("/people/$user->id");
    }

    /**
     * Toggle People Status
     */
    public function status($id, $status)
    {
        //dd(request()->all());
        $user = User::findOrFail($id);
        $user->status = request('status');
        $user->save();

        if (request()->ajax())
            return response()->json(['success', '200']);

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id)->delete();

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
        $users = User::select([
            'users.id', 'users.type', 'users.firstname', 'users.lastname', 'users.phone', 'users.email', 'users.address', 'users.suburb', 'users.state', 'users.postcode',
            'users.grade_id', 'users.school_id', 'users.media_consent', 'users.wwc_no', 'users.wwc_verified', 'users.status', 'users.aid',
            'schools.id As sid', 'schools.name AS school_name', 'grades.id AS gid', 'grades.name AS grade_name',
            DB::raw('CONCAT(users.firstname, " ", users.lastname) AS full_name'),
            DB::raw('DATE_FORMAT(users.wwc_exp, "%b %Y") AS wwc_exp2')])
            ->leftJoin('schools', 'users.school_id', '=', 'schools.id')
            ->leftJoin('grades', 'users.grade_id', '=', 'grades.id')
            ->whereIn('users.type', $types)
            ->where('users.aid', session('aid'))
            ->whereIn('users.status', $status);

        $dt = Datatables::of($users)
            //->filterColumn('full_name', 'whereRaw', "CONCAT(firstname,' ',lastname) like ?", ["%$1%"])
            //->addColumn('view2', function ($user) {
            //    return '<div class="text-center"><a href="/people/' . $user->id . '"><i class="fa fa-search"></i></a></div>';
            //})
            ->editColumn('full_name', function ($user) {
                $string = $user->firstname . ' ' . $user->lastname;
                if (!$user->status)
                    $string .= "<br>** INACTIVE **";

                return $string;
            })
            ->editColumn('address', function ($user) {
                $address = '';
                if ($user->address) $address .= "$user->address, ";
                if ($user->suburb) $address .= strtoupper($user->suburb) . ", ";
                if ($user->state) $address .= "$user->state ";
                if ($user->postcode) $address .= "$user->postcode";

                return $address;
            })
            ->editColumn('media_consent', function ($user) {
                if (!in_array($user->type, ['Student', 'Student/Volenteer'])) return "<i class='fa fa-user m--font-metal'>";

                return ($user->media_consent == 'y') ? "<i class='fa fa-user m--font-success'>" : " <i class='fa fa-user-slash m--font-danger'>";
            })
            ->editColumn('wwc_exp2', function ($user) {
                return ($user->wwc_verified) ? $user->wwc_exp2 : $user->wwc_exp2 . " &nbsp; <i class='fa fa-eye-slash m--font-danger'>";
            })
            ->addColumn('action', function ($user) {
                $actions = '';
                if ($user->status)
                    $actions .= "<button class='btn dark btn-sm sbold uppercase margin-bottom btn-archive' style='background: inherit;' data-remote='/people/$user->id/status/0' data-name='$user->firstname $user->lastname'><i class='fa fa-trash-alt'></i></button>";
                else
                    $actions .= "<button class='btn dark btn-sm sbold uppercase margin-bottom btn-delete inactive-person' style='background: inherit; color:#fff' data-remote='/people/$user->id/status/1' data-name='$user->firstname $user->lastname'><i class='fa fa-trash-alt
'></i></button>";

                return $actions;
            })
            ->rawColumns(['id', 'view', 'full_name', 'name', 'phone', 'email', 'media_consent', 'wwc_exp2', 'action'])
            ->make(true);

        return $dt;
    }
}
