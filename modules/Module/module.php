<?php

return [
    'name' => 'Module',
    'slug' => 'module',
    'version' => '1.2',
    'latest_version' => '1.2',
    'description' => 'Manage all modules',
    'status' => 1,
    'position' => 0,
    'providers' => [
        \Modules\Module\Providers\ServiceProvider::class,
    ],
    'helpers_autoload' => [

    ]
];
