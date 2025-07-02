<?php
namespace Plugins\SEO\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/../Views', 'seo_plugin');
        $this->loadTranslationsFrom(__DIR__ . '/../Languages', 'seo_plugin');
        $this->loadMigrationsFrom(__DIR__ . '/../Migrations');
    }

    public function register()
    {
    }
}