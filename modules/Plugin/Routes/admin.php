<?php

Route::group([
    'prefix' => admin_path(),
    'namespace' => 'Modules\\Plugin\\Http\\Controllers',
    'middleware' => ['web', 'admin'],
    'as' => 'admin.',
], function () {
    Route::name('plugin.index')->get('/plugin', 'PluginController@index');
});