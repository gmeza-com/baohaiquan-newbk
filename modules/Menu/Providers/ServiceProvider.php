<?php
namespace Modules\Menu\Providers;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/../Routes/admin.php');

        $this->loadTranslationsFrom(__DIR__ . '/../Languages', 'menu');

        $this->loadMigrationsFrom(__DIR__ . '/../Migrations');

        $this->loadViewsFrom(__DIR__ . '/../Views', 'menu');
    }

    public function register()
    {
        // Register hooks
        register_hook('module_in_menu_search_hook');
    }
}
