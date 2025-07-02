<?php
/**
 * Get menus via slug
 *
 * @param $slug
 * @return mixed
 */
function cnv_menu($slug) {
    $menu = \Modules\Menu\Models\Menu::where('slug', $slug)
        ->first();
    return $menu->items->filter(function ($item) {
        return $item->parent_id == 0;
    })->sortBy('position');
}