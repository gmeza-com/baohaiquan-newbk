<?php
namespace Modules\Option\Providers;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/../Routes/admin.php');

        $this->loadTranslationsFrom(__DIR__ . '/../Languages', 'option');

        $this->loadMigrationsFrom(__DIR__ . '/../Migrations');

        $this->loadViewsFrom(__DIR__ . '/../Views', 'option');
    }

    public function register()
    {
        $this->app->singleton('option', \Modules\Option\Libraries\Option::class);
        register_hook('general_option_fields', []);
        register_hook('system_option_fields', []);
        register_hook('email_templates', []);
    }
}