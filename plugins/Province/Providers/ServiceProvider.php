<?php
namespace Plugins\Province\Providers;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/../Routes/api.php');
        $this->loadRoutesFrom(__DIR__ . '/../Routes/admin-api.php');
        $this->loadViewsFrom(__DIR__ . '/../Views', 'province_plugin');
        $this->loadTranslationsFrom(__DIR__ . '/../Languages', 'province_plugin');
    }

    public function register()
    {
        
    }
}