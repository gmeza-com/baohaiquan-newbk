<?php
namespace Modules\Activity\Providers;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    public function boot()
    {
        $this->loadTranslationsFrom(__DIR__ . '/../Languages', 'activity');
        $this->loadViewsFrom(__DIR__ . '/../Views', 'activity');
        $this->loadRoutesFrom(__DIR__ . '/../Routes/admin.php');
        $this->loadMigrationsFrom(__DIR__ . '/../Migrations');
    }
}