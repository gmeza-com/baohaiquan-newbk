<?php

return [
    'name' => 'User',
    'slug' => 'user',
    'version' => '1.0',
    'latest_version' => '1.0',
    'description' => 'Manage all users on website',
    'status' => 1,
    'position' => 0,
    'providers' => [
        \Modules\User\Providers\ServiceProvider::class
    ],
    'helpers_autoload' => [
        // helpers here
    ]
];