<?php

Route::get('/', 'HomeController@index');
Route::get('/home', 'HomeController@index');

// Route::get('/dqh', function() {
//     foreach (Modules\Gallery\Models\GalleryLanguage::with('gallery')->get() as $gallery) {
//         if($gallery->gallery->type === 'video') {
//             continue;
//         }
//         $gallery->update([
//             'content' => $gallery->content->map(function($item) {
//                 $item['picture'] = str_replace('baohaiquan.cnv.vn', 'baohaiquanvietnam.vn', $item['picture']);
//                 return $item;
//             })->toArray()
//         ]);
//     }
// });
