<?php

return [
    'name' => 'Widget',
    'slug' => 'widget',
    'version' => '1.0',
    'latest_version' => '1.0',
    'description' => 'Manage widgets on website',
    'status' => 1,
    'position' => 0,
    'providers' => [
        \Modules\Widget\Providers\ServiceProvider::class
    ],
    'helpers_autoload' => [
        'widgets'
    ]
];