<?php

return [
    'name' => 'Auth',
    'slug' => 'auth',
    'version' => '1.0',
    'latest_version' => '1.0',
    'description' => 'Manage login, register and forgot password user',
    'status' => 1,
    'position' => 0,
    'providers' => [
        \Modules\Auth\Providers\ServiceProvider::class,
        \Modules\Auth\Providers\EventServiceProvider::class,
    ],
    'helpers_autoload' => [
        // helpers here
    ]
];