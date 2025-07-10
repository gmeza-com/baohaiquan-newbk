<?php

use Illuminate\Support\Facades\Route;

Route::group([
  'prefix' => admin_path(),
  'namespace' => 'Modules\\Dashboard\\Http\\Controllers',
  'middleware' => ['web', 'admin'],
  'as' => 'admin.'
], function () {
  Route::get('/', 'DashboardController@index');
});
