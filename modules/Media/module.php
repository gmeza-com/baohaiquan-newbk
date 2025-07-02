<?php

return [
    'name' => 'Media',
    'slug' => 'media',
    'version' => '1.0',
    'latest_version' => '1.0',
    'description' => 'File manager for website',
    'status' => 1,
    'position' => 0,
    'providers' => [
        \Modules\Media\Providers\ServiceProvider::class,
    ],
    'helpers_autoload' => [
        // helpers here
    ]
];
