<?php

Route::group([
    'prefix' => admin_path(),
    'namespace' => 'Modules\\Activity\\Http\\Controllers',
    'middleware' => ['web', 'admin'],
    'as' => 'admin.',
], function () {
    Route::name('activity.index')->get('activity', 'ActivityController@index');
    Route::name('activity.clear')->delete('activity', 'ActivityController@index');
});