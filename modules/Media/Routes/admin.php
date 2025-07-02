<?php

use Illuminate\Support\Facades\Route;

Route::group([
  'prefix' => admin_path() . '/media',
  'middleware' => ['web', 'admin'],
  'namespace' => 'Modules\\Media\\Http\\Controllers',
], function () {
  Route::name('admin.media.index')->get('/', 'MediaController@index');
});
