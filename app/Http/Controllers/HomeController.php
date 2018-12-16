<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }

    public function metronic()
    {
        return view('metronic');
    }

    public function fudge()
    {
        return view('fudge');
    }

    public function people()
    {
        return view('people/index');
    }
    public function event()
    {
        return view('event/index');
    }
    public function group()
    {
        return view('group/index');
    }
}
