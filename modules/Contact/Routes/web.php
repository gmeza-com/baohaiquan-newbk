<?php

Route::group([
    'middleware' => ['web'],
    'namespace' => 'Modules\\Contact\\Http\\Controllers'
], function () {
    Route::get('/contact', 'ContactController@index');
    Route::post('/contact', 'ContactController@send');
    Route::get('/viewup', function(){
    	$data = [];
    	$viewup = new Modules\Option\Models\Option;
    	$item = $viewup::where('name','site_view')->first();
    	$data['value'] = $item->value + '1173';
    	$item->update($data);
    });
});