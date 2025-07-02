<?php

return [
    'name' => 'Acl',
    'slug' => 'acl',
    'version' => '1.0',
    'latest_version' => '1.0',
    'description' => 'ALC - Access List Control, manage all roles and permissions on website.',
    'status' => 1,
    'postition' => 0,
    'providers' => [
        \Modules\Acl\Providers\ServiceProvider::class,
        \Modules\Acl\Providers\AuthServiceProvider::class,
    ],
    'helpers_autoload' => [
        'acl'
    ]
];