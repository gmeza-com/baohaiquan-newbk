<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class PluginServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('plugin', \App\Core\Plugin::class);
        $plugins = plugin()->getActivatedPlugins();


        foreach ($plugins as $plugin) {
            foreach ($plugin['providers'] as $provider) {
                $this->app->register($provider);
            }
        }
    }
}
