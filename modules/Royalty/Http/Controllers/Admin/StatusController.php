<?php

namespace Modules\Royalty\Http\Controllers\Admin;

use App\Http\Controllers\AdminController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Modules\Royalty\Models\RoyaltyStatus;
use Yajra\DataTables\Facades\DataTables;

class StatusController extends AdminController
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

    $this->tpl->setData('title', trans('royalty::language.manager_status'));
    $this->tpl->setTemplate('royalty::admin.status.index');

    // breadcrumb
    $this->tpl->breadcrumb()->add('admin.royalty.status.index', trans('royalty::language.manager_status'));

    // datatable
    $this->tpl->datatable()->setSource(admin_route('royalty.status.index'));
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
      trans('language.position'),
      'ordering',
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

    $model = (new RoyaltyStatus());

    return Datatables::of($model->query())
      ->editColumn('name', function ($model) {
        return allow('royalty.status.edit') ? link_to_route('admin.royalty.status.edit', $model->name, ['royaltyStatus' => $model->id]) : $model->name;
      })
      ->addColumn('action', function ($model) {
        app('helper')->load('buttons');
        $button = [];

        // edit role
        if (allow('royalty.status.edit')) {
          $button[] = [
            'route' => admin_route('royalty.status.edit', $model->id),
            'name' => trans('language.edit'),
            'icon' => 'fa fa-pencil-square-o',
            'attributes' => [
              'class' => 'btn btn-xs btn-primary'
            ]
          ];
        }

        // delete
        if (allow('royalty.status.destroy')) {
          $button[] = [
            'route' => 'javascript:void(0);',
            'name' => trans('language.delete'),
            'icon' => 'fa fa-trash-o',
            'attributes' => [
              'class' => 'btn btn-xs btn-danger',
              'data-url' => admin_route('royalty.status.destroy', $model->id),
              'data-action' => 'confirm_to_delete',
              'data-message' => trans('language.confirm_to_delete')
            ]
          ];
        }

        return cnv_action_block($button);
      })
      ->rawColumns(['name', 'action'])
      ->make(true);
  }

  public function create(RoyaltyStatus $royaltyStatus)
  {
    $this->tpl->setData('title', trans('royalty::language.status_create'));
    $this->tpl->setData('royaltyStatus', $royaltyStatus);
    $this->tpl->setTemplate('royalty::admin.status.create');

    // breadcrumb
    $this->tpl->breadcrumb()->add(admin_route('royalty.status.index'), trans('royalty::language.manager_status'));
    $this->tpl->breadcrumb()->add(admin_route('royalty.status.create'), trans('royalty::language.status_create'));

    return $this->tpl->render();
  }

  public function store(Request $request)
  {
    $data = $request->except(['_token']);

    if ($status = RoyaltyStatus::create($data)) {
      Cache::flush();
      return response()->json([
        'status' => 200,
        'message' => trans('language.update_success'),
        'redirect_url' => admin_route('royalty.status.edit', $status->id)
      ]);
    } else {
      return response()->json([
        'status' => 500,
        'message' => trans('language.update_fail'),
      ]);
    }
  }

  public function edit(RoyaltyStatus $royaltyStatus)
  {
    $this->tpl->setData('title', trans('royalty::language.status_edit'));
    $this->tpl->setData('royaltyStatus', $royaltyStatus);
    $this->tpl->setTemplate('royalty::admin.status.edit');

    // breadcrumb
    $this->tpl->breadcrumb()->add(admin_route('royalty.status.index'), trans('royalty::language.manager_status'));
    $this->tpl->breadcrumb()->add(admin_route('royalty.status.edit', $royaltyStatus->id), trans('royalty::language.status_edit'));

    return $this->tpl->render();
  }

  public function update(Request $request, RoyaltyStatus $royaltyStatus)
  {
    if (! $request->ajax()) return;

    $data = $request->except(['_token']);
    $royaltyStatus->update($data);

    Cache::flush();
    return response()->json([
      'status' => 200,
      'message' => trans('language.update_success'),
    ]);
  }

  /**
   * Delele source
   * @param Request $request
   * @param RoyaltyStatus $royaltyStatus
   * @return mixed
   */
  public function destroy(Request $request, RoyaltyStatus $royaltyStatus)
  {
    if (! $request->ajax()) return;

    if ($royaltyStatus->delete()) {
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
