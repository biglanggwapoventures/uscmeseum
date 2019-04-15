<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

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
    protected $redirectTo = '/';

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
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request $request
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateLogin(Request $request)
    {
        $validator = \Validator:: make($request->all(), [
            $this->username() => [
                'required',
                'string',
                Rule::exists('users')
            ],
            'password'        => 'required|string',
        ], [
            "{$this->username()}.exists" => "The given account does not exists or has not been activated yet."
        ]);

        $validator->after(function ($validator) use ($request) {
            if ($validator->errors()->count()) {
                return;
            }
            $user = User::query()
                        ->where($this->username(), $request->input($this->username()))
                        ->first();

            if ( ! $user->enabled_at && $user->isRole('standard')) {
                $validator->errors()->add($this->username(), 'Account is not yet activated.');
                return;
            }

            if ( ! Hash::check($request->input('password'), $user->password)) {
                $validator->errors()->add('password', 'Incorrect password');
            }


        });

        $validator->validate();
    }
}
