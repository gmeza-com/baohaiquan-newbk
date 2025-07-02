<?php
namespace Modules\Auth\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Modules\Auth\Notifications\ResetPassword;
use Modules\User\Models\User;

trait ResetPasswordsUsers
{
    /**
     * Send a reset link to the given user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function forgot(Request $request)
    {
        $this->validate($request, ['email' => 'required|email']);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        if ($user = $this->getUserByEmail($request->input('email'))) {
            $this->createTokenForUser($user);
            $user->notify(new ResetPassword($user));

            return $this->sendResetLinkResponse();
        }

        return $this->sendResetLinkFailedResponse();
    }

    /**
     * Get the response for a successful password reset link.
     *
     * @return array
     */
    protected function sendResetLinkResponse()
    {
        return [
            'status' => 200,
            'message' => trans('auth::language.send_reset_success')
        ];
    }

    /**
     * Get the response for a failed password reset link.
     *
     * @return array
     */
    protected function sendResetLinkFailedResponse()
    {
        return [
            'status' => 500,
            'message' => trans('auth::language.send_reset_failed')
        ];
    }

    /**
     * Find user via email
     *
     * @param $email
     * @return bool|User
     */
    protected function getUserByEmail($email)
    {
        $user = User::where('email', $email)->first();
        return $user ?: false;
    }

    /**
     * Reset password
     *
     * @param Request $request
     * @return array
     */
    protected function resetPassword(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed'
        ]);

        if($user = $this->getUserByEmail($request->input('email'))) {
            $user->update(['password' => bcrypt($request->input('password')), 'token' => '']);

            return [
                'status' => 200,
                'message' => trans('auth::language.reset_success'),
                'redirect_url' => url('auth')
            ];
        }

        return [
            'status' => 500,
            'message' => trans('auth::language.reset_failed')
        ];
    }
}