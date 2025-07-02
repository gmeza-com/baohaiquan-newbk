<?php

Route::group([
    'middleware' => ['web'],
    'namespace' => 'Modules\\News\\Http\\Controllers\\Web'
], function () {
    Route::get('blogs/search', 'PostController@search');

    Route::name('post.category.shortlink')->get('danh-muc', 'CategoryController@shortlink');
    Route::name('post.category.show')->get('danh-muc/{slug}', 'CategoryController@show');

    Route::name('post.shortlink')->get('tin-tuc/news', 'PostController@shortlink');
    Route::name('post.show')->get('tin-tuc/{slug}', 'PostController@show');

    Route::name('post.show.test')->get('tin-tuc/test/{slug}', 'PostController@test');

    Route::name('post.printer')->get('print/{slug}', 'PostController@printer');
});
