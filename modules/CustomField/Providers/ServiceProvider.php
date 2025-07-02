<?php
namespace Modules\CustomField\Providers;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Modules\CustomField\Libraries\CustomFieldsType;

class ServiceProvider extends BaseServiceProvider
{
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Migrations');
        $this->loadTranslationsFrom(__DIR__ . '/../Languages', 'custom_field');
        $this->loadViewsFrom(__DIR__ . '/../Views', 'custom_field');
        $this->loadRoutesFrom(__DIR__ . '/../Routes/admin.php');
    }

    public function register()
    {
        $this->app->singleton('custom.field', CustomFieldsType::class);
    }
}
