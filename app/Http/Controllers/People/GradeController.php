<?php

namespace App\Http\Controllers\People;

use App\Models\People\Grade;
use App\Models\People\School;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GradeController extends Controller {

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('people/grade/index');
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
     * Get Grades (ajax)
     */
    public function getGrades()
    {
        $list = [];
        $grades = Grade::where('aid', session('aid'))->orderBy('order')->get();
        if ($grades) {
            foreach ($grades as $grade) {
                $list[] = [
                    'id'     => $grade->id,
                    'name'   => $grade->name,
                    'key'    => $grade->type,
                    'order'  => $grade->order,
                    'status' => $grade->status,
                ];
            }
        }

        /*$list = [];
        $list[] = ['id' => 1, 'name' => 'Cat', 'key' => 1, 'order'  => 0];
        $list[] = ['id' => 2, 'name' => 'Dog', 'key' => 2, 'order'  => 1];
        $list[] = ['id' => 3, 'name' => 'Bird', 'key' => 3, 'order'  => 2];*/


        return json_encode($list);
    }
}
