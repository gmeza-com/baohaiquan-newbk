<?php

Route::group([
    'prefix' => admin_path(),
    'middleware' => ['web', 'admin'],
    'as' => 'admin.',
    'namespace' => 'Modules\\Widget\\Http\\Controllers'
], function () {
    Route::resource('widget', 'WidgetController');
});