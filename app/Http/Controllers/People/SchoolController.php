<?php

namespace App\Http\Controllers\People;

use DB;
use Auth;
use App\User;
use App\Models\Account\Account;
use App\Models\People\School;
use Carbon\Carbon;
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
     * Store a newly created resource in storage.
     */
    public function store()
    {
        $school = School::create(request()->all());
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($id)
    {
        if (request()->ajax()) {
            //dd(request()->all());
            $school = School::findOrFail($id);
            $school->update(request()->all());

            return $school;
        }

        return abort(404);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        if (request()->ajax()) {
            $school = School::findOrFail($id)->delete();

            return response()->json(['success', '200']);
        }

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
     * Link a School to a Grade (ajax)
     */
    public function linkSchool2Grade($sid, $gid, $link)
    {
        echo "s: $sid g:$gid l:$link<br>";
        // Link
        if ($link == 0) {
            $exists = DB::table('schools_grades')->where('sid', $sid)->where('gid', $gid)->first();
            if (!$exists)
                DB::table('schools_grades')->insert(
                    ['sid' => $sid, 'gid' => $gid, 'created_by' => Auth::user()->id,  'updated_by' => Auth::user()->id,
                     'created_at' => Carbon::now()->toDateTimeString(), 'updated_at' => Carbon::now()->toDateTimeString()]);
        } else
            DB::table('schools_grades')->where('sid', $sid)->where('gid', $gid)->delete();

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
