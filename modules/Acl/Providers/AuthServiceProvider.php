<?php

namespace Modules\Acl\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as BaseServiceProvider;
use Illuminate\Support\Facades\Gate;
use Modules\Acl\Models\Permission;

class AuthServiceProvider extends BaseServiceProvider
{
  /**
   * The policy mappings for the application.
   *
   * @var array
   */
  protected $policies = [
    'App\Model' => 'App\Policies\ModelPolicy',
  ];

  /**
   * Register any authentication / authorization services.
   *
   * @return void
   */
  public function boot()
  {
    $this->registerPolicies();
    foreach ($this->getAllPermissions() as $permission) {
      Gate::define($permission->slug, function ($user) use ($permission) {
        return $user->hasRole($permission->roles);
      });
    }
  }

  /**
   * Get all permissions
   *
   * @return \Illuminate\Database\Eloquent\Collection|static[]
   */
  public function getAllPermissions()
  {
    return Permission::with('roles')->get();
  }
}
