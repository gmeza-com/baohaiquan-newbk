<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
  /**
   * Bootstrap any application services.
   *
   * @return void
   */
  public function boot()
  {
    Schema::defaultStringLength(191);
  }

  /**
   * Register any application services.
   *
   * @return void
   */
  public function register()
  {
    // Register Single App
    $this->app->singleton('helper', \App\Core\Helper::class);
    $this->app->singleton('language', \App\Core\Language::class);

    // Template engine injection
    $this->app->singleton(\App\Core\Template\TemplateInterface::class, \App\Core\Template\Template::class);

    if (env('APP_DEBUG')) {
      // $this->app->register(\Barryvdh\Debugbar\ServiceProvider::class);
    }
  }
}
