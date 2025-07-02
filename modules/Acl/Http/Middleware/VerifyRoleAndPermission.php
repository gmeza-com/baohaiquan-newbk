<?php

namespace Modules\Acl\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str; // Added import for Str class
use Modules\User\Models\User;

class VerifyRoleAndPermission
{
  protected $replaceMethods = [
    'store' => 'create',
    'update' => 'edit',
  ];

  /**
   * Handle an incoming request.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Closure  $next
   * @return mixed
   */
  public function handle($request, Closure $next)
  {
    if ($request->segment(1) !== admin_path()) {
      abort(500);
    }

    $permission   = $this->getPermission($request);
    if (
      Auth::check() && (allow($permission) || $request->user()->is_super_admin)
    ) {
      $this->setupPermissionForFileManager($request->user());

      return $next($request);
    }

    if ($request->ajax()) {
      if (! Auth::check()) {
        return response()->json([
          'status'  => 500,
          'message' => trans('acl::language.auth_required'),
        ]);
      } else {
        return response()->json([
          'status'  => 500,
          'message' => trans('acl::language.access_deny'),
        ]);
      }
    } else {
      if (Auth::check()) {
        abort(403);
      }
      return redirect('auth');
    }
  }

  /**
   *
   * @param $method
   * @return mixed
   */
  protected function replaceMethod($method)
  {
    return str_replace(array_keys($this->replaceMethods), array_values($this->replaceMethods), $method);
  }

  /**
   *
   * @param Request $request
   * @return string
   */
  protected function getPermission($request)
  {
    $action    = $request->route()->getActionName();
    $module    = explode('\\', $action);
    $module    = isset($module[1]) && $module[1] != 'Http' ? strtolower($module[1]) : 'default';
    $controller = preg_replace('#Controller\@(.*?)$#is', '', class_basename($action));
    $method    = $request->route()->getActionMethod();
    $method    = $this->replaceMethod($method);
    $method    = Str::snake($method);

    return strtolower($module . '.' . $controller . '.' . $method);
  }

  protected function setupPermissionForFileManager($user)
  {
    @session_name('DQHSESS');
    if (!session_id()) {
      @session_start();
    }
    unset($_SESSION['dqh.filesystem.local.wwwroot']);

    $rootDir = realpath(config('filesystems.disks.public.root'));

    if ($user->can('media.media.index') || $user->is_super_admin) {
      $_SESSION['isLoggedIn'] = true;

      if ($user->can('media.media.owner') && !$user->is_super_admin) {
        $userDir =  $rootDir . '/users/user_' . $user->id;
        $url     = url('storage/users/user_' . $user->id);

        if (! file_exists($userDir)) {
          @mkdir($userDir);
        }
        $_SESSION['dqh.filesystem.rootpath'] = $userDir;
        $_SESSION['dqh.filesystem.local.wwwroot'] = $userDir;
        $_SESSION['dqh.filesystem.local.urlprefix'] = $url;
      } else {
        $_SESSION['dqh.filesystem.rootpath'] =  $rootDir;
        $_SESSION['dqh.filesystem.local.wwwroot'] = $rootDir;
        $_SESSION['dqh.filesystem.local.urlprefix'] = url('storage');
      }
    }
  }
}
