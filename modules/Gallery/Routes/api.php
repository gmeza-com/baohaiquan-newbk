<?php

use Illuminate\Support\Facades\Route;
use Modules\Gallery\Models\Gallery;

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


    Route::name('api.gallery.category.get')->get('/gallery/category/{id}', function ($id) {
        try {
            $category = \Modules\Gallery\Models\GalleryCategory::with('languages')->find($id);

            if (!$category) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Category not found',
                    'result' => null
                ], 404);
            }

            return response()->json([
                'status' => 200,
                'result' => [
                    'id' => $category->id,
                    'name' => $category->language('name'),
                    'slug' => $category->language('slug'),
                    'published' => $category->published,
                    'thumbnail' => $category->thumbnail,
                    'languages' => $category->languages,
                    'created_at' => $category->created_at,
                    'updated_at' => $category->updated_at
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Internal server error',
                'result' => null
            ], 500);
        }
    });
});
