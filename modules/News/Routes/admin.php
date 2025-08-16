<?php

use Illuminate\Support\Facades\Route;

Route::group([
  'prefix' => admin_path(),
  'middleware' => ['web', 'admin'],
  'as' => 'admin.',
  'namespace' => 'Modules\\News\\Http\\Controllers\\Admin'
], function () {

  // Category
  Route::name('post.category.index')->get('/post/category', 'CategoryController@index');
  Route::name('post.category.create')->get('/post/category/create', 'CategoryController@create');
  Route::name('post.category.store')->post('/post/category', 'CategoryController@store');
  Route::name('post.category.edit')->get('/post/category/{postCategory}/edit', 'CategoryController@edit');
  Route::name('post.category.update')->put('/post/category/{postCategory}', 'CategoryController@update');
  Route::name('post.category.destroy')->delete('/post/category/{postCategory}', 'CategoryController@destroy');

  //Revision
  Route::name('post.revision.index')->get('/post/revision', 'RevisionController@index');
  Route::name('post.revision.edit')->get('/post/revision/{postHistory}/edit', 'RevisionController@edit');
  Route::name('post.revision.update')->put('/post/revision/{postHistory}', 'RevisionController@update');


  // Post
  Route::resource('post', 'PostController');
  Route::name('post.slug')->get('/post/slug', 'PostController@slug');
  Route::name('post.waiting_approve_post')->get('/waiting-approve-post', 'PostController@index');
});
