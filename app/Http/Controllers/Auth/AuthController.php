<?php

namespace App\Http\Controllers\Auth;

use App\Events\NewUserWasRegistered;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Validator;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    /**
     * Amount of bad attempts user can make.
     *
     * @var int
     */
    protected $maxLoginAttempts = 3;

    /**
     * Time for which user is going to be blocked in seconds.
     *
     * @var int
     */
    protected $lockoutTime = 60;

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware($this->guestMiddleware(), ['except' => 'logout']);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $rules = [
            'name'                 => 'required|max:255',
            'email'                => 'required|email|max:255|unique:users',
            'password'             => 'required|min:6|confirmed',
            'g-recaptcha-response' => 'required|captcha',
        ];

        if (app()->environment('local') || app()->environment('testing')) {
            unset($rules['g-recaptcha-response']);
        }

        return Validator::make($data, $rules);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     *
     * @return User
     */
    protected function create(array $data)
    {
        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => bcrypt($data['password']),
        ]);

        event(new NewUserWasRegistered($user));

        return $user;
    }
}
