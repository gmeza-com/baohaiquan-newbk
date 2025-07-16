<?php

use Illuminate\Support\Facades\Route;

Route::group([
  'middleware' => ['api'],
  'namespace' => 'Modules\\Page\\Http\\Controllers\\API',
  'prefix' => 'api'
], function () {
  // api blog
  Route::get('/search/gallery', function (\Illuminate\Http\Request $request, \Modules\Gallery\Repositories\GalleryRepository $galleryRepository) {
    $keyword = $request->get('q');
    $gallery = $galleryRepository->search($keyword);

    return response()->json([
      'status' => 200,
      'result' => $gallery->map(function ($g) {
        $result = [];
        $result['name'] = $g->name;

        foreach ($g->gallery->languages as $language) {
          $result['attributes'][] = [
            'attr' => 'language[' . $language->locale . '][name]',
            'value' => $language->name
          ];
        }
        $result['attributes'][] = [
          'attr' => 'attributes[url]',
          'value' => route('gallery.shortlink', ['id' => $g->gallery->id], false),
        ];

        return $result;
      })
    ]);
  });

  Route::get('/search/gallery/collection', function (\Illuminate\Http\Request $request, \Modules\Gallery\Repositories\GalleryCategoryRepository $galleryRepository) {
    $keyword = $request->get('q');
    $gallery = $galleryRepository->search($keyword);

    return response()->json([
      'status' => 200,
      'result' => $gallery->map(function ($g) {
        $result = [];
        $result['name'] = $g->name;

        foreach ($g->category->languages as $language) {
          $result['attributes'][] = [
            'attr' => 'language[' . $language->locale . '][name]',
            'value' => $language->name
          ];
        }
        $result['attributes'][] = [
          'attr' => 'attributes[url]',
          'value' => route('gallery.category.shortlink', ['id' => $g->category->id], false),
        ];

        return $result;
      })
    ]);
  });

});
