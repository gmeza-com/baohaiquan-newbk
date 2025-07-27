<?php

namespace Modules\Gallery\Http\Controllers\Admin;

use App\Http\Controllers\AdminController;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Modules\Gallery\Models\Gallery;
use Modules\Gallery\Models\GalleryLanguage;
use Yajra\DataTables\Facades\DataTables;
use Modules\Royalty\Models\RoyaltyCategory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;


class GalleryController extends AdminController
{
  public function index(Request $request)
  {
    if ($request->ajax()) {
      return $this->data($request);
    }

    $this->tpl->setData('title', trans('gallery::language.manager'));
    $this->tpl->setTemplate('gallery::admin.index');

    // breadcrumb
    $this->tpl->breadcrumb()->add('admin.gallery.index', trans('gallery::language.manager'));

    // datatable
    $filter = encrypt($request->only(['language', 'category', 'published']));
    $url = admin_route('gallery.index');
    $url = $url . '?filter=' . $filter;
    $this->tpl->datatable()->setSource($url);
    $this->tpl->datatable()->addColumn(
      '#',
      'id',
      ['class' => 'col-md-1']
    );
    $this->tpl->datatable()->addColumn(
      '<i class="fa fa-picture-o"></i>',
      'thumbnail',
      ['class' => 'col-md-2'],
      false,
      false
    );
    $this->tpl->datatable()->addColumn(
      trans('language.name'),
      'name',
      ['class' => 'col-md-4']
    );
    $this->tpl->datatable()->addColumn(
      trans('language.status'),
      'gallery.published',
      ['class' => 'col-md-2'],
      false,
      true
    );
    $this->tpl->datatable()->addColumn(
      trans('language.updated_at'),
      'gallery.updated_at'
    );

    return $this->tpl->render();
  }

  private function getGalleryTypeColor($type)
  {
    switch ($type) {
      case 'video':
        return 'info';
      case 'album':
        return 'success';
      case 'longform':
        return 'warning';
      default:
        return 'default';
    }
  }

  public function data(Request $request)
  {
    $filter = decrypt($request->get('filter'));
    $language = @$filter['language'] ?: config('cnv.language_default');
    $model = GalleryLanguage::with([
      'gallery',
      'gallery.categories.languages' => function ($query) use ($language) {
        $query->where('locale', $language);
      }
    ])->where('locale', $language);


    $currentUser = Auth::user();
    $user_id = $currentUser->id;

    if (allow('gallery.gallery.only_show_my_post') && !$currentUser->is_super_admin) {
      $model = $model->whereHas('gallery', function ($query) use ($user_id) {
        $query->where('user_id', $user_id);
      });
    }


    if (@$filter['category'] && @$filter['category'] !== '*') {
      $model->whereHas('gallery', function ($query) use ($filter) {
        $query->whereHas('categories', function ($query) use ($filter) {
          $query->where('id', @$filter['category']);
        });
      });
    }

    return Datatables::of($model)
      ->editColumn('thumbnail', function ($model) {
        return sprintf('<div class="%s"><img src="%s" width="120" class="img-rounded"></div>', 'text-center', $model->thumbnail);
      })
      ->editColumn('name', function ($model) {
        $html = '<strong>';
        $html .= sprintf(
          '<span class="label label-%s">%s</span> ',
          $this->getGalleryTypeColor($model->gallery->type),
          $model->gallery->type
        );
        $html .= link_to_route('admin.gallery.edit', $model->name, ['gallery' => $model->gallery->id]);
        $html .= '</strong>';
        $html .= '<p>' . $model->description . '</p>';

        return $html;
      })
      ->addColumn('action', function ($model) {
        app('helper')->load('buttons');
        $button = [];

        $button[] = [
          'route' => $model->gallery->categories->first()->languages->first()->slug . ($model->gallery->type == 'album' ? '?newspaper=' . $model->gallery->languages->first()->slug : '/' .  $model->gallery->languages->first()->slug),
          'name' => trans('language.show'),
          'icon' => 'fa fa-eye',
          'attributes' => [
            'class' => 'btn btn-xs btn-warning',
            'target' => '_blank'
          ],
        ];

        // edit role
        if (allow('gallery.gallery.edit')) {
          $button[] = [
            'route' => admin_route('gallery.edit', $model->gallery->id),
            'name' => trans('language.edit'),
            'icon' => 'fa fa-pencil-square-o',
            'attributes' => [
              'class' => 'btn btn-xs btn-primary'
            ]
          ];
        }

        // delete
        if (allow('gallery.gallery.destroy')) {
          $button[] = [
            'route' => 'javascript:void(0);',
            'name' => trans('language.delete'),
            'icon' => 'fa fa-trash-o',
            'attributes' => [
              'class' => 'btn btn-xs btn-danger',
              'data-url' => admin_route('gallery.destroy', $model->gallery->id),
              'data-action' => 'confirm_to_delete',
              'data-message' => trans('language.confirm_to_delete')
            ]
          ];
        }

        return cnv_action_block($button);
      })
      ->editColumn('gallery.published', function ($model) {
        return sprintf(
          '<span class="label label-%s">%s</span>',
          $model->gallery->published ? 'success' : 'warning',
          $model->gallery->published ? trans('language.published') : trans('language.trashed')
        );
      })
      ->editColumn('gallery.updated_at', function ($model) {
        return Carbon::parse($model->gallery->updated_at)->format('d/m/Y H:i');
      })
      ->rawColumns(['name', 'action', 'gallery.published', 'thumbnail'])
      ->make(true);
  }

