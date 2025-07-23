<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => ['api'],
    'namespace' => 'Modules\\Gallery\\Http\\Controllers\\API',
], function () {

    Route::name('api.gallery.category.get')->get('/iadmin/gallery/category/{id}', function ($id) {
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

    Route::name('api.longform.show')->post('/iadmin/longform/show', function () {
        $data = request()->input('editorjs_data');

        // Decode JSON string back to array/object
        if (is_string($data)) {
            $data = json_decode($data, true);
        }

        return view('gallery::admin.show', compact('data'));
    });

    Route::name('api.longform.preview')->post('/iadmin/longform/preview', function () {
        $data = request()->input('editorjs_data');

        // Chỉ encode JSON nếu là array, giữ nguyên kiểu dữ liệu khác
        $data = is_array($data) ? json_encode($data) : $data;

        return view('gallery::admin.preview', compact('data'));
    });
});
