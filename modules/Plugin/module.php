<?php

return [
    'name' => 'Plugins Manager',
    'slug' => 'plugin',
    'version' => '1.0',
    'latest_version' => '1.0',
    'description' => 'Manage all plugins',
    'status' => 1,
    'position' => 0,
    'providers' => [
        \Modules\Plugin\Providers\ServiceProvider::class,
    ],
    'helpers_autoload' => [

    ]
];
