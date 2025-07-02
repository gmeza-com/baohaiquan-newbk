<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class OptionServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->config['app'] = array_merge($this->app->config['app'], [
            'name' => get_option('site_name'),
            'url' => get_option('site_url')
        ]);

        $this->app->config['mail'] = array_merge($this->app->config['mail'], [
            'host' => get_option('mail_host'),
            'port' => get_option('mail_port'),
            'from' => [
                'address' => get_option('mail_from_address'),
                'name' => get_option('mail_from_name')
            ],
            'encryption' => get_option('mail_encryption'),
            'username' => get_option('mail_username'),
            'password' => get_option('mail_password'),
        ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
    }
}