  public function create(Request $request, Gallery $gallery)
  {
    if ($request->ajax()) {
      return $this->getForm($request->get('type'), $gallery);
    }

    $gallery->published_at = Carbon::now();
    $gallery->published = true;

    $this->tpl->setData('title', trans('gallery::language.gallery_create'));
    $this->tpl->setData('gallery', $gallery);
    $this->tpl->setTemplate('gallery::admin.create');

    // breadcrumb
    $this->tpl->breadcrumb()->add(admin_route('gallery.index'), trans('gallery::language.manager'));
    $this->tpl->breadcrumb()->add(admin_route('gallery.create'), trans('gallery::language.gallery_create'));

    return $this->tpl->render();
  }

  public function store(Request $request)
  {
    // Check if this is an AJAX request or regular form submission
    $isAjax = $request->ajax();

    $data = $request->except(['_token', 'language']);

    $data['featured'] = $request->has('featured') ? true : false;
    $data['published'] = $request->has('published') ? true : false;
    $data['published_at'] = Carbon::createFromFormat('d-m-Y H:i', $this->getDatetimeOrCreateFromNow($request));

    $data['category'] = @$data['category'] ?: [];
    // required category
    if (!$data['category']) {
      if ($isAjax) {
        return response()->json([
          'status' => 500,
          'message' => trans('gallery::language.required_categories'),
        ]);
      } else {
        return redirect()->back()->withErrors([trans('gallery::language.required_categories')]);
      }
    }

    // Validate podcast_category when type is audio
    if ($data['type'] === 'audio' && empty($data['podcast_category'])) {
      if ($isAjax) {
        return response()->json([
          'status' => 500,
          'message' => 'Podcast category is required when type is audio.',
        ]);
      } else {
        return redirect()->back()->withErrors(['Podcast category is required when type is audio.']);
      }
    }

    $languages = $request->only('language');

    // Xử lý tạo unique slug cho tất cả languages
    $languages = $this->processLanguagesWithUniqueSlug($languages);

    if (! @$languages['language']['vi']['content'] && $data['type'] != 'content') {
      if ($isAjax) {
        return response()->json([
          'status' => 500,
          'message' => 'Các trường album hoặc video bị thiếu, không thể lưu !',
        ]);
      } else {
        return redirect()->back()->withErrors(['Các trường album hoặc video bị thiếu, không thể lưu !']);
      }
    }

    if (! @$languages['language']['vi']['post_content'] && $data['type'] == 'content') {
      if ($isAjax) {
        return response()->json([
          'status' => 500,
          'message' => 'Trường nội dung bị thiếu, không thể lưu !',
        ]);
      } else {
        return redirect()->back()->withErrors(['Trường nội dung bị thiếu, không thể lưu !']);
      }
    }

    try {
      if ($gallery = Gallery::create($data)) {

        // save royalty
        $this->updateRoyalty($request, $gallery);

        $gallery->saveLanguages($languages);

        $gallery->categories()->sync($data['category']);

        $gallery->podcast_categories()->sync($data['podcast_category']);

        Cache::flush();

        if ($isAjax) {
          return response()->json([
            'status' => 200,
            'message' => trans('language.create_success'),
            'redirect_url' => admin_route('gallery.edit', $gallery->id)
          ]);
        } else {
          return redirect()->route('admin.gallery.edit', $gallery->id)->with('success', trans('language.create_success'));
        }
      } else {
        if ($isAjax) {
          return response()->json([
            'status' => 500,
            'message' => trans('language.update_fail'),
          ]);
        } else {
          return redirect()->back()->withErrors([trans('language.update_fail')]);
        }
      }
    } catch (\Exception $e) {
      if ($isAjax) {
        return response()->json([
          'status' => 500,
          'message' => 'Đã xảy ra lỗi khi lưu gallery: ' . $e->getMessage(),
        ]);
      } else {
        return redirect()->back()->withErrors(['Đã xảy ra lỗi khi lưu gallery: ' . $e->getMessage()]);
      }
    }
  }

