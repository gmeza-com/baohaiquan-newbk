<?php

namespace Modules\Gallery\Http\Controllers\Admin;

use App\Http\Controllers\AdminController;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Modules\Gallery\Models\Gallery;
use Modules\Gallery\Models\GalleryLanguage;
use Yajra\DataTables\Facades\DataTables;

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
    $model = GalleryLanguage::with('gallery')->where('locale', $language);
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
          'route' => $model->gallery->type == 'video' ? route('gallery.show', $model->slug) : url('/bao-in-hai-quan?id=' . $model->gallery->id . '&year=' . Carbon::parse($model->gallery->published_at)->format('Y')),
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
    if (! $request->ajax()) {
      return;
    }

    $data = $request->except(['_token', 'language']);

    $data['featured'] = $request->has('featured') ? true : false;
    $data['published'] = $request->has('published') ? true : false;
    $data['published_at'] = Carbon::createFromFormat('d-m-Y H:i', $this->getDatetimeOrCreateFromNow($request));

    $data['category'] = @$data['category'] ?: [];
    // required category
    if (!$data['category']) {
      return response()->json([
        'status' => 500,
        'message' => trans('gallery::language.required_categories'),
      ]);
    }

    $languages = $request->only('language');

    if (! @$languages['language']['vi']['content']) {
      return response()->json([
        'status' => 500,
        'message' => 'Các trường album hoặc video bị thiếu, không thể lưu !',
      ]);
    }

    if ($gallery = Gallery::create($data)) {
      $gallery->saveLanguages($languages);
      $gallery->categories()->sync($data['category']);

      Cache::flush();
      return response()->json([
        'status' => 200,
        'message' => trans('language.create_success'),
        'redirect_url' => admin_route('gallery.edit', $gallery->id)
      ]);
    } else {
      return response()->json([
        'status' => 500,
        'message' => trans('language.update_fail'),
      ]);
    }
  }

  public function edit(Request $request, Gallery $gallery)
  {
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
    if (! $request->ajax()) {
      return;
    }

    $data = $request->except(['_token', 'language']);

    $data['featured'] = $request->has('featured') ? true : false;
    $data['published'] = $request->has('published') ? true : false;
    $data['published_at'] = Carbon::createFromFormat('d-m-Y H:i', $this->getDatetimeOrCreateFromNow($request));

    $data['category'] = @$data['category'] ?: [];
    // required category
    if (!$data['category']) {

      Cache::flush();
      return response()->json([
        'status' => 500,
        'message' => trans('gallery::language.required_categories'),
      ]);
    }

    $languages = $request->only('language');

    if (! @$languages['language']['vi']['content']) {
      return response()->json([
        'status' => 500,
        'message' => 'Các trường album hoặc video bị thiếu, không thể lưu !',
      ]);
    }

    if ($gallery->update($data)) {
      $gallery->saveLanguages($languages);
      $gallery->categories()->sync($data['category']);

      Cache::flush();
      return response()->json([
        'status' => 200,
        'message' => trans('language.update_success'),
        'redirect_url' => admin_route('gallery.edit', $gallery->id)
      ]);
    } else {
      return response()->json([
        'status' => 500,
        'message' => trans('language.update_fail'),
      ]);
    }
  }

  public function destroy(Request $request, Gallery $gallery)
  {
    if (! $request->ajax()) {
      return;
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

    return view('gallery::admin.album', compact('gallery'));
  }

  protected function getDatetimeOrCreateFromNow(Request $request)
  {
    $date = $request->has('date_published') ? $request->input('date_published') : date('d-m-Y');
    $time = $request->has('time_published') ? $request->input('time_published') : '00:00';

    return $date . ' ' . $time;
  }

  public function show(Request $request, Gallery $gallery)
  {
    $locale = $request->get('locale', config('cnv.language_default'));

    $this->tpl->setData('title', 'Xem trước: ' . $gallery->language('name', $locale));
    $this->tpl->setData('gallery', $gallery);
    $this->tpl->setData('locale', $locale);
    $this->tpl->setTemplate('gallery::admin.show');

    // breadcrumb
    $this->tpl->breadcrumb()->add(admin_route('gallery.index'), trans('gallery::language.manager'));
    $this->tpl->breadcrumb()->add(admin_route('gallery.edit', $gallery->id), trans('gallery::language.gallery_edit'));
    $this->tpl->breadcrumb()->add(admin_route('gallery.show', $gallery->id), 'Xem trước');

    return $this->tpl->render();
  }
}
