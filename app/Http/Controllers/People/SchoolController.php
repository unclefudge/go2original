<?php

namespace App\Http\Controllers\People;

use DB;
use App\User;
use App\Models\Account\Account;
use App\Models\People\School;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SchoolController extends Controller {

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Check authorisation
        $schools = School::where('aid', session('aid'))->get()->sortBy('name');

        return view('settings/school/index', compact('events'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('people/school/create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store()
    {
        $school = School::create(request()->all());
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        echo 'here';
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }

    /**
     * List of Schools by Grade (ajax)
     */
    public function schoolsByGrade($gid)
    {
        $list = [];
        $schools = School::where('aid', session('aid'))->orderBy('name')->get();
        if ($schools) {
            foreach ($schools as $school) {
                if ($school->grades->where('id', $gid)->first())
                    $list[$school->id] = $school->name;
            }
        }

        return json_encode($list);
    }

    /**
     * Get Schools (ajax)
     */
    public function getSchools()
    {
        $account = Account::findOrFail(session('aid'));
        $schools = School::where('aid', session('aid'))->orderBy('name')->get();
        $school_array = [];
        foreach ($schools as $school) {
            $grade_array = [];
            $grades = $account->grades->sortBy('order');
            foreach ($grades as $grade) {
                $students = User::where('school_id', $school->id)->where('grade_id', $grade->id)->count();
                $linked = (DB::table('schools_grades')->where('sid', $school->id)->where('gid', $grade->id)->first()) ? 1 : 0;
                $grade_array[] = (object) ['id' => $grade->id, 'name' => $grade->name, 'students' => $students, 'linked' => $linked, 'status' => $grade->status];
            }
            $school_array[] = [
                'id'       => $school->id,
                'name'     => $school->name,
                'students' => $school->students->count(),
                'status'   => $school->status,
                'grades'   => $grade_array,
            ];
        }

        return $school_array;
    }

}
