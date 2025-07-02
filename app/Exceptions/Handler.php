<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Handler extends ExceptionHandler
{
  /**
   * A list of the exception types that should not be reported.
   *
   * @var array
   */
  protected $dontReport = [
    \Illuminate\Auth\AuthenticationException::class,
    \Illuminate\Auth\Access\AuthorizationException::class,
    \Symfony\Component\HttpKernel\Exception\HttpException::class,
    \Illuminate\Database\Eloquent\ModelNotFoundException::class,
    \Illuminate\Session\TokenMismatchException::class,
    \Illuminate\Validation\ValidationException::class,
  ];

  /**
   * Report or log an exception.
   * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
   *
   * @param  \Exception $exception
   * @return void
   */
  public function report(\Throwable $exception)
  {
    parent::report($exception);
  }

  /**
   * Render an exception into an HTTP response.
   *
   * @param  \Illuminate\Http\Request $request
   * @param  \Exception $exception
   * @return \Illuminate\Http\Response
   */
  public function render($request, \Throwable $exception)
  {
    return parent::render($request, $exception);
  }

  /**
   * Convert an authentication exception into an unauthenticated response.
   *
   * @param  \Illuminate\Http\Request $request
   * @param  \Illuminate\Auth\AuthenticationException $exception
   * @return \Illuminate\Http\Response
   */
  protected function unauthenticated($request, AuthenticationException $exception)
  {
    if ($request->expectsJson()) {
      return response()->json(['error' => 'Unauthenticated.'], 401);
    }

    return redirect()->guest(route('auth.login'));
  }



  /**
   * Render the given HttpException.
   *
   * @param  \Symfony\Component\HttpKernel\Exception\HttpException $e
   * @return \Symfony\Component\HttpFoundation\Response
   */
  // protected function renderHttpException(HttpException $e)
  // {
  //   $status = $e->getStatusCode();

  //   app('language')->init();

  //   if (view()->exists("errors.http")) {
  //     return response()->view("errors.http", [
  //       'title' => trans('language.error_title_' . $status),
  //       'message' => trans('language.error_message_' . $status),
  //       'code' => $status,
  //       'exception' => $e
  //     ], $status, $e->getHeaders());
  //   } else {
  //     return $this->convertExceptionToResponse($e);
  //   }
  // }
}
