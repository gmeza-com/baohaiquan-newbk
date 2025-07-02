<?php

Route::group([
    'prefix' => admin_path(),
    'namespace' => 'Modules\\Module\\Http\\Controllers',
    'middleware' => ['web', 'admin'],
    'as' => 'admin.',
], function () {

    Route::name('module.store')->get('/module/market', 'ModuleController@market');
    Route::name('module.install')->get('/module/install', 'ModuleController@install');
    Route::name('module.do_install')->post('/module/install', 'ModuleController@install');


    Route::name('module.index')->get('/module', 'ModuleController@index');
    Route::name('module.activate')->post('/module/{slug}/activate', 'ModuleController@activate');
    Route::name('module.deactivate')->post('/module/{slug}/deactivate', 'ModuleController@deactivate');
    Route::name('module.destroy')->post('/module/{slug}/remove', 'ModuleController@destroy');
});