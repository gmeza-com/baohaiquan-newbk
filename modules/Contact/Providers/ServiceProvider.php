<?php

namespace Modules\Contact\Providers;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function boot()
    {
        $this->loadTranslationsFrom(__DIR__ . '/../Languages', 'contact');
        $this->loadViewsFrom(__DIR__ . '/../Views', 'contact');
        $this->loadRoutesFrom(__DIR__ . '/../Routes/web.php');

        $emails = unserialize(option('email_templates'));
        if(! array_key_exists('contact', $emails)) {
            $emails['contact'] = [
                'email_contact' => [
                    'title' => trans('contact::web.mail_template'),
                    'path' => 'modules/Contact/Views/contact.blade.php',
                    'guide' => 'contact::guide'
                ]
            ];
            update_option('email_templates', serialize($emails));
        }
    }

    public function register()
    {
    }
}