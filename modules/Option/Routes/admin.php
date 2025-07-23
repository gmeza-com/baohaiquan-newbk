<?php

use Illuminate\Support\Facades\Route;

Route::group([
  'prefix' => admin_path(),
  'as'     => 'admin.option.',
  'middleware' => ['web', 'admin'],
  'namespace' => 'Modules\\Option\\Http\\Controllers'
], function () {
  // Option
  Route::post('/option/', 'OptionController@save');
  Route::get('/option/', 'OptionController@index');

  // general
  Route::get('/option/general', 'OptionController@general');
  Route::get('/option/system', 'OptionController@system');
  Route::get('/option/update', 'OptionController@update');
  Route::post('/option/update', 'OptionController@update');

  // email template
  Route::get('/option/email', 'EmailController@email');
  Route::get('/option/email/{module}/{email}', 'EmailController@editEmailTemplate');
  Route::post('/option/email/{module}/{email}', 'EmailController@editEmailTemplate');
});
