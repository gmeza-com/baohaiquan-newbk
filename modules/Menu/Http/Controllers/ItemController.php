<?php
namespace Modules\Menu\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Menu\Models\Menu;
use Modules\Menu\Models\MenuItem;
use Modules\Menu\Libraries\ViewComposer;
use App\Http\Controllers\AdminController;
use Modules\Menu\Http\Requests\MenuItemRequest;

class ItemController extends AdminController
{
    public function index(Menu $menu, Request $request)
    {
        if(! $request->ajax()) {
            return;
        }

        $this->tpl->setData('items', $menu->items);
        $this->tpl->setTemplate('menu::item.index');
        return $this->tpl->render();
    }

    public function create(Request $request, Menu $menu, MenuItem $item)
    {
        if(! $request->ajax()) {
            return;
        }

        $this->tpl->setData('menu', $menu);
        $this->tpl->setData('item', $item);
        ViewComposer::dropdownItemsMenu($menu);

        $this->tpl->setTemplate('menu::item.create');
        return $this->tpl->render();
    }

    public function store(MenuItemRequest $request, Menu $menu)
    {
        if(! $request->ajax()) {
            return;
        }

        $data = $request->except(['_token', 'language']);
        $data['menu_id'] = $menu->id;

        $menuItem = MenuItem::create($data);
        $menuItem->saveLanguages($request->only('language'));

        return response()->json([
            'status' => 200,
            'message' => trans('language.create_success'),
            'menu_id'=> $menu->id,
        ]);
    }

    public function edit(Request $request, Menu $menu, $id)
    {
        if(! $request->ajax()) {
            return;
        }

        $item = MenuItem::findOrFail($id);

        $this->tpl->setData('menu', $menu);
        $this->tpl->setData('item', $item);
        ViewComposer::dropdownItemsMenu($menu, $item->parent_id);

        $this->tpl->setTemplate('menu::item.edit');
        return $this->tpl->render();
    }

    public function update(MenuItemRequest $request, Menu $menu, $id)
    {
        if(! $request->ajax()) {
            return;
        }
        $item = MenuItem::findOrFail($id);

        if($request->input('parent_id') == $item->id) {
            return response()->json([
                'status' => 500,
                'message' => trans('menu::language.you_can_use_parent_id_self')
            ]);
        }

        $data = $request->except(['_token', 'language']);
        $data['menu_id'] = $menu->id;

        $item->update($data);
        $item->saveLanguages($request->only('language'));

        if($request->input('parent_id') !== $item->parent_id) {
            $item->resolveUpdated();
        }

        return response()->json([
            'status' => 200,
            'message' => trans('language.update_success'),
            'menu_id'=> $menu->id,
        ]);
    }

    public function sort(Request $request, Menu $menu)
    {
        if(! $request->ajax()) {
            return;
        }

        MenuItem::updateAllPosition($request->input('data'), 0);
        return response()->json(['status' => 200]);
    }

    public function destroy(Request $request, Menu $menu, $id)
    {
        if(! $request->ajax()) {
            return;
        }
        $item = MenuItem::findOrFail($id);
        $item->resolveDelete($item);

        return response()->json([
            'status' => 200,
            'message' => trans('language.delete_success'),
            'menu_id' => $menu->id
        ]);
    }
}
