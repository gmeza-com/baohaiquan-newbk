<?php
namespace Modules\Plugin\Providers;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ .'/../Routes/admin.php');
        $this->loadViewsFrom(__DIR__ .'/../Views', 'plugin');
        $this->loadTranslationsFrom(__DIR__ . '/../Languages', 'plugin');
    }
}