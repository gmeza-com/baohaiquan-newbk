<?php

return [
    'name' => 'Activity',
    'slug' => 'activity',
    'version' => '1.0',
    'latest_version' => '1.0',
    'description' => 'Manage all list activities on website',
    'status' => 1,
    'position' => 0,
    'providers' => [
        \Modules\Activity\Providers\ServiceProvider::class
    ],
    'helpers_autoload' => [
        // helpers here
    ]
];
