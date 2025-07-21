<?php

namespace Modules\Gallery\Http\Controllers\Admin;

use App\Http\Controllers\AdminController;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Gallery\Models\PodcastCategory;
use Illuminate\Support\Facades\Cache;
use Yajra\DataTables\Facades\DataTables;

class PodcastCategoryController extends AdminController
{
  /**
   * @param Request $request
   * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
   */
  public function index(Request $request)
  {
    if ($request->ajax()) {
      return $this->data($request);
    }

    $this->tpl->setData('title', trans('gallery::language.manager_podcast_category'));
    $this->tpl->setData('type', 'podcast');
    $this->tpl->setTemplate('gallery::admin.category.index');

    // breadcrumb
    $this->tpl->breadcrumb()->add('admin.gallery.podcast-category.index', trans('gallery::language.manager_podcast_category'));

    // datatable
    $this->tpl->datatable()->setSource(admin_route('gallery.podcast-category.index') . '?language=' . $request->get('language'));
    $this->tpl->datatable()->addColumn(
      '#',
      'id',
      ['class' => 'col-md-1'],
      false,
      false
    );
    $this->tpl->datatable()->addColumn(
      trans('language.name'),
      'name',
      ['class' => 'col-md-6'],
      false,
      false
    );
    $this->tpl->datatable()->addColumn(
      trans('language.published'),
      'published',
      ['class' => 'col-md-2'],
      false,
      false
    );
    $this->tpl->datatable()->addColumn(
      trans('language.updated_at'),
      'updated_at',
      [],
      false,
      false
    );

    return $this->tpl->render();
  }

  /**
   * @param Request $request
   * @return mixed
   */
  public function data(Request $request)
  {
    $language = $request->has('language') ? $request->get('language') : config('cnv.language_default');
    $model = (new PodcastCategory());

    return Datatables::of($model->query())
      ->editColumn('name', function ($model)  use ($language) {
        return link_to_route('admin.gallery.podcast-category.edit', $model->language('name'), ['podcastCategory' => $model->id]);
      })
      ->addColumn('action', function ($model) use ($language) {
        app('helper')->load('buttons');
        $button = [];

        // $button[] = [
        //   'route' => route('gallery.category.show', $model->language('slug', $language)),
        //   'name' => trans('language.show'),
        //   'icon' => 'fa fa-eye',
        //   'attributes' => [
        //     'class' => 'btn btn-xs btn-warning',
        //     'target' => '_blank'
        //   ],
        // ];

        // edit role
        if (allow('gallery.podcast-category.edit')) {
          $button[] = [
            'route' => admin_route('gallery.podcast-category.edit', $model->id),
            'name' => trans('language.edit'),
            'icon' => 'fa fa-pencil-square-o',
            'attributes' => [
              'class' => 'btn btn-xs btn-primary'
            ]
          ];
        }

        // delete
        if (allow('gallery.podcast-category.destroy')) {
          $button[] = [
            'route' => 'javascript:void(0);',
            'name' => trans('language.delete'),
            'icon' => 'fa fa-trash-o',
            'attributes' => [
              'class' => 'btn btn-xs btn-danger',
              'data-url' => admin_route('gallery.podcast-category.destroy', $model->id),
              'data-action' => 'confirm_to_delete',
              'data-message' => trans('language.confirm_to_delete')
            ]
          ];
        }

        return cnv_action_block($button);
      })
      ->editColumn('published', function ($model) {
        return sprintf(
          '<span class="label label-%s">%s</span>',
          $model->published ? 'success' : 'warning',
          $model->published ? trans('language.published') : trans('language.trashed')
        );
      })
      ->editColumn('updated_at', function ($model) {
        return Carbon::parse($model->updated_at)->format('d/m/Y H:i');
      })
      ->rawColumns(['name', 'action', 'published'])
      ->make(true);
  }

  public function create(PodcastCategory $category)
  {
    $this->tpl->setData('title', trans('gallery::language.podcast_category_create'));
    $this->tpl->setData('category', $category);
    $this->tpl->setTemplate('gallery::admin.podcast-category.create');

    $category->published = true;

    // breadcrumb
    $this->tpl->breadcrumb()->add(admin_route('gallery.podcast-category.index'), trans('gallery::language.manager_podcast_category'));
    $this->tpl->breadcrumb()->add(admin_route('gallery.podcast-category.create'), trans('gallery::language.podcast_category_create'));

    return $this->tpl->render();
  }

  public function store(Request $request)
  {
    $data = $request->except(['_token', 'language']);

    /** @var PodcastCategory $category */
    if ($category = PodcastCategory::create($data)) {
      $category->saveLanguages($request->only('language'));

      Cache::flush();
      return response()->json([
        'status' => 200,
        'message' => trans('language.update_success'),
        'redirect_url' => admin_route('gallery.podcast-category.edit', $category->id)
      ]);
    } else {
      return response()->json([
        'status' => 500,
        'message' => trans('language.update_fail'),
      ]);
    }
  }

  public function edit(PodcastCategory $podcastCategory)
  {
    $this->tpl->setData('title', trans('gallery::language.podcast_category_edit'));
    $this->tpl->setData('category', $podcastCategory);
    $this->tpl->setTemplate('gallery::admin.podcast-category.edit');

    // breadcrumb
    $this->tpl->breadcrumb()->add(admin_route('gallery.podcast-category.index'), trans('gallery::language.manager_podcast_category'));
    $this->tpl->breadcrumb()->add(admin_route('gallery.podcast-category.edit', $podcastCategory->id), trans('gallery::language.podcast_category_edit'));

    return $this->tpl->render();
  }

  public function update(Request $request, PodcastCategory $podcastCategory)
  {
    if (! $request->ajax()) {
      return;
    }

    $data = $request->except(['_token', 'language']);
    $data['published'] = $request->has('published') ? true : false;
    $podcastCategory->update($data);
    $podcastCategory->saveLanguages($request->only('language'));

    Cache::flush();
    return response()->json([
      'status' => 200,
      'message' => trans('language.update_success'),
    ]);
  }

  /**
   * Delele source
   * @param Request $request
   * @param PodcastCategory $podcastCategory
   * @return mixed
   */
  public function destroy(Request $request, PodcastCategory $podcastCategory)
  {
    if (! $request->ajax()) {
      return;
    }

    $podcastCategory->gallery()->delete();
    if ($podcastCategory->delete()) {
      Cache::flush();
      return response()->json([
        'status' => 200,
        'message' => trans('language.delete_success')
      ]);
    } else {
      return response()->json([
        'status' => 500,
        'message' => trans('language.delete_fail')
      ]);
    }
  }
}
