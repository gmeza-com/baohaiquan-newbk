<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => ['web'],
    'namespace' => 'Modules\\Gallery\\Http\\Controllers\\Web'
], function () {
    Route::name('gallery.category.shortlink')->get('gallery/collections', 'CategoryController@shortlink');
    Route::name('gallery.category.show')->get('/{slug}');
    Route::name('gallery.podcast-category.show')->get('podcast-category/{slug}',);

    Route::name('gallery.list.album')->get('gallery/albums', 'GalleryController@album');
    Route::name('gallery.list.video')->get('gallery/videos', 'GalleryController@video');
    Route::name('gallery.shortlink')->get('gallery', 'GalleryController@shortlink');
    Route::name('gallery.show')->get('gallery/{slug}', 'GalleryController@show');

    Route::get('/bao-in-hai-quan', 'NewspaperController@index');
    Route::get('/bao-in-hai-quan-3d', 'NewspaperController@index_3d');
    Route::get('/load_newspaper', 'NewspaperController@loadNewsPaper');
});
