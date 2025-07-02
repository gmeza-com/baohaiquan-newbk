<?php

namespace Modules\User\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Modules\Acl\Models\Role;
use Modules\User\Models\User;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Controllers\AdminController;
use Modules\User\Http\Requests\UserRequest;

class UserController extends AdminController
{
  public function index(Request $request)
  {
    if ($request->ajax()) {
      return $this->data($request);
    }

    $this->tpl->setData('title', trans('user::language.user_manager'));
    $this->tpl->setTemplate('user::admin.index');

    // breadcrumb
    $this->tpl->breadcrumb()->add('/' . admin_path(), trans('language.dashboard'));
    $this->tpl->breadcrumb()->add('admin.user.index', trans('user::language.user_manager'));

    // Datatables
    $this->tpl->datatable()->setSource(admin_route('user.index'));
    $this->tpl->datatable()->addColumn(
      trans('user::language.username'),
      'username',
      ['class' => 'col-md-2']
    );
    $this->tpl->datatable()->addColumn(
      trans('user::language.avatar'),
      'avatar',
      ['class' => 'col-md-2'],
      false,
      false
    );
    $this->tpl->datatable()->addColumn(
      trans('user::language.email'),
      'email',
      ['class' => 'col-md-2']
    );
    $this->tpl->datatable()->addColumn(
      trans('language.status'),
      'activated',
      ['class' => 'col-md-2']
    );
    $this->tpl->datatable()->addColumn(
      trans('language.updated_at'),
      'updated_at',
      ['class' => 'col-md-2']
    );

    return $this->tpl->render();
  }

  public function data()
  {
    app('helper')->load('buttons');

    return Datatables::eloquent(User::query())
      ->editColumn('avatar', function ($model) {
        return sprintf('<div class="%s"><img src="%s" width="40" class="img-circle"></div>', 'text-center', $model->avatar);
      })
      ->addColumn('action', function ($model) {
        $button = [];

        // edit role
        if (allow('user.user.edit')) {
          $button[] = [
            'route' => admin_route('user.edit', $model->id),
            'name' => trans('language.edit'),
            'icon' => 'fa fa-pencil-square-o',
            'attributes' => [
              'class' => 'btn btn-xs btn-primary'
            ]
          ];
        }

        // delete
        if (allow('user.user.destroy')) {
          $button[] = [
            'route' => 'javascript:void(0);',
            'name' => trans('language.delete'),
            'icon' => 'fa fa-trash-o',
            'attributes' => [
              'class' => 'btn btn-xs btn-danger',
              'data-url' => admin_route('user.destroy', $model->id),
              'data-action' => 'confirm_to_delete',
              'data-message' => trans('language.confirm_to_delete')
            ]
          ];
        }

        return cnv_action_block($button);
      })
      ->editColumn('activated', function ($model) {
        return sprintf(
          '<span class="label label-%s">%s</span>',
          $model->activated ? 'success' : 'warning',
          $model->activated ? trans('language.activated') : trans('language.inactivated')
        );
      })
      ->rawColumns(['action', 'activated', 'avatar'])
      ->make(true);
  }

  public function create(Request $request, User $user)
  {
    $this->tpl->setData('title', trans('user::language.user_create'));
    $this->tpl->setTemplate('user::admin.create');

    // Breadcrumb
    $this->tpl->breadcrumb()->add('/' . admin_path(), trans('language.dashboard'));
    $this->tpl->breadcrumb()->add('admin.user.index', trans('user::language.user_manager'));
    $this->tpl->breadcrumb()->add('admin.user.create', trans('user::language.user_create'));

    // set data default
    $this->tpl->setData('user', $user);
    $this->tpl->setData('roles', $this->getRoles());
    $this->tpl->setData('user_settings_fields', get_hook('user_settings_fields'));

    $this->tpl->setData('user_settings_fields', get_hook('user_settings_fields'));
    return $this->tpl->render();
  }

  public function store(Request $request, User $user)
  {
    if (! $request->ajax()) {
      return;
    }

    $data = $user->readyProfile($request);
    // mặc định mật khẩu 123456
    if (!isset($data['password'])) {
      $data['password'] = bcrypt(time());
    }

    if ($user = User::create($data)) {
      if ($request->has('role')) {
        $user->roles()->sync($request->input('role'));
      }

      return response()->json([
        'status' => 200,
        'message' => trans('language.create_success')
      ]);
    } else {
      return response()->json([
        'status' => 500,
        'message' => trans('language.create_fail')
      ]);
    }
  }

  public function edit(Request $request, User $user)
  {
    $this->tpl->setData('title', trans('user::language.user_edit'));
    $this->tpl->setTemplate('user::admin.edit');

    // Breadcrumb
    $this->tpl->breadcrumb()->add('/' . admin_path(), trans('language.dashboard'));
    $this->tpl->breadcrumb()->add('admin.user.index', trans('user::language.user_manager'));
    $this->tpl->breadcrumb()->add(admin_route('user.edit', $user->id), trans('user::language.user_edit'));

    // set data default
    $this->tpl->setData('user', $user);
    $this->tpl->setData('roles', $this->getRoles());
    $this->tpl->setData('user_settings_fields', get_hook('user_settings_fields'));

    $this->tpl->setData('user_settings_fields', get_hook('user_settings_fields'));
    return $this->tpl->render();
  }

  public function update(UserRequest $request, User $user)
  {
    if (! $request->ajax()) {
      return;
    }

    $data = $user->readyProfile($request);

    if ($user->username == 'admin' && $data['username'] != 'admin') {
      return response()->json([
        'status' => 500,
        'message' => trans('user::language.you_could_not_to_delete_admin')
      ]);
    }

    if ($user->update($data)) {
      if ($request->has('role')) {
        $user->roles()->sync($request->input('role'));
      }

      return response()->json([
        'status' => 200,
        'message' => trans('language.update_success')
      ]);
    } else {
      return response()->json([
        'status' => 500,
        'message' => trans('language.update_fail')
      ]);
    }
  }

  public function destroy(Request $request, User $user)
  {
    if (! $request->ajax()) {
      return;
    }

    if ($user->username == 'admin') {
      return response()->json([
        'status' => 500,
        'message' => trans('user::language.you_could_not_to_delete_admin')
      ]);
    }

    if ($user->delete()) {
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

  protected function getRoles()
  {
    return Role::orderBy('slug')->with('languages')->get();
  }
}
