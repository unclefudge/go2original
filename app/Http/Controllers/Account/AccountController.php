<?php

namespace App\Http\Controllers\Account;

use DB;
use Validator;
use App\Models\Account\Account;
use Yajra\Datatables\Datatables;
use Kamaln7\Toastr\Facades\Toastr;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Check authorisation
        return view('account/index', compact('people'));
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $account = Account::findOrFail($id);
        return view('account/show', compact('account'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
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
     * Store a newly created resource in storage.
     */
    public function store()
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($id)
    {
        $account = Account::findOrFail($id);

        // Validate
        $rules = ['name' => 'required', 'slug' => 'required'];
        $mesgs = [];
        $validator = Validator::make(request()->all(), $rules, $mesgs);

        if ($validator->fails()) {
            $validator->errors()->add('FORM', 'account');

            return back()->withErrors($validator)->withInput();
        }
        //dd(request()->all());

        $account_request = request()->all();
        $account->update($account_request);

        Toastr::success("Saved changes");

        return redirect("/account/$account->id");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }
}
