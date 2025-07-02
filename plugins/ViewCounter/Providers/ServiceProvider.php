<?php
namespace Plugins\ViewCounter\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    public function boot()
    {
        $this->loadTranslationsFrom(__DIR__ . '/../Languages', 'view_counter_plugin');
        $this->loadMigrationsFrom(__DIR__ . '/../Migrations');
    }

    public function register()
    {
    }
}