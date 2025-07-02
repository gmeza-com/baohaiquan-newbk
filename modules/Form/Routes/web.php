<?php

Route::group([
    'middleware' => ['web'],
    'namespace' => 'Modules\\Form\\Http\\Controllers\\Web'
], function () {
    Route::get('/form/{slug}', 'FormController@index');
    Route::post('/form/{slug}', 'FormController@store');
});