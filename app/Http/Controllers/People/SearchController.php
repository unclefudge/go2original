<?php

namespace App\Http\Controllers\People;

use DB;
use App\Models\People\People;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SearchController extends Controller {

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
    public function searchUsers(Request $request)
    {
        return People::where('firstname', 'LIKE', '%'.$request->q.'%')->orWhere('lastname', 'LIKE', '%'.$request->q.'%')
        ->orWhereRaw("CONCAT(firstname, ' ', lastname) LIKE ?", ['%'.$request->q.'%'])->get();
    }
}
