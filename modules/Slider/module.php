<?php

return [
    'name' => 'Slider',
    'slug' => 'Slider',
    'version' => '1.0',
    'latest_version' => '1.0',
    'description' => 'Manage slider on website',
    'status' => 1,
    'position' => 1,
    'providers' => [
        \Modules\Slider\Providers\ServiceProvider::class
    ],
    'helpers_autoload' => [
    ]
];
