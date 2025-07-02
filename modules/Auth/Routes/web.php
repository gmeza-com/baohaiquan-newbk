<?php

Route::group([
    'as'     => 'auth.',
    'middleware' => ['web'],
    'namespace' => 'Modules\\Auth\\Http\\Controllers'
], function () {
    // Login
    Route::name('index')
        ->get('/auth', 'AuthController@index');
    Route::post('/auth', 'AuthController@action');

    // Activation account
    Route::name('activation')
        ->get('/auth/activation/{token}', 'AuthController@activation');
    Route::name('reset')
        ->get('/auth/reset/{token}', 'AuthController@reset');

    // Logout
    Route::name('logout')
        ->get('/logout', 'AuthController@logout');
});
