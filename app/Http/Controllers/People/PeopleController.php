<?php

namespace App\Http\Controllers\People;

use DB;
use Validator;
use App\Models\People\People;
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
        $people = People::where('status', 1)->get()->sortBy('firstname');
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
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('people/create');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        //
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
            'lastname.required' => 'The last name is required.',
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


        // Student details
        if (in_array(request('type'), ['Student', 'Student/Volunteer'])) {
            // Media Consent
            if (request('media_consent') != !$people->media_consent) {
                $people_request['media_consent'] = request('media_consent') ? Carbon::now()->toDateTimeString() : null;
                $people_request['media_consent_by'] = Auth::user()->id;
            }
        } else {
            $people_request['grade'] = $people_request['school_id'] = null;
            $people_request['media_consent'] = $people_request['media_consent_by'] = null;
        }

        // Volunteer details
        if (in_array(request('type'), ['Volunteer', 'Student/Volunteer', 'Parent/Volunteer'])) {
            $people_request['wwc_exp'] = (request('wwc_exp')) ? Carbon::createFromFormat('d/m/Y H:i', request('wwc_exp') . '00:00')->toDateTimeString() : null;
        }

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

        //dd($types);
        $people = People::select([
            'people.id', 'people.type', 'people.firstname', 'people.lastname', 'people.phone', 'people.email', 'people.address', 'people.suburb', 'people.state', 'people.postcode',
            'people.grade', 'people.school_id', 'people.media_consent', 'people.wwc_no', 'people.wwc_verified', 'people.status', 'people.aid',
            'schools.id As sid', 'schools.name AS school_name',
            DB::raw('CONCAT(people.firstname, " ", people.lastname) AS full_name'),
            DB::raw('DATE_FORMAT(people.wwc_exp, "%b %Y") AS wwc_exp2')])
            ->leftJoin('schools', 'people.school_id', '=', 'schools.id')
            ->whereIn('people.type', $types)
            ->where('people.aid', 1)
            ->where('people.status', 1); // request('status')

        //$people = People::whereIn('people.type', $types)->where('people.aid', 1)->where('people.status', 1); // request('status')

        $dt = Datatables::of($people)
            //->filterColumn('full_name', 'whereRaw', "CONCAT(firstname,' ',lastname) like ?", ["%$1%"])
            //->addColumn('view2', function ($people) {
            //    return '<div class="text-center"><a href="/people/' . $people->id . '"><i class="fa fa-search"></i></a></div>';
            //})
            ->editColumn('full_name', function ($people) {
                $string = $people->firstname . ' ' . $people->lastname;

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
                return ($people->wwc_verified) ? $people->wwc_exp2 :  $people->wwc_exp2 . " &nbsp; <i class='fa fa-eye-slash m--font-danger'>";
            })
            ->addColumn('action', function ($people) {
                $actions = '';
                $actions .= "<button class='btn dark btn-sm sbold uppercase margin-bottom btn-delete' data-remote='/people/$people->id' data-name='$people->firstname $people->lastname'><i class='fa fa-trash-alt'></i></button>";

                return $actions;
            })
            ->rawColumns(['id', 'view', 'full_name', 'name', 'phone', 'email', 'media_consent', 'wwc_exp2', 'action'])
            ->make(true);

        return $dt;
    }
}
