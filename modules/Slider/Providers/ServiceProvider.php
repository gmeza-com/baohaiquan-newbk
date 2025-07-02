<?php

namespace Modules\Slider\Providers;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Modules\Slider\Widgets\Slider;

class ServiceProvider extends BaseServiceProvider
{
    public function boot()
    {
        $this->loadTranslationsFrom(__DIR__ . '/../Languages', 'slider');
        $this->loadViewsFrom(__DIR__ . '/../Views', 'slider');
    }

    public function register()
    {
        add_action('widgets_hook', [
            'type' => 'slider',
            'abstract' => Slider::class
        ]);
    }
}