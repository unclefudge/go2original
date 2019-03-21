<?php

namespace App\Http\Controllers\Misc;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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

    public function event()
    {
        return view('event/index');
    }
    public function group()
    {
        return view('group/index');
    }

}
