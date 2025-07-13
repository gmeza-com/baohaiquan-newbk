<?php

namespace Modules\Royalty\Providers;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
  public function boot()
  {
    $this->loadMigrationsFrom(__DIR__ . '/../Migrations');
    $this->loadTranslationsFrom(__DIR__ . '/../Languages', 'royalty');
    $this->loadViewsFrom(__DIR__ . '/../Views', 'royalty');

    $this->loadRoutesFrom(__DIR__ . '/../Routes/admin.php');
    $this->loadRoutesFrom(__DIR__ . '/../Routes/web.php');
    $this->loadRoutesFrom(__DIR__ . '/../Routes/api.php');
  }

  public function register()
  {
    add_action('module_in_menu_search_hook', [
      'name' => 'Royalty',
      'url' => '/api/search/royalty',
    ]);

    add_action('module_in_menu_search_hook', [
      'name' => 'Royalty Category',
      'url' => '/api/search/royalty/collection',
    ]);
  }
}
