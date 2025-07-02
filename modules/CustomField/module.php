<?php

return [
    'name' => 'Custom Field',
    'slug' => 'custom-field',
    'description' => 'Quản lý các trường tùy biến trong Laravel',
    'status' => 1,
    'position' => 0,
    'providers' => [
        \Modules\CustomField\Providers\ServiceProvider::class,
    ],
    'helpers_autoload' => [
    ]
];