  public function edit(Request $request, Gallery $gallery)
  {
    if ((allow('gallery.gallery.only_show_my_post') && !Auth::user()->is_super_admin && $gallery->user_id !== Auth::user()->id)) {
      abort(403);
    }

    if ($request->ajax()) {
      return $this->getForm($request->get('type'), $gallery);
    }

    $this->tpl->setData('title', trans('gallery::language.gallery_edit'));
    $this->tpl->setData('gallery', $gallery);
    $this->tpl->setTemplate('gallery::admin.edit');

    // breadcrumb
    $this->tpl->breadcrumb()->add(admin_route('gallery.index'), trans('gallery::language.manager'));
    $this->tpl->breadcrumb()->add(admin_route('gallery.edit', $gallery->id), trans('gallery::language.gallery_edit'));

    return $this->tpl->render();
  }

  public function update(Request $request, Gallery $gallery)
  {
    if ((allow('gallery.gallery.only_show_my_post') && !Auth::user()->is_super_admin && $gallery->user_id !== Auth::user()->id)) {
      abort(403);
    }

    // Check if this is an AJAX request or regular form submission
    $isAjax = $request->ajax();
    error_log("[GalleryController@update] Is AJAX request: " . ($isAjax ? 'true' : 'false'));

    $data = $request->except(['_token', 'language']);

    $data['featured'] = $request->has('featured') ? true : false;
    $data['published'] = $request->has('published') ? true : false;
    $data['published_at'] = Carbon::createFromFormat('d-m-Y H:i', $this->getDatetimeOrCreateFromNow($request));

    $data['category'] = @$data['category'] ?: [];
    // required category
    if (!$data['category']) {
      if ($isAjax) {
        return response()->json([
          'status' => 500,
          'message' => trans('gallery::language.required_categories'),
        ]);
      } else {
        return redirect()->back()->withErrors([trans('gallery::language.required_categories')]);
      }
    }


    // Validate podcast_category when type is audio
    // if ($data['type'] === 'audio' && empty($data['podcast_category'])) {
    //   if ($isAjax) {
    //     return response()->json([
    //       'status' => 500,
    //       'message' => 'Podcast category is required when type is audio.',
    //     ]);
    //   } else {
    //     return redirect()->back()->withErrors(['Podcast category is required when type is audio.']);
    //   }
    // }

    $languages = $request->only('language');

    // Xử lý tạo unique slug cho tất cả languages (exclude current gallery)
    $languages = $this->processLanguagesWithUniqueSlug($languages, $gallery->id);

    if (! @$languages['language']['vi']['content'] && $data['type'] != 'content') {
      if ($isAjax) {
        return response()->json([
          'status' => 500,
          'message' => 'Các trường album hoặc video bị thiếu, không thể lưu !',
        ]);
      } else {
        return redirect()->back()->withErrors(['Các trường album hoặc video bị thiếu, không thể lưu !']);
      }
    }

    if (! @$languages['language']['vi']['post_content'] && $data['type'] == 'content') {
      if ($isAjax) {
        return response()->json([
          'status' => 500,
          'message' => 'Trường nội dung bị thiếu, không thể lưu !',
        ]);
      } else {
        return redirect()->back()->withErrors(['Trường nội dung bị thiếu, không thể lưu !']);
      }
    }

    if ($gallery->update($data)) {
      $this->updateRoyalty($request, $gallery);

      $gallery->saveLanguages($languages);
      $gallery->categories()->sync($data['category']);
      $gallery->podcast_categories()->sync($data['podcast_category']);

      Cache::flush();
      if ($isAjax) {
        return response()->json([
          'status' => 200,
          'message' => trans('language.update_success'),
          'redirect_url' => admin_route('gallery.edit', $gallery->id)
        ]);
      } else {
        return redirect()->route('admin.gallery.edit', $gallery->id)->with('success', trans('language.update_success'));
      }
    } else {
      if ($isAjax) {
        return response()->json([
          'status' => 500,
          'message' => trans('language.update_fail'),
        ]);
      } else {
        return redirect()->back()->withErrors([trans('language.update_fail')]);
      }
    }
  }

