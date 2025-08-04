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

  Route::name('gallery.podcast-category.index')->get('/gallery/podcast-category', 'PodcastCategoryController@index');
  Route::name('gallery.podcast-category.create')->get('/gallery/podcast-category/create', 'PodcastCategoryController@create');
  Route::name('gallery.podcast-category.store')->post('/gallery/podcast-category', 'PodcastCategoryController@store');
  Route::name('gallery.podcast-category.edit')->get('/gallery/podcast-category/{podcastCategory}/edit', 'PodcastCategoryController@edit');
  Route::name('gallery.podcast-category.update')->put('/gallery/podcast-category/{podcastCategory}', 'PodcastCategoryController@update');
  Route::name('gallery.podcast-category.destroy')->delete('/gallery/podcast-category/{podcastCategory}', 'PodcastCategoryController@destroy');

  Route::resource('gallery', 'GalleryController');
  Route::name('gallery.waiting_approve_gallery')->get('/waiting-approve-gallery', 'GalleryController@index');
});
