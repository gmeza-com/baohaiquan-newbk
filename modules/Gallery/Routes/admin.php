<?php

use Illuminate\Support\Facades\Route;

Route::group([
  'prefix' => admin_path(),
  'middleware' => ['web', 'admin'],
  'as' => 'admin.',
  'namespace' => 'Modules\\Gallery\\Http\\Controllers\\Admin'
], function () {
  // Category
  Route::name('gallery.category.index')->get('/gallery/category', 'CategoryController@index');
  Route::name('gallery.category.create')->get('/gallery/category/create', 'CategoryController@create');
  Route::name('gallery.category.store')->post('/gallery/category', 'CategoryController@store');
  Route::name('gallery.category.edit')->get('/gallery/category/{galleryCategory}/edit', 'CategoryController@edit');
  Route::name('gallery.category.update')->put('/gallery/category/{galleryCategory}', 'CategoryController@update');
  Route::name('gallery.category.destroy')->delete('/gallery/category/{galleryCategory}', 'CategoryController@destroy');

  Route::resource('gallery', 'GalleryController');
});
