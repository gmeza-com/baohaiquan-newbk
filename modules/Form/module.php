<?php

return [
    'name' => 'Form',
    'slug' => 'form',
    'description' => 'Quản lý các form',
    'status' => 1,
    'position' => 2,
    'version' => '1.1',
    'latest_version' => '1.1',
    'providers' => [
        \Modules\Form\Providers\ServiceProvider::class,
    ],
    'helpers_autoload' => [
        'forms'
    ]
];
