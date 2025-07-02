<?php

namespace Modules\Gallery\Providers;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Migrations');
        $this->loadTranslationsFrom(__DIR__ . '/../Languages', 'gallery');
        $this->loadViewsFrom(__DIR__ . '/../Views', 'gallery');

        $this->loadRoutesFrom(__DIR__ . '/../Routes/admin.php');
        $this->loadRoutesFrom(__DIR__ . '/../Routes/web.php');
        $this->loadRoutesFrom(__DIR__ . '/../Routes/api.php');
    }

    public function register()
    {
        add_action('module_in_menu_search_hook', [
            'name' => 'Gallery',
            'url' => '/api/search/gallery',
        ]);

        add_action('module_in_menu_search_hook', [
            'name' => 'Gallery Category',
            'url' => '/api/search/gallery/collection',
        ]);
    }
}