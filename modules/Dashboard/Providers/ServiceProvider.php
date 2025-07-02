<?php
namespace Modules\Dashboard\Providers;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Modules\CustomField\Libraries\CustomFieldsType;

class ServiceProvider extends BaseServiceProvider
{
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Migrations');
        $this->loadTranslationsFrom(__DIR__ . '/../Languages', 'dashboard');
        $this->loadViewsFrom(__DIR__ . '/../Views', 'dashboard');
        $this->loadRoutesFrom(__DIR__ . '/../Routes/admin.php');
    }

    public function register()
    {
        register_hook('dashboard_block');
        add_action('dashboard_block', 'dashboard::welcome');
    }
}
