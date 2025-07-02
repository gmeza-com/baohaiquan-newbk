<?php

return [
    'name' => 'Province',
    'slug' => 'province',
    'description' => 'Quản lý tỉnh thành Việt Nam',
    'status' => 1,
    'providers' => [
        \Plugins\Province\Providers\ServiceProvider::class,
    ],
    'setting' => [
        'controller' => \Plugins\Province\Http\Controllers\ProvinceController::class,
        'action' => 'index'
    ]
];