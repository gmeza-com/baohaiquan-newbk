<?php

return [
    'name' => 'Gallery',
    'slug' => 'gallery',
    'version' => '1.3',
    'latest_version' => '1.3',
    'description' => 'Manage gallery on website',
    'status' => 1,
    'position' => 3,
    'providers' => [
        \Modules\Gallery\Providers\ServiceProvider::class
    ],
    'helpers_autoload' => [
        'gallery'
    ]
];
