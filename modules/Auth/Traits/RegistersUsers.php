<?php
namespace Modules\Auth\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Modules\Auth\Events\Registered;
use Modules\User\Models\User;

trait RegistersUsers
{
    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function registerValidator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'username' => 'required|max:255|alpha_dash|unique:users',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
            'terms' => 'required|boolean'
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function registerCreate(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'username' => $data['username'],
            'token' => '',
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'activated' => 0,
            'setting' => []
        ]);

        // Create token activation
        $this->createTokenForUser($user);

        return $user;
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function register(Request $request)
    {
        return [
            'status' => 403,
            'message' => 'Deny !'
        ];
//        $this->registerValidator($request->all())->validate();
//
//        event(new Registered($user = $this->registerCreate($request->all())));
//
//        return [
//            'status' => 200,
//            'message' => trans('auth::language.register_success')
//        ];
    }
}