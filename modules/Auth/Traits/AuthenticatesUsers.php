<?php

namespace Modules\Auth\Traits;

// use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Modules\User\Models\User;

trait AuthenticatesUsers
{
  // use ThrottlesLogins;

  /**
   * Login
   * @param Request $request
   * @return array
   */
  protected function login(Request $request)
  {
    $this->validateLogin($request);

    // If username is root, disabled now
    if ($this->hasRootType($request)) {
      return $this->sendDenyResponse();
    }

    // If user is not activated
    if ($this->isNotActivated($request)) {
      return $this->sendNotAcivatedResponse();
    }

    // If the class is using the ThrottlesLogins trait, we can automatically throttle
    // the login attempts for this application. We'll key this by the username and
    // the IP address of the client making these requests into this application.
    // if ($this->hasTooManyLoginAttempts($request)) {
    //   $this->fireLockoutEvent($request);

    //   return $this->sendLockoutResponse($request);
    // }

    if ($this->attemptLogin($request)) {
      return $this->sendLoginResponse($request);
    }

    // If the login attempt was unsuccessful we will increment the number of attempts
    // to login and redirect the user back to the login form. Of course, when this
    // user surpasses their maximum number of attempts they will get locked out.
    $this->incrementLoginAttempts($request);

    return $this->sendFailedLoginResponse($request);
  }

  /**
   * Validate the user login request.
   *
   * @param  \Illuminate\Http\Request $request
   * @return void
   */
  protected function validateLogin(Request $request)
  {
    $this->validate($request, [
      $this->username() => 'required',
      'password' => 'required',
    ]);
  }

  /**
   * if username is root, send true.
   * @return boolean
   */
  protected function hasRootType(Request $request)
  {
    return ($request->input($this->username()) == 'root');
  }

  /**
   * Send deny message
   * @return array
   */
  protected function sendDenyResponse()
  {
    return [
      'status' => 500,
      'message' => trans('auth::language.root_deny')
    ];
  }

  /**
   * isNotActivated
   * @return boolean
   */
  protected function isNotActivated(Request $request)
  {
    $user = User::where('username', $request->input('username'))->firstOrFail();
    return !$user->activated;
  }

  protected function sendNotAcivatedResponse()
  {
    return [
      'status' => 500,
      'message' => trans('auth::language.account_is_not_activated')
    ];
  }

  /**
   * Attempt to log the user into the application.
   *
   * @param  \Illuminate\Http\Request $request
   * @return bool
   */
  protected function attemptLogin(Request $request)
  {
    return $this->guard()->attempt(
      $this->credentials($request),
      $request->has('remember')
    );
  }

  /**
   * Get the needed authorization credentials from the request.
   *
   * @param  \Illuminate\Http\Request $request
   * @return array
   */
  protected function credentials(Request $request)
  {
    return $request->only($this->username(), 'password');
  }

  /**
   * Send the response after the user was authenticated.
   *
   * @param  \Illuminate\Http\Request $request
   * @return array
   */
  protected function sendLoginResponse(Request $request)
  {
    $request->session()->regenerate();

    // $this->clearLoginAttempts($request);

    return $this->authenticated($request, $this->guard()->user());
  }

  /**
   * The user has been authenticated.
   *
   * @param  \Illuminate\Http\Request $request
   * @param  User $user
   * @return mixed
   */
  protected function authenticated(Request $request, $user)
  {
    $user->recordActivity('logged');

    return [
      'status' => 200,
      'message' => trans('auth::language.login_success'),
      'redirect_url' => $this->redirectTo($request)
    ];
  }

  /**
   * Redirect the user after determining they are locked out.
   *
   * @param  \Illuminate\Http\Request $request
   * @return array
   */
  protected function sendLockoutResponse(Request $request)
  {
    $seconds = $this->limiter()->availableIn(
      $this->throttleKey($request)
    );

    $message = trans('auth::language.throttle', ['seconds' => $seconds]);

    return [
      'status' => 500,
      'message' => $message
    ];
  }

  /**
   * Get the failed login response instance.
   *
   * @param  \Illuminate\Http\Request $request
   * @return array
   */
  protected function sendFailedLoginResponse(Request $request)
  {
    return [
      'status' => 500,
      'message' => trans('auth::language.failed')
    ];
  }

  protected function redirectTo(Request $request)
  {
    if ($request->has('redirect_to')) {
      return $request->redirect_to;
    } else {
      return url('/iadmin');
    }
  }

  /**
   * Get the login username to be used by the controller.
   *
   * @return string
   */
  protected function username()
  {
    return 'username';
  }

  /**
   * Get the guard to be used during authentication.
   *
   * @return \Illuminate\Contracts\Auth\StatefulGuard
   */
  protected function guard()
  {
    return Auth::guard();
  }

  /**
   * Log the user out of the application.
   *
   * @param  \Illuminate\Http\Request $request
   * @return \Illuminate\Http\Response
   */
  public function logout(Request $request)
  {
    if (!$request->session()->has('root_remote')) {
      $this->guard()->logout();
    }

    Cache::flush();
    $request->session()->flush();
    $request->session()->regenerate();

    @session_name('DQHSESS');
    if (!session_id()) {
      @session_start();
    }
    session_destroy();

    return redirect('/');
  }
}
