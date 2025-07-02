<?php

return [
    'name' => 'Dashboard',
    'slug' => 'dashboard',
    'description' => '',
    'status' => 1,
    'position' => 0,
    'providers' => [
        \Modules\Dashboard\Providers\ServiceProvider::class,
    ],
    'helpers_autoload' => [
    ]
];
