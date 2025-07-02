<?php

Route::group([
    'prefix' => admin_path(),
    'namespace' => 'Modules\\User\\Http\\Controllers\\Admin',
    'middleware' => ['web', 'admin'],
    'as' => 'admin.',
], function () {
    Route::resource('user', 'UserController');
});
