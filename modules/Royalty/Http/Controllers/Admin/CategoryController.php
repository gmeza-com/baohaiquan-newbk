<?php

namespace Modules\Royalty\Http\Controllers\Admin;

use App\Http\Controllers\AdminController;
use Illuminate\Support\Number;
use Illuminate\Http\Request;
use Modules\Royalty\Models\RoyaltyCategory;
use Illuminate\Support\Facades\Cache;
use Yajra\DataTables\Facades\DataTables;

use function Laravel\Prompts\error;

class CategoryController extends AdminController
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

    $this->tpl->setData('title', trans('royalty::language.manager_category'));
    $this->tpl->setTemplate('royalty::admin.category.index');

    // breadcrumb
    $this->tpl->breadcrumb()->add('admin.royalty.category.index', trans('gallery::language.manager_category'));

    // datatable
    $this->tpl->datatable()->setSource(admin_route('royalty.category.index'));
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
      ['class' => 'col-md-2'],
      true,
      false
    );
    $this->tpl->datatable()->addColumn(
      trans('royalty::language.amount'),
      'amount',
      ['class' => 'col-md-2'],
      true,
      true
    );
    $this->tpl->datatable()->addColumn(
      trans('language.status'),
      'active',
      [],
      false,
      true
    );

    return $this->tpl->render();
  }

  /**
   * @param Request $request
   * @return mixed
   */
  public function data(Request $request)
  {

    $model = (new RoyaltyCategory());
    return Datatables::of($model->query())
      ->editColumn('name', function ($model) {
        return allow('royalty.category.edit') ? link_to_route('admin.royalty.category.edit', $model->name, ['royaltyCategory' => $model->id]) : $model->name;
      })
      ->addColumn('action', function ($model) {
        app('helper')->load('buttons');
        $button = [];

        // edit role
        if (allow('royalty.category.edit')) {
          $button[] = [
            'route' => admin_route('royalty.category.edit', $model->id),
            'name' => trans('language.edit'),
            'icon' => 'fa fa-pencil-square-o',
            'attributes' => [
              'class' => 'btn btn-xs btn-primary'
            ]
          ];
        }

        // delete
        if (allow('royalty.category.destroy')) {
          $button[] = [
            'route' => 'javascript:void(0);',
            'name' => trans('language.delete'),
            'icon' => 'fa fa-trash-o',
            'attributes' => [
              'class' => 'btn btn-xs btn-danger',
              'data-url' => admin_route('royalty.category.destroy', $model->id),
              'data-action' => 'confirm_to_delete',
              'data-message' => trans('language.confirm_to_delete')
            ]
          ];
        }

        return cnv_action_block($button);
      })
      ->editColumn('active', function ($model) {
        return sprintf(
          '<span class="label label-%s">%s</span>',
          $model->active ? 'success' : 'warning',
          $model->active ? trans('language.activate') : trans('language.deactivated')
        );
      })
      ->editColumn('amount', function ($model) {
        return Number::currency($model->amount, 'VND', 'vi_VN');
      })
      ->rawColumns(['name', 'action', 'active'])
      ->make(true);
  }

  public function create(RoyaltyCategory $category)
  {
    $this->tpl->setData('title', trans('gallery::language.category_create'));
    $this->tpl->setData('category', $category);
    $this->tpl->setTemplate('royalty::admin.category.create');

    $category->published = true;

    // breadcrumb
    $this->tpl->breadcrumb()->add(admin_route('royalty.category.index'), trans('royalty::language.manager_category'));
    $this->tpl->breadcrumb()->add(admin_route('royalty.category.create'), trans('language.create'));

    return $this->tpl->render();
  }

  public function store(Request $request)
  {
    $data = $request->except(['_token']);
    $data['active'] = $request->has('active') ? 1 : 0;

    if ($category = RoyaltyCategory::create($data)) {
      Cache::flush();
      return response()->json([
        'status' => 200,
        'message' => trans('language.update_success'),
        'redirect_url' => admin_route('royalty.category.edit', $category->id)
      ]);
    } else {
      return response()->json([
        'status' => 500,
        'message' => trans('language.update_fail'),
      ]);
    }
  }

  public function edit(RoyaltyCategory $royaltyCategory)
  {
    $this->tpl->setData('title', trans('royalty::language.category_edit'));
    $this->tpl->setData('category', $royaltyCategory);
    $this->tpl->setTemplate('royalty::admin.category.edit');

    // breadcrumb
    $this->tpl->breadcrumb()->add(admin_route('royalty.category.index'), trans('royalty::language.manager_category'));
    $this->tpl->breadcrumb()->add(admin_route('royalty.category.edit', $royaltyCategory->id), trans('language.edit'));

    return $this->tpl->render();
  }

  public function update(Request $request, RoyaltyCategory $royaltyCategory)
  {
    if (! $request->ajax())  return;

    $data = $request->except(['_token']);
    $data['active'] = $request->has('active') ? 1 : 0;

    $royaltyCategory->update($data);

    Cache::flush();
    return response()->json([
      'status' => 200,
      'message' => trans('language.update_success'),
    ]);
  }

  /**
   * Delele source
   * @param Request $request
   * @param GalleryCategory $galleryCategory
   * @return mixed
   */
  public function destroy(Request $request, RoyaltyCategory $royaltyCategory)
  {
    if (! $request->ajax()) return;

    if ($royaltyCategory->delete()) {
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
