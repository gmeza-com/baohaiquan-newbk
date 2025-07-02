<?php
namespace Modules\User\Providers;

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
        $this->loadMigrationsFrom(__DIR__ . '/../Migrations');

        $this->loadTranslationsFrom(__DIR__ . '/../Languages', 'user');

        $this->loadViewsFrom(__DIR__ . '/../Views', 'user');

        $this->loadRoutesFrom(__DIR__ . '/../Routes/admin.php');

        $this->loadRoutesFrom(__DIR__ . '/../Routes/web.php');

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        register_hook('user_settings_fields');

        add_action('user_settings_fields', 'user::form.avatar');
        add_action('user_settings_fields', 'user::form.information');
    }
}
