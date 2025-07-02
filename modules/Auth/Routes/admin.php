<?php

Route::group([
    'prefix' => admin_path(),
    'as'     => 'module.auth.admin.',
    'middleware' => ['web', 'admin'],
    'namespace' => 'Modules\\Auth\\Http\\Controllers'
], function () {

});