<?php

return [
    'name' => 'Tag',
    'slug' => 'tag',
    'description' => 'Quản lý các thẻ cho các module',
    'status' => 0,
    'providers' => [
        \Plugins\Province\Providers\ServiceProvider::class,
    ],
    'setting' => [
        'controller' => \Plugins\Province\Http\Controllers\ProvinceController::class,
        'action' => 'index'
    ]
];