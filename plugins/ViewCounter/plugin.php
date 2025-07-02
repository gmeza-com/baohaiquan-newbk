<?php

return [
    'name' => 'ViewCounter',
    'slug' => 'view-counter',
    'description' => 'Quản lý bộ đếm cho các ứng dụng',
    'status' => 1,
    'providers' => [
        \Plugins\ViewCounter\Providers\ServiceProvider::class,
    ]
];