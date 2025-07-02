<?php

Route::group([
    'prefix' => admin_path(),
    'middleware' => ['web', 'admin'],
    'as' => 'admin.',
    'namespace' => 'Modules\\Form\\Http\\Controllers\Admin'
], function () {
    Route::get('/form/data', 'DataController@index');
    Route::get('/form/data/{formData}', 'DataController@show');
    Route::delete('/form/data/{formData}', 'DataController@destroy');
    Route::resource('form', 'FormController');
});