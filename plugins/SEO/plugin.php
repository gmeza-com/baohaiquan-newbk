<?php

return [
    'name' => 'SEO',
    'slug' => 'seo',
    'description' => 'Search Engine Optimized',
    'status' => 1,
    'providers' => [
        \Plugins\SEO\Providers\ServiceProvider::class,
    ]
];