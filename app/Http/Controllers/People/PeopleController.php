<?php

namespace App\Http\Controllers\People;

use DB;
use Validator;
use App\Models\People\People;
use Yajra\Datatables\Datatables;
use Kamaln7\Toastr\Facades\Toastr;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PeopleController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Check authorisation
        $people = People::all()->sortBy('firstname');

        return view('people/index', compact('people'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\People\People $people
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $people = People::findOrFail($id);

        return view('people/show', compact('people'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\People\People $people
     * @return \Illuminate\Http\Response
     */
    public function edit(People $people)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\People\People $people
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        $people = People::findOrFail($id);

        // Validate
        $rules = ['firstname' => 'required', 'lastname' => 'required'];
        $mesgs = [
            'firstname.required' => 'The first name is required.',
            'firstname.required' => 'The last name is required.',
        ];
        $validator = Validator::make(request()->all(), $rules, $mesgs);

        if ($validator->fails()) {
            $validator->errors()->add('FORM', 'profile');

            return back()->withErrors($validator)->withInput();
        }
        //dd(request()->all());

        $people_request = request()->all();

        // Empty State field if rest of address fields are empty
        if (!request('address') && !request('suburb') && !request('postcode'))
            $people_request['state'] = null;

        $people->update($people_request);

        Toastr::success("Saved changes");

        return redirect("/people/$people->id");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\People\People $people
     * @return \Illuminate\Http\Response
     */
    public function destroy(People $people)
    {
        //
    }

    /**
     * Get People for specific account + Process datatables ajax request.
     */
    public function getPeople()
    {
        $types = ['Student', 'Parent', 'Parent/Volunteer', 'Volunteer', 'S', 'P', 'PV', 'V'];
        if (request('type')) {
            if (request('type') == 'Parent')
                $types = ['Parent', 'Parent/Volunteer', 'P', 'PV'];
            elseif (request('type') == 'Volunteer')
                $types = ['Volunteer', 'Parent/Volunteer', 'V', 'PV'];
        }

        //dd($types);
        $people = People::select([
            'people.id', 'people.type', 'people.firstname', 'people.lastname', 'people.phone', 'people.email',
            'people.address', 'people.suburb', 'people.state', 'people.postcode', 'people.status', 'people.aid',
            DB::raw('CONCAT(people.firstname, " ", people.lastname) AS full_name')])
            ->whereIn('people.type', $types)
            ->where('people.aid', 1)
            ->where('people.status', 1); // request('status')

        //$people = People::whereIn('people.type', $types)->where('people.aid', 1)->where('people.status', 1); // request('status')

        $dt = Datatables::of($people)
            //->filterColumn('full_name', 'whereRaw', "CONCAT(firstname,' ',lastname) like ?", ["%$1%"])
            ->editColumn('id', function ($people) {
                //if (in_array(Auth::user()->id, [3,109]) || Auth::user()->allowed2('view.user', $user))
                return '<div class="text-center"><a href="/people/' . $people->id . '"><i class="fa fa-search"></i></a></div>';

                return '';
            })
            ->editColumn('full_name', function ($people) {
                $string = $people->firstname . ' ' . $people->lastname;

                return $string;
            })
            ->editColumn('phone', function ($people) {
                return $people->phone;
                return '<a href="tel:' . preg_replace("/[^0-9]/", "", $people->phone) . '">' . $people->phone . '</a>';
            })
            ->editColumn('email', function ($people) {
                //return '<a href="mailto:' . $user->email . '">' . '<i class="fa fa-envelope-o"></i>' . '</a>';
                return '<a href="mailto:' . $people->email . '">' . $people->email . '</a>';
            })
            ->editColumn('address', function ($people) {
                $address = '';
                if ($people->address) $address .= "$people->address, ";
                if ($people->suburb) $address .= strtoupper($people->suburb).", ";
                if ($people->state) $address .= "$people->state ";
                if ($people->postcode) $address .= "$people->postcode";
                return $address;
            })
            ->addColumn('action', function ($people) {
                $actions = '';
                $actions .= "<button class='btn dark btn-sm sbold uppercase margin-bottom btn-delete' data-remote='/people/$people->id' data-name='$people->firstname $people->lastname'><i class='fa fa-trash'></i></button>";
                return $actions;
            })
            ->rawColumns(['id', 'full_name', 'name', 'phone', 'email', 'action'])
            ->make(true);

        return $dt;
    }
}
