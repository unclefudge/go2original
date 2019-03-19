<?php

namespace App\Http\Controllers\People;

use App\User;
use App\Models\Account\Account;
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
        return view('settings/school/grades');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store()
    {
        $grade = Grade::create(request()->all());
    }

    /**
     * Update the specified resource in storage.
     *
     * Note: we are passing the Account ID and NOT the Grade ID as standard REST practice
     *       as we updating all grades for specified account via ajax
     */
    public function update($aid)
    {
        if (request()->ajax()) {
            $grade_array = json_decode(request('grades'));
            $grade_ids = [];
            if ($grade_array) {
                // Update existing grades or Create new ones
                foreach ($grade_array as $obj) {
                    //echo "id: $obj->id name: $obj->name<br>";
                    if ($obj->id == 'new')
                        $grade = Grade::create(['name' => $obj->name, 'order' => $obj->order]);
                    else {
                        $grade = Grade::findOrFail($obj->id);
                        if ($grade->name != $obj->name || $grade->order != $obj->order || $grade->status != $obj->status) {
                            $grade->name = $obj->name;
                            $grade->order = $obj->order;
                            $grade->status = $obj->status;
                            $grade->save();
                        }
                    }
                    $grade_ids[] = $grade->id;
                }
            }

            // Delete any grades not passed to update
            $account = Account::findOrFail($aid);
            foreach ($account->grades as $grade) {
                if (!in_array($grade->id, $grade_ids)) {
                    // Clear the grade field for any Students in this grade
                    $students = User::where('aid', session('aid'))->where('grade_id', $grade->id)->get();
                    if ($students) {
                        foreach ($students as $student) {
                            $student->grade_id = null;
                            $student->save();
                        }
                    }

                    // Delete grade
                    $grade->delete();
                }
            }
        }
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
                    'count'  => $grade->students->count(),
                    'status' => $grade->status,
                    'edit'  => 0,
                ];
            }
        }
        return json_encode($list);
    }
}
