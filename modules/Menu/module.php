<?php

return [
    'name' => 'Menu',
    'slug' => 'menu',
    'version' => '1.0',
    'latest_version' => '1.0',
    'description' => 'Manage all menus on website',
    'status' => 1,
    'position' => 0,
    'providers' => [
        \Modules\Menu\Providers\ServiceProvider::class
    ],
    'helpers_autoload' => [
        'menus'
    ]
];