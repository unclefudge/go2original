<?php

namespace App\Http\Controllers\People;

use App\Models\People\School;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SchoolController extends Controller {

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        //
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
}
