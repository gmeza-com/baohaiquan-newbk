<?php

return [
    'name' => 'Option',
    'slug' => 'option',
    'version' => '1.0',
    'latest_version' => '1.0',
    'description' => 'Manage options on website',
    'status' => 1,
    'position' => 0,
    'providers' => [
        \Modules\Option\Providers\ServiceProvider::class,
    ],
    'helpers_autoload' => [
        'options'
    ]
];
