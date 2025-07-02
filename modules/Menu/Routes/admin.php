<?php

Route::group([
    'prefix' => admin_path(),
    'as'     => 'admin.',
    'middleware' => ['web', 'admin'],
    'namespace' => 'Modules\\Menu\\Http\\Controllers'
], function () {

    Route::resource('menu', 'MenuController', ['except' => ['show']]);
    Route::resource('menu.item', 'ItemController', ['except' => ['show']]);
    Route::name('menu.item.sort')->put('/menu/{menu}/item', 'ItemController@sort');
});
