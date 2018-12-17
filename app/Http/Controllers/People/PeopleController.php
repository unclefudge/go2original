<?php

namespace App\Http\Controllers\People;

use Validator;
use App\Models\People\People;
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

        $validator = Validator::make(request()->all(), [
            'firstname' => 'required',
        ]);

        if ($validator->fails()) {
            $validator->errors()->add('FORM', 'profile');

            return back()->withErrors($validator)->withInput();
        }
        dd(request()->all());
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
}