  protected function updateRoyalty(Request $request, Gallery $gallery)
  {
    // check if the add-royalty is enabled
    $isAdd = $request->input('add-royalty');
    if ($isAdd) {
      $royalty = $request->input('royalty');
      if (isset($royalty['id']) && $royalty['id'] > 0) {
        // update royalty if status_id == 1
        if ($royalty['status_id'] == 1) {
          $royalty['amount'] = RoyaltyCategory::query()
            ->where('id', $royalty['category_id'])
            ->value('amount');

          $gallery->royalties()->updateOrCreate(
            ['id' => $royalty['id']],
            [
              'user_id' => $royalty['user_id'],
              'category_id' => $royalty['category_id'],
              'status_id' => $royalty['status_id'],
              'amount' => $royalty['amount'],
              'month' => $royalty['month']
            ]
          );
        }
      } else {
        // create royalty. must get the amount from category_id
        $royalty['amount'] = RoyaltyCategory::query()
          ->where('id', $royalty['category_id'])
          ->value('amount');

        $gallery->royalties()->create([
          'user_id' => $royalty['user_id'],
          'category_id' => $royalty['category_id'],
          'status_id' => $royalty['status_id'],
          'amount' => $royalty['amount'],
          'month' => $royalty['month']
        ]);
      }
    } else {
      // update the royalty to status_id = 4
      $gallery->royalties()->update(['status_id' => 4]);
    }
  }

  public function destroy(Request $request, Gallery $gallery)
  {
    if (! $request->ajax()) {
      return;
    }

    if ((allow('gallery.gallery.only_show_my_post') && !Auth::user()->is_super_admin && $gallery->user_id !== Auth::user()->id)) {
      abort(403);
    }

    if ($gallery->delete()) {

      Cache::flush();
      return response()->json([
        'status' => 200,
        'message' => trans('language.delete_success')
      ]);
    } else {
      return response()->json([
        'status' => 500,
        'message' => trans('language.delete_fail'),
      ]);
    }
  }

  protected function getForm($type, $gallery)
  {

    if (in_array($type, ['video', 'audio'])) {
      return view('gallery::admin.video', compact('gallery', 'type'));
    }

    if ($type == 'longform') {
      return view('gallery::admin.longform', compact('gallery'));
    }

    if ($type == 'content') {
      return view('gallery::admin.content', compact('gallery'));
    }

    return view('gallery::admin.album', compact('gallery'));
  }

  protected function getDatetimeOrCreateFromNow(Request $request)
  {
    $date = $request->has('date_published') ? $request->input('date_published') : date('d-m-Y');
    $time = $request->has('time_published') ? $request->input('time_published') : '00:00';

    return $date . ' ' . $time;
  }

  /**
   * Generate unique slug for a given locale and base slug
   */
  private function generateUniqueSlug($locale, $baseSlug, $excludeGalleryId = null)
  {
    $slug = $baseSlug;
    $counter = 1;
    
    while (true) {
      $query = GalleryLanguage::query()
        ->whereLocale($locale)
        ->whereSlug($slug);
      
      if ($excludeGalleryId) {
        $query->whereHas('gallery', function($q) use ($excludeGalleryId) {
          $q->where('id', '!=', $excludeGalleryId);
        });
      }
      
      if (!$query->exists()) {
        return $slug;
      }
      
      $slug = $baseSlug . '-' . $counter;
      $counter++;
    }
  }

  /**
   * Process languages data to generate unique slugs
   */
  private function processLanguagesWithUniqueSlug($languages, $excludeGalleryId = null)
  {
    foreach ($languages['language'] as $locale => $dataLanguage) {
      $baseSlug = isset($dataLanguage['slug']) ? $dataLanguage['slug'] : Str::slug($dataLanguage['name']);
      
      // Tự động tạo unique slug
      $languages['language'][$locale]['slug'] = $this->generateUniqueSlug($locale, $baseSlug, $excludeGalleryId);
    }
    
    return $languages;
  }
}
