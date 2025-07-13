<?php

use Illuminate\Support\Facades\Route;
use Modules\Royalty\Http\Controllers\Admin\RoyaltyController;

Route::group([
  'prefix' => admin_path(),
  'middleware' => ['web', 'admin'],
  'as' => 'admin.',
  'namespace' => 'Modules\\Royalty\\Http\\Controllers\\Admin'
], function () {
  // Category
  Route::name('royalty.category.index')->get('/royalty/category', 'CategoryController@index');
  Route::name('royalty.category.create')->get('/royalty/category/create', 'CategoryController@create');
  Route::name('royalty.category.store')->post('/royalty/category', 'CategoryController@store');
  Route::name('royalty.category.edit')->get('/royalty/category/{royaltyCategory}/edit', 'CategoryController@edit');
  Route::name('royalty.category.update')->put('/royalty/category/{royaltyCategory}', 'CategoryController@update');
  Route::name('royalty.category.destroy')->delete('/royalty/category/{royaltyCategory}', 'CategoryController@destroy');

  // Status
  Route::name('royalty.status.index')->get('/royalty/status', 'StatusController@index');
  Route::name('royalty.status.create')->get('/royalty/status/create', 'StatusController@create');
  Route::name('royalty.status.store')->post('/royalty/status', 'StatusController@store');
  Route::name('royalty.status.edit')->get('/royalty/status/{royaltyStatus}/edit', 'StatusController@edit');
  Route::name('royalty.status.update')->put('/royalty/status/{royaltyStatus}', 'StatusController@update');
  Route::name('royalty.status.destroy')->delete('/royalty/status/{royaltyStatus}', 'StatusController@destroy');

  // Royalty
  // Route::resource('royalty', 'RoyaltyController');
  Route::name('royalty.index')->get('/royalty', 'RoyaltyController@index');
  Route::name('royalty.create')->get('/royalty/create', 'RoyaltyController@create');
  Route::name('royalty.store')->post('/royalty', 'RoyaltyController@store');
  Route::name('royalty.edit')->get('/royalty/{royalty}/edit', 'RoyaltyController@edit');
  Route::name('royalty.update')->put('/royalty/{royalty}', 'RoyaltyController@update');
  Route::name('royalty.destroy')->delete('/royalty/{royalty}', 'RoyaltyController@destroy');
  Route::name('royalty.royalty.export')->get('/royalty/export', 'RoyaltyController@export');
});
