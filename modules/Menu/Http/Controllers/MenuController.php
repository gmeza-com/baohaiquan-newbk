<?php

namespace Modules\Menu\Http\Controllers;

use App\Http\Controllers\AdminController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Menu\Http\Requests\MenuRequest;
use Modules\Menu\Models\Menu;
use Yajra\DataTables\Facades\DataTables;

class MenuController extends AdminController
{
  public function index(Request $request)
  {
    $this->tpl->setData('title', trans('menu::language.menu_manager'));
    $this->tpl->setTemplate('menu::index');

    // Breadcrumb
    $this->tpl->breadcrumb()->add('/' . admin_path(), trans('language.dashboard'));
    $this->tpl->breadcrumb()->add('admin.menu.index', trans('menu::language.menu_manager'));

    // Get all menu in tables
    $menus = Menu::orderBy('slug')->get()->filter(function ($menu) {
      if ($menu->id === 1) {
        return Auth::user()->is_super_admin;
      }
      return true;
    })->mapWithKeys(function ($menu) {
      return [$menu->slug => $menu->slug];
    });

    $this->tpl->setData('menus', $menus);

    $hook = get_hook('module_in_menu_search_hook');
    $this->tpl->setData('module_in_menu_search_hook', $hook);

    // loading data submenu
    if ($request->has('menu')) {
      $menu = Menu::whereSlug($request->get('menu'))->firstOrFail();
      $this->tpl->setData('menu', $menu);

      // only root to edit admin menu
      if ($menu->slug == 'admin-menu' && ! Auth::user()->is_super_admin) {
        abort(403);
      }
    }
    return $this->tpl->render();
  }

  public function create()
  {
    $this->tpl->setData('title', trans('menu::language.create_new_menu'));

    // Breadcrumb
    $this->tpl->breadcrumb()->add('/' . admin_path(), trans('language.dashboard'));
    $this->tpl->breadcrumb()->add('admin.menu.index', trans('menu::language.menu_manager'));
    $this->tpl->breadcrumb()->add('admin.menu.create', trans('menu::language.create_new_menu'));

    $this->tpl->setData('menu', new Menu());
    $this->tpl->setTemplate('menu::create');

    return $this->tpl->render();
  }

  public function store(MenuRequest $request)
  {
    $menu = Menu::create($request->all());

    return response()->json([
      'status' => 200,
      'message' => trans('language.create_success')
    ]);
  }

  public function edit(Request $request, Menu $menu)
  {
    // only root to edit admin menu
    if ($menu->slug == 'admin-menu' && ! Auth::user()->is_super_admin) {
      abort(403);
    }

    $this->tpl->setData('title', trans('menu::language.edit_menu'));

    // Breadcrumb
    $this->tpl->breadcrumb()->add('/' . admin_path(), trans('language.dashboard'));
    $this->tpl->breadcrumb()->add('admin.menu.index', trans('menu::language.menu_manager'));
    $this->tpl->breadcrumb()->add(admin_route('menu.index', $menu->id), trans('menu::language.edit_menu'));

    $this->tpl->setData('menu', $menu);
    $this->tpl->setTemplate('menu::edit');

    return $this->tpl->render();
  }

  public function update(MenuRequest $request, Menu $menu)
  {
    // only root to edit admin menu
    if ($menu->slug == 'admin-menu' && ! Auth::user()->is_super_admin) {
      abort(403);
    }

    $menu->update($request->all());

    return response()->json([
      'status' => 200,
      'message' => trans('language.update_success'),
      'redirect_url' => admin_route('menu.index') . '?menu=' . $menu->slug
    ]);
  }

  public function destroy(Request $request, Menu $menu)
  {
    if (! $request->ajax()) {
      return;
    }

    if ($menu->slug == 'admin-menu') {
      return response()->json([
        'status' => 500,
        'message' => trans('menu::language.could_not_delete_system_menu')
      ]);
    }
    $menu->delete();

    return response()->json([
      'status' => 200,
      'message' => trans('language.delete_success'),
      'redirect_url' => route('admin.menu.index')
    ]);
  }
}
