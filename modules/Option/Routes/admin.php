<?php

use Illuminate\Support\Facades\Route;

Route::group([
  'prefix' => admin_path() . '/option',
  'as'     => 'admin.option.',
  'middleware' => ['web', 'admin'],
  'namespace' => 'Modules\\Option\\Http\\Controllers'
], function () {
  // Option
  Route::post('/', 'OptionController@save');
  Route::get('/', 'OptionController@index');

  // general
  Route::get('/general', 'OptionController@general');
  Route::get('/system', 'OptionController@system');
  Route::get('/update', 'OptionController@update');
  Route::post('/update', 'OptionController@update');

  // email template
  Route::get('/email', 'EmailController@email');
  Route::get('/email/{module}/{email}', 'EmailController@editEmailTemplate');
  Route::post('/email/{module}/{email}', 'EmailController@editEmailTemplate');
});
