<?php
namespace Modules\Menu\Libraries;

use Modules\Menu\Models\Menu;

class ViewComposer
{
    public static function dropdownItemsMenu($menuSlug, $selected = 0)
    {
        if(is_string($menuSlug)) {
            $menu = Menu::where('slug', $menuSlug)->with('items')->first();
        } else {
            $menu = $menuSlug;
        }

        if($menu) {
            $items = collect(static::filter([], $menu->items));
            $items = $items->mapWithKeys(function($item) {
                return [$item->id => static::getNameWithPrefix($item)];
            });
        }

        view()->composer('menu::item.dropdown_menu', function($view) use ($items, $selected) {
            $a_items = [];
            $a_items['0'] = trans('menu::language.menu_parent');
            $a_items += $items->toArray();

            $view->with('items', $a_items)->with('selected', $selected);
        });
    }

    protected static function filter($data = [], $items, $parent = 0)
    {
        foreach ($items->filter(function($item) use ($parent){
            return $item->parent_id == $parent;
        })->sortBy('position') as $item) {
            $data[] = $item;
            if($item->children->count())
            {
                $data = static::filter($data, $item->children, $item->id);
            }
        }
        return $data;
    }

    protected static function getNameWithPrefix($item)
    {
        $prefix = '';
        if($item->level > 0) {
            for ($i=1; $i <= $item->level; $i++) {
                $prefix .= '--';
            }
        }

        return $prefix . ' ' . $item->language('name');
    }
}
