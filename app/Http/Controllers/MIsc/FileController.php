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

    public function getPhoto($aid, $type, $filename)
    {
        if (session('aid') == $aid)
            return response()->download(storage_path("app/account/$aid/images/$type/$filename"), null, [], null);

        abort(404);
    }

    public function getLog($aid, $type, $filename)
    {
        if (session('aid') == $aid)
            return response()->download(storage_path("app/log/$type/$filename"), null, [], null);

        abort(404);
    }
}