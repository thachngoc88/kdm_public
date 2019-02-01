<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Cookie;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

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

    use AuthenticatesUsers {
        authenticated as traitAuthenticated;
        logout as traitLogout;
    }

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function credentials(Request $request)
    {
//      return array_merge($request->only($this->username(), 'password'), ['enabled' => 1]);
        return array_merge($request->only($this->username(), 'password'));
    }

    public function username()
    {
        return 'login_id';
    }

    protected function authenticated(Request $request, $user)
    {
        Cookie::queue(Cookie::make('challengeUserStatus', 'login'));
        if ($user->enabled == 0) {
            $user->enabled = 1;
            $user->save();
        }

        return $this->traitAuthenticated($request, $user);
    }

    public function logout(Request $request)
    {
        Cookie::queue(Cookie::make('isLogout', '1'));
        return $this->traitLogout($request);
    }
}
