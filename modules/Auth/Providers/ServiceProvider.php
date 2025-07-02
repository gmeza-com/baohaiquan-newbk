<?php
namespace Modules\Auth\Providers;

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
        // load lang
        $this->loadTranslationsFrom(__DIR__ . '/../Languages', 'auth');
        // load view
        $this->loadViewsFrom(__DIR__ . '/../Views', 'auth');
        // Load route
        $this->loadRoutesFrom(__DIR__ . '/../Routes/web.php');
        $this->loadRoutesFrom(__DIR__ . '/../Routes/admin.php');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
    }
}