<?php

use Illuminate\Support\Facades\Route;

Route::group([
  'prefix' => admin_path() . '/acl',
  'as'     => 'admin.acl.',
  'middleware' => ['web', 'admin'],
  'namespace' => 'Modules\\Acl\\Http\\Controllers'
], function () {
  Route::resource('role', 'RoleController', ['except' => ['show']]);
  Route::resource('permission', 'PermissionController', ['except' => ['show']]);
});
