<?php

namespace App\Http\Controllers\Event;

use DB;
use Auth;
use Validator;
use App\Models\Event\Event;
use App\Models\Event\EventInstance;
use App\Models\Event\Attendance;
use App\Models\People\People;
use Carbon\Carbon;
use Yajra\Datatables\Datatables;
use Kamaln7\Toastr\Facades\Toastr;
use Jenssegers\Agent\Agent;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CheckinController extends Controller {

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Display the specified resource.
     *
     */
    public function show($id)
    {
        $today = Carbon::now()->format('Y-m-d');
        $event = Event::findOrFail($id);
        $instance = EventInstance::where('eid', $id)->whereDate('start', $today)->first();
        if (!$instance) {
            $instance = EventInstance::create([
                'name'       => $event->name,
                'start'      => Carbon::today()->toDateTimeString(),
                'code'       => $event->code,
                'grades'     => $event->grades,
                'background' => $event->background,
                'eid'        => $event->id,
                'aid'        => $event->aid
            ]);
        }
        return view('event/checkin', compact('event', 'instance'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $event = Event::findOrFail($id);

        //dd($event->name);

        return view('event/edit', compact('event'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store()
    {
        // Validate
        $rules = ['name' => 'required', 'frequency' => 'required',];
        $mesgs = [
            'name.required'      => 'The event name is required.',
            'frequency.required' => 'The frequency is required.',
        ];
        $validator = Validator::make(request()->all(), $rules, $mesgs);

        if ($validator->fails()) {
            $validator->errors()->add('FORM', 'event');

            return back()->withErrors($validator)->withInput();
        }

        $event_request = request()->all();
        if (request('frequency') == 'recur')
            $event_request['recur'] = 1;

        $event = Event::create($event_request);

        Toastr::success("Event created");

        return (request('frequency') == 'recur') ? redirect("/event") : redirect("/event/$event->id");
    }


    /**
     * Update the specified resource in storage.
     */
    public function update($id)
    {
        $people = People::findOrFail($id);

        // Validate
        $rules = ['type' => 'required', 'firstname' => 'required', 'lastname' => 'required'];
        $mesgs = [
            'firstname.required' => 'The first name is required.',
            'lastname.required'  => 'The last name is required.',
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

        $people_request['dob'] = (request('dob')) ? Carbon::createFromFormat('d/m/Y H:i', request('dob') . '00:00')->toDateTimeString() : null;


        // Volunteer details
        if (in_array(request('type'), ['Volunteer', 'Student/Volunteer', 'Parent/Volunteer']))
            $people_request['wwc_exp'] = (request('wwc_exp')) ? Carbon::createFromFormat('d/m/Y H:i', request('wwc_exp') . '00:00')->toDateTimeString() : null;

        //dd($people_request);
        $people->update($people_request);

        Toastr::success("Saved changes");

        return redirect("/people/$people->id");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $people = People::findOrFail($id);
        $people->status = 0;
        $people->save();

        return response()->json(['success', '200']);
        //return Response::json('success', 200);
    }


    /**
     * Get People (ajax)
     */
    public function getPeople($id)
    {
        $people = People::where('status', 1)->where('aid', 1)->get();
        $instance = EventInstance::find($id);
        $people_array = [];
        foreach ($people as $person) {
            $checked_in = null;
            $attended = Attendance::where('eid', $instance->id)->where('pid', $person->id)->first();
            if ($instance && $attended)
                $checked_in = $attended->in;
            $people_array[] = ['pid' => $person->id, 'name' => $person->name, 'in' => $checked_in, 'eid' => $instance->id];
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
