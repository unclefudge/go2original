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
use App\Http\Utilities\Slim;
use Intervention\Image\Facades\Image;
use Yajra\Datatables\Datatables;
use Kamaln7\Toastr\Facades\Toastr;
use Jenssegers\Agent\Agent;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EventController extends Controller {

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Check authorisation
        $events = Event::where('aid', session('aid'))->get()->sortBy('name');

        //$agent = new Agent();

        return view('event/index', compact('events'));
    }

    /**
     * Display the specified resource.
     *
     */
    public function show($id)
    {
        $event = Event::findOrFail($id);

        return view('event/overview', compact('event'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('event/create');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $event = Event::findOrFail($id);

        return view('event/edit', compact('event'));
    }

    /**
     * Event Settings
     */
    public function settings($id)
    {
        $event = Event::findOrFail($id);

        return view('event/settings', compact('event'));
    }

    /**
     * Event Attendance
     */
    public function attendance($id, $date)
    {
        $event = Event::findOrFail($id);
        $instances = EventInstance::where('eid', $event->id)->get();

        if ($date == 0) {
            $instance = EventInstance::where('eid', $event->id)->orderBy('start', 'desc')->first();
            $date = ($instance) ? $instance->start->timezone(session('tz'))->format('Y-m-d') : '';
        } else {
            list($year, $month, $day) = explode($date);
            $date_timezone_adjusted = Carbon::createFromDate($year, $month, $day, session('tz'));
            $instance = EventInstance::where('eid', $event->id)->whereDate('start', $date)->first();
            // Redirect if invalid instance date
            if (!$instance)
                return abort(404);
        }

        $dates = [];
        foreach ($instances as $inst)
            $dates[$inst->start->timezone(session('tz'))->format('Y-m-d')] = $inst->start->timezone(session('tz'))->format(session('df')) . " &nbsp; $inst->name";

        krsort($dates);

        //dd($instance);
        return view('event/attendance', compact('event', 'instance', 'date', 'dates'));
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

        $event_request['start'] = Carbon::now()->toDateTimeString();
        $event = Event::create($event_request);

        Toastr::success("Event created");

        return (request('frequency') == 'recur') ? redirect("/event") : redirect("/event/$event->id");
    }


    /**
     * Update the specified resource in storage.
     */
    public function update($id)
    {
        $event = Event::findOrFail($id);

        //dd(request()->all());
        // Validate
        $rules = ['name' => 'required'];
        $mesgs = ['name.required' => 'The event name is required.',];
        $validator = Validator::make(request()->all(), $rules, $mesgs);

        if ($validator->fails()) {
            $validator->errors()->add('FORM', 'event');

            return back()->withErrors($validator)->withInput();
        }
        $event_request = request()->all();

        // Convert Grades to CSV
        if (request('grades')) {
            $string = '';
            foreach (request('grades') as $grade)
                $string .= "$grade<>";
            $event_request['grades'] = rtrim($string, '<>');
        }

        //dd($event_request);
        $event->update($event_request);

        Toastr::success("Saved changes");

        return redirect("/event/$event->id/settings");
    }

    /**
     * Update Photo
     */
    public function updatePhoto($id)
    {
        $event = Event::findOrFail($id);
        $path = storage_path("app/account/$event->aid/images/events/");   // Server path

        //dd(request()->all());
        // Handle attached photo
        if (request()->photo) {
            // Pass Slim's getImages the name of your file input, and since we only care about one image, use Laravel's head() helper to get the first element
            $image = head(Slim::getImages('photo'));

            // Grab the ouput data (data modified after Slim has done its thing)
            if (isset($image['output']['data'])) {
                $name = $event->id . '.' . pathinfo($image['output']['name'], PATHINFO_EXTENSION);   // Original file name = $image['output']['name'];
                $data = $image['output']['data'];  // Base64 of the image

                $filepath = $path . $name;

                // Save the file to the server + update record
                $file = Slim::saveFile($data, $name, $path, false);
                $event->background = $name;
                $event->save();

                // Save the image as a medium size max 400px with 70% quality
                if (exif_imagetype($filepath)) {
                    Image::make($filepath)
                        ->resize(400, null, function ($constraint) {
                            $constraint->aspectRatio();
                            $constraint->upsize();
                        })
                        ->save($path . 'm' . $name, 70);
                } else
                    Toastr::error("Bad image");
            }
        } elseif (request('previous_photo')) {
            // Delete file
            if ($event->background && file_exists($path . $event->background))
                unlink($path . $event->background);
            $event->background = null;
            $event->save();
        }

        return redirect("/event/$event->id/settings");
    }

    /**
     * Toggle Event Status
     */
    public function status($id, $status)
    {
        $event = Event::findOrFail($id);
        $event->status = request('status');
        $event->save();

        if (request()->ajax())
            return response()->json(['success', '200']);

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $event = Event::findOrFail($id)->delete();

        if (request()->ajax())
            return response()->json(['success', '200']);

        return redirect("/event");
    }


    /**
     * Get Dates (ajax)
     */
    public function getDates($id)
    {
        $instances = EventInstance::where('eid', $id)->get();
        $dates_array = [0 => 'Select date'];
        foreach ($instances as $instance) {
            $dates_array[] = ['id' => $instance->id, 'text' => $instance->start->format(session('df'))];
        }

        return $dates_array;
    }

    /**
     * Get People (ajax)
     */
    public function getPeople($id)
    {
        $people = People::where('aid', session('aid'))->orderBy('firstname')->get();
        $instance = EventInstance::find($id);
        $people_array = [];
        foreach ($people as $person) {
            $checked_in = $method = null;
            $attended = Attendance::where('eid', $instance->id)->where('pid', $person->id)->first();
            $new = 0;
            if ($instance && $attended) {
                $checked_in = $attended->in->timezone(session('tz'))->format('Y-m-d H:i:s'); // Need to convert to local tz due to front-end moment.js
                $method = $attended->method;
                $new = ($person->firstEvent()->start->timezone(session('tz'))->format('Y-m-d') == $instance->start->timezone(session('tz'))->format('Y-m-d')) ? 1 : 0;
            }
            $people_array[] = [
                'pid'    => $person->id,
                'in'     => $checked_in,
                'name'   => $person->name,
                'type'   => $person->type,
                'gender'  => $person->gender,
                'grade'  => $person->grade,
                'school' => $person->school_name,
                'photo'  => $person->photo_path,
                'status' => $person->status,
                'new' => $new,
                'method' => $method,
                'eid'    => $instance->id
            ];
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
