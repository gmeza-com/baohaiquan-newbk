<?php

use Illuminate\Support\Facades\Route;

Route::group([
  'prefix' => admin_path(),
  'namespace' => 'Modules\\CustomField\\Http\\Controllers',
  'middleware' => ['web', 'admin'],
  'as' => 'admin.'
], function () {
  Route::resource('custom-field', 'FieldController');
  Route::resource('custom-field.type', 'TypeController');
});
