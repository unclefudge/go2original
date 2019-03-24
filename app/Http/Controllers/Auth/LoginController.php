<?php

namespace App\Http\Controllers\Auth;

use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * The user has been authenticated.   *** OVERRIDE DEFAULT ***
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        session(['aid' => Auth::user()->aid]);
        session(['tz' => Auth::user()->timezone]);
        session(['df' => Auth::user()->dateformat]);
        session(['df-datepicker' => (Auth::user()->dateformat == 'd/m/Y') ? 'dd/mm/yyyy' : 'mm/dd/yyyy']);
        session(['df-moment' => (Auth::user()->dateformat == 'd/m/Y') ? 'DD/MM/YYYY' : 'MM/DD/YYYY']);
        session(['show_inactive_people' => '0']);
        session(['show_inactive_events' => '0']);
        //return redirect()->intended($this->redirectPath());
    }

    /**
     * Check either username or email.    *** OVERRIDE DEFAULT ***
     * @return string
     */
    public function username()
    {
        $identity  = request('identity');
        $fieldName = filter_var($identity, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        request()->merge([$fieldName => $identity]);
        return $fieldName;
    }
    /**
     * Validate the user login.     *** OVERRIDE DEFAULT ***
     * @param Request $request
     */
    protected function validateLogin(Request $request)
    {
        $this->validate($request,
            ['identity' => 'required|string', 'password' => 'required|string'],
            ['identity.required' => 'Username or email is required', 'password.required' => 'Password is required']
        );
    }
    /**
     * @param Request $request         *** OVERRIDE DEFAULT ***
     * @throws ValidationException
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        $request->session()->put('login_error', trans('auth.failed'));
        throw ValidationException::withMessages(['error' => [trans('auth.failed')]]);
    }
}

