<?php
namespace Modules\Media\Providers;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ .'/../Routes/admin.php');
        $this->loadViewsFrom(__DIR__ .'/../Views', 'media');
        $this->loadTranslationsFrom(__DIR__ . '/../Languages', 'media');
    }

    public function register()
    {

    }
}