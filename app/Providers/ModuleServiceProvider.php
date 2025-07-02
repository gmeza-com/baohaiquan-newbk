<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ModuleServiceProvider extends ServiceProvider
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
        // register
        $this->app->singleton('module', \App\Core\Module::class);
        $modules = module()->getActivatedModules();


        foreach ($modules as $module) {
            foreach ($module['providers'] as $provider) {
                $this->app->register($provider);
            }
            // register helpers
            if (isset($module['helpers_autoload'])) {
                foreach ($module['helpers_autoload'] as $helper) {
                    app('helper')->load($helper, $module['name']);
                }
            }
        }
    }
}
