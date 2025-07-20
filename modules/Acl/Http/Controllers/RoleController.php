<?php

namespace Modules\Acl\Http\Controllers;

use App\Http\Controllers\AdminController;
use Illuminate\Http\Request;
use Modules\Acl\Http\Requests\RoleRequest;
use Modules\Acl\Models\Permission;
use Modules\Acl\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class RoleController extends AdminController
{
  public function index(Request $request)
  {
    // get data
    if ($request->ajax()) {
      return $this->data($request);
    }

    $this->tpl->setData('title', trans('acl::language.acl_manager'));
    $this->tpl->setTemplate('acl::role.index');

    // Breadcrumb
    $this->tpl->breadcrumb()->add('/' . admin_path(), trans('language.dashboard'));
    $this->tpl->breadcrumb()->add('admin.acl.role.index', trans('acl::language.acl'));
    $this->tpl->breadcrumb()->add('admin.acl.role.index', trans('acl::language.acl_manager'));

    // Datatables
    $this->tpl->datatable()->setSource(admin_route('acl.role.index'));
    $this->tpl->datatable()->addColumn(
      trans('acl::language.role_name'),
      'name',
      ['class' => 'col-md-6'],
      false,
      false
    );
    $this->tpl->datatable()->addColumn(
      trans('language.updated_at'),
      'updated_at',
      ['class' => 'col-md-3']
    );

    // app('helper')->load('buttons');

    return $this->tpl->render();
  }

  public function data()
  {
    app('helper')->load('buttons');

    $currentUser = auth()->user();
    $query = Role::query();

    if (!$currentUser->is_super_admin) {
      $query->where('slug', '!=', 'super_admin');
    }

    return Datatables::eloquent($query)
      ->addColumn('name', function ($model) {
        $preffy = '';
        $preffy .= "<h4><strong>{$model->language('name')}</strong></h4>";
        $preffy .= "<p>{$model->language('description')}</p>";
        return $preffy;
      })
      ->addColumn('action', function ($model) {
        $button = [];

        // edit role
        if (allow('acl.role.edit')) {
          $button[] = [
            'route' => admin_route('acl.role.edit', $model->id),
            'name' => trans('language.edit'),
            'icon' => 'fa fa-pencil-square-o',
            'attributes' => [
              'class' => 'btn btn-xs btn-primary'
            ]
          ];
        }

        // delete
        if (allow('acl.role.destroy')) {
          $button[] = [
            'route' => 'javascript:void(0);',
            'name' => trans('language.delete'),
            'icon' => 'fa fa-trash-o',
            'attributes' => [
              'class' => 'btn btn-xs btn-danger',
              'data-url' => admin_route('acl.role.destroy', $model->id),
              'data-action' => 'confirm_to_delete',
              'data-message' => trans('language.confirm_to_delete')
            ]
          ];
        }

        return cnv_action_block($button);
      })
      ->editColumn('updated_at', function ($model) {
        return $model->updated_at->format('d/m/Y H:i');
      })
      ->rawColumns(['action', 'name'])
      ->make(true);
  }

  public function create(Request $request, Role $role)
  {
    $this->tpl->setData('title', trans('acl::language.role_create'));
    $this->tpl->setTemplate('acl::role.create');

    // Breadcrumb
    $this->tpl->breadcrumb()->add('/' . admin_path(), trans('language.dashboard'));
    $this->tpl->breadcrumb()->add('admin.acl.role.index', trans('acl::language.acl'));
    $this->tpl->breadcrumb()->add('admin.acl.role.index', trans('acl::language.acl_manager'));
    $this->tpl->breadcrumb()->add('admin.acl.role.create', trans('acl::language.role_create'));

    // set data default
    $this->tpl->setData('role', $role);
    $this->tpl->setData('permissions', $this->getAllPermissions());

    return $this->tpl->render();
  }

  public function store(RoleRequest $request)
  {
    if (! $request->ajax()) {
      return;
    }

    $data = $request->except(['_token', 'language', 'permission']);

    $role = Role::create($data);
    $role->saveLanguages($request->only('language'));

    if ($request->has('permission')) {
      $role->permissions()->sync($request->input('permission'));
    }

    return response()->json([
      'status' => 200,
      'message' => trans('language.create_success'),
    ]);
  }

  public function edit(Request $request, Role $role)
  {
    $this->tpl->setData('title', trans('acl::language.role_create'));
    $this->tpl->setTemplate('acl::role.edit');

    // Breadcrumb
    $this->tpl->breadcrumb()->add('/' . admin_path(), trans('language.dashboard'));
    $this->tpl->breadcrumb()->add('admin.acl.role.index', trans('acl::language.acl'));
    $this->tpl->breadcrumb()->add('admin.acl.role.index', trans('acl::language.acl_manager'));
    $this->tpl->breadcrumb()->add(admin_route('acl.role.edit', $role->id), trans('acl::language.role_edit'));

    // set data default
    $this->tpl->setData('role', $role);
    $this->tpl->setData('permissions', $this->getAllPermissions());

    return $this->tpl->render();
  }

  public function update(RoleRequest $request, Role $role)
  {
    if (! $request->ajax()) {
      return;
    }

    $data = $request->except(['_token', 'language', 'permission']);

    $role->update($data);
    $role->saveLanguages($request->only('language'));

    if ($request->has('permission')) {
      $role->permissions()->sync($request->input('permission'));
    }

    return response()->json([
      'status' => 200,
      'message' => trans('language.update_success'),
    ]);
  }

  public function destroy(Request $request, Role $role)
  {
    if (! $request->ajax()) {
      return;
    }
    $role->delete();

    return response()->json([
      'status' => 200,
      'message' => trans('language.delete_success'),
    ]);
  }

  protected function getAllPermissions()
  {
    $currentUser = auth()->user();
    $permissions = Permission::where('only_for_super_admin', false)->get();

    if ($currentUser->is_super_admin) {
      $permissions = Permission::all();
    }

    return $permissions->groupBy('module');
  }
}
