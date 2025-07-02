<?php

return [
    'name' => 'News',
    'slug' => 'news',
    'version' => '1.0',
    'latest_version' => '1.0',
    'description' => 'Manage all news posts on website',
    'status' => 1,
    'position' => 2,
    'providers' => [
        \Modules\News\Providers\ServiceProvider::class
    ],
    'helpers_autoload' => [
        'news',
    ]
];
