<?php

namespace Modules\Acl\Providers;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
  /**
   * Bootstrap the application services.
   *
   * @return void
   */
  public function boot()
  {
    $this->loadRoutesFrom(__DIR__ . '/../Routes/admin.php');

    $this->loadTranslationsFrom(__DIR__ . '/../Languages', 'acl');

    $this->loadMigrationsFrom(__DIR__ . '/../Migrations');

    $this->loadViewsFrom(__DIR__ . '/../Views', 'acl');
  }

  /**
   * Register the application services.
   *
   * @return void
   */
  public function register()
  {
    $this->app->singleton('APIVerifyWebService', \Modules\Acl\Libraries\APIVerifyWebService::class);
  }
}
