<?php

namespace App\Http\Controllers\Misc;

use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FileController extends Controller {

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getFile($filename)
    {
        //dd('here');
        if (Auth::user()->id == 1)
            return response()->download(storage_path('app/public/people/photos/'.$filename), null, [], null);

        abort(404);
    }

    public function getThumb($filename)
    {
        //dd('here');
        if (Auth::user()->id == 1)
            return response()->download(storage_path('app/public/people/thumbs/'.$filename), null, [], null);

        abort(404);
    }
}