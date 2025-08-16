<?php

namespace Modules\News\Providers;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Migrations');
        $this->loadTranslationsFrom(__DIR__ . '/../Languages', 'news');
        $this->loadViewsFrom(__DIR__ . '/../Views', 'news');

        $this->loadRoutesFrom(__DIR__ . '/../Routes/admin.php');
        $this->loadRoutesFrom(__DIR__ . '/../Routes/admin-api.php');
        $this->loadRoutesFrom(__DIR__ . '/../Routes/web.php');
        $this->loadRoutesFrom(__DIR__ . '/../Routes/api.php');
    }

    public function register()
    {
        /**
         * COMMENT HOOK
         * @type array
         * @param class
         */
        register_hook('post_comment');

        add_action('module_in_menu_search_hook', [
            'name' => 'News',
            'url' => '/api/search/blog',
        ]);
        add_action('module_in_menu_search_hook', [
            'name' => 'News Category',
            'url' => '/api/search/blog/category',
        ]);
    }
}
