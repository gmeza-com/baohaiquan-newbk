<?php

return [
  'name' => 'Royalty',
  'slug' => 'royalty',
  'version' => '1.3',
  'latest_version' => '1.3',
  'description' => 'Manage royalty on website',
  'status' => 1,
  'position' => 3,
  'providers' => [
    \Modules\Royalty\Providers\ServiceProvider::class
  ],
  'helpers_autoload' => [
    'royalty'
  ]
];
