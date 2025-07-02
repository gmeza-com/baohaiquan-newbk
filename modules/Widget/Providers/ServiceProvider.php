<?php

namespace Modules\Widget\Providers;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Modules\Widget\Widgets\Block;
use Modules\Widget\Widgets\GoogleMaps;

class ServiceProvider extends BaseServiceProvider
{
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Migrations');
        $this->loadRoutesFrom(__DIR__ . '/../Routes/admin.php');
        $this->loadTranslationsFrom(__DIR__ . '/../Languages', 'widget');
        $this->loadViewsFrom(__DIR__ . '/../Views', 'widget');
    }

    public function register()
    {
        register_hook('widgets_hook');
        add_action('widgets_hook', [
            'type' => 'block',
            'abstract' => Block::class
        ]);
        add_action('widgets_hook', [
            'type' => 'google_map',
            'abstract' => GoogleMaps::class
        ]);
    }
}