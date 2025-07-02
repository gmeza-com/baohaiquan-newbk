<?php

namespace Modules\Acl\Http\Controllers;

use App\Http\Controllers\AdminController;
use Illuminate\Http\Request;
use Modules\Acl\Http\Requests\PermissionRequest;
use Modules\Acl\Models\Permission;
use Yajra\DataTables\Facades\DataTables;

class PermissionController extends AdminController
{
  public function index(Request $request)
  {
    // get data
    if ($request->ajax()) {
      return $this->data($request);
    }

    $this->tpl->setData('title', trans('acl::language.acl_manager'));
    $this->tpl->setTemplate('acl::permission.index');

    // Breadcrumb
    $this->tpl->breadcrumb()->add('/' . admin_path(), trans('language.dashboard'));
    $this->tpl->breadcrumb()->add('admin.acl.role.index', trans('acl::language.acl'));
    $this->tpl->breadcrumb()->add('admin.acl.permission.index', trans('acl::language.acl_permission_manager'));

    // Datatables
    $this->tpl->datatable()->setSource(admin_route('acl.permission.index'));
    $this->tpl->datatable()->addColumn(
      trans('acl::language.permission_name'),
      'name',
      ['class' => 'col-md-4'],
      false,
      false
    );
    $this->tpl->datatable()->addColumn(
      trans('language.slug'),
      'slug',
      ['class' => 'col-md-3']
    );
    $this->tpl->datatable()->addColumn(
      trans('acl::language.permission_module'),
      'module',
      ['class' => 'col-md-3']
    );

    // app('helper')->load('buttons');

    return $this->tpl->render();
  }

  public function data()
  {
    app('helper')->load('buttons');

    return Datatables::eloquent(Permission::query())
      ->addColumn('name', function ($model) {
        return $model->language('description');
      })
      ->addColumn('action', function ($model) {
        $button = [];

        // edit permission
        if (allow('acl.permission.edit')) {
          $button[] = [
            'route' => admin_route('acl.permission.edit', $model->id),
            'name' => trans('language.edit'),
            'icon' => 'fa fa-pencil-square-o',
            'attributes' => [
              'class' => 'btn btn-xs btn-primary'
            ]
          ];
        }

        // delete
        if (allow('acl.permission.destroy')) {
          $button[] = [
            'route' => 'javascript:void(0);',
            'name' => trans('language.delete'),
            'icon' => 'fa fa-trash-o',
            'attributes' => [
              'class' => 'btn btn-xs btn-danger',
              'data-url' => admin_route('acl.permission.destroy', $model->id),
              'data-action' => 'confirm_to_delete',
              'data-message' => trans('language.confirm_to_delete')
            ]
          ];
        }

        return cnv_action_block($button);
      })
      ->make(true);
  }

  public function create(Request $request, Permission $permission)
  {
    $this->tpl->setData('title', trans('acl::language.permission_create'));
    $this->tpl->setTemplate('acl::permission.create');

    // Breadcrumb
    $this->tpl->breadcrumb()->add('/' . admin_path(), trans('language.dashboard'));
    $this->tpl->breadcrumb()->add('admin.acl.role.index', trans('acl::language.acl'));
    $this->tpl->breadcrumb()->add('admin.acl.permission.index', trans('acl::language.acl_permission_manager'));
    $this->tpl->breadcrumb()->add('admin.acl.permission.create', trans('acl::language.permission_create'));

    // set data default
    $this->tpl->setData('permission', $permission);
    $this->tpl->setData('permissions', $this->getAllPermissions());

    return $this->tpl->render();
  }

  public function store(PermissionRequest $request)
  {
    if (! $request->ajax()) {
      return;
    }

    $data = $request->except(['_token', 'language', 'permission']);

    $permission = Permission::create($data);
    $permission->saveLanguages($request->only('language'));

    return response()->json([
      'status' => 200,
      'message' => trans('language.create_success'),
    ]);
  }

  public function edit(Request $request, Permission $permission)
  {
    $this->tpl->setData('title', trans('acl::language.permission_create'));
    $this->tpl->setTemplate('acl::permission.edit');

    // Breadcrumb
    $this->tpl->breadcrumb()->add('/' . admin_path(), trans('language.dashboard'));
    $this->tpl->breadcrumb()->add('admin.acl.role.index', trans('acl::language.acl'));
    $this->tpl->breadcrumb()->add('admin.acl.permission.index', trans('acl::language.acl_permission_manager'));
    $this->tpl->breadcrumb()->add(admin_route('acl.permission.edit', $permission->id), trans('acl::language.permission_edit'));

    // set data default
    $this->tpl->setData('permission', $permission);
    $this->tpl->setData('permissions', $this->getAllPermissions());

    return $this->tpl->render();
  }

  public function update(PermissionRequest $request, Permission $permission)
  {
    if (! $request->ajax()) {
      return;
    }

    $data = $request->except(['_token', 'language', 'permission']);

    $permission->update($data);
    $permission->saveLanguages($request->only('language'));

    return response()->json([
      'status' => 200,
      'message' => trans('language.update_success'),
    ]);
  }

  public function destroy(Request $request, Permission $permission)
  {
    if (! $request->ajax()) {
      return;
    }
    $permission->delete();

    return response()->json([
      'status' => 200,
      'message' => trans('language.delete_success'),
    ]);
  }

  protected function getAllPermissions()
  {
    $permissions = Permission::all();
    return $permissions->groupBy('module');
  }
}
