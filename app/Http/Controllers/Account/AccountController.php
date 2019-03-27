<?php

namespace App\Http\Controllers\Account;

use DB;
use Auth;
use Validator;
use App\User;
use App\Models\Account\Account;
use Kamaln7\Toastr\Facades\Toastr;
use Camroncade\Timezone\Facades\Timezone;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AccountController extends Controller {

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $account = Account::findOrFail(session('aid'));

        return view('account/profile', compact('account'));
    }

    /**
     * Display the specified resource.
     */
    public function profile()
    {
        $account = Account::findOrFail(session('aid'));

        return view('account/profile', compact('account'));
    }

    /**
     * Display the specified resource.
     */
    public function schools()
    {
        $account = Account::findOrFail(session('aid'));

        return view('account/school/index', compact('account'));
    }

    /**
     * Display the specified resource.
     */
    public function grades()
    {
        $account = Account::findOrFail(session('aid'));

        return view('account/school/grades', compact('account'));
    }

    /**
     * Display the specified resource.
     */
    public function admins()
    {
        $account = Account::findOrFail(session('aid'));

        return view('account/admins', compact('account'));
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
