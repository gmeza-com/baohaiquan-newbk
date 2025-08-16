<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => admin_path(),
    'middleware' => ['api'],
    'as' => 'admin-api.',
    'namespace' => 'Modules\\News\\Http\\Controllers\\Admin',
], function () {


    Route::name('post.rest_search')->get('/rest/post/search', 'PostController@postSearch');
});
