<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;

trait ModelNested
{

    public static function bootModelNested()
    {
        static::deleting(function ($model) {
            $children = $model->where('parent_id', $model->id)->get();
            $children->each->delete();
        });
    }

    /**
     * @param int $parent
     * @param int $level
     * @return \Illuminate\Support\Collection
     */
    public function getNestedMenus($parent = 0, $level = 0, $exclude_draft = false)
    {
        $collect = collect([]);
        $query = (new static)->where('parent_id', $parent);
        
        if ($exclude_draft) {
            $query->where('published', 1);
        }
        
        $items = $query->with('children')->get();

        foreach ($items as $item) {
            $item->level = $level;
            $collect->push($item);

            if ($item->children->count() > 0) {
                $collect = $collect->merge($this->getNestedMenus($item->id, $level + 1, $exclude_draft));
            }
        }

        return $collect;
    }

    /**
     * @param null $locale
     * @param int $parent
     * @param int $level
     * @return \Illuminate\Support\Collection
     */
    public function getNestedMenusWithFormat($locale = null, $parent = 0, $level = 0)
    {
        $menus = $this->getNestedMenus($parent, $level);
        $locale = $locale ?: session('lang');
        return $menus->filter(function ($item) use ($locale) {
            return $item->language('name', $locale);
        })->map(function ($item) use ($locale) {
            $item->name = $this->renderPrefix($item->level) . $item->language('name', $locale);
            return $item;
        });
    }

    /**
     * @param null $locale
     * @param $root
     * @return mixed
     */
    protected function getNestedMenusForChoose($locale = null, $root, $removeSelfAndChildren = false, $get_all = true)
    {
        $locale = $locale ?: session('lang');

        $menus = $this->getNestedMenus(0, 0, !$get_all);

        $collect =  $menus->filter(function ($i) use ($removeSelfAndChildren) {
            if ($removeSelfAndChildren) {
                dd(($i->id !== $this->id && $i->parent_id !== $this->id));
                return ($i->id !== $this->id && $i->parent_id !== $this->id);
            }
            return true;
        })->mapWithKeys(function ($i) use ($locale) {
            return [$i->id => $this->renderPrefix($i->level) . $i->language('name', $locale)];
        });

        if ($root) {
            $collect->prepend(trans('language.root_category'), 0);
        }
        return $collect;
    }

    /**
     * @param $level
     * @return string
     */
    public function renderPrefix($level)
    {
        if ($level > 0) {
            $pre = '';
            for ($i = 1; $i <= $level; $i++) {
                $pre .= '—';
            }

            return $pre . '» ';
        }
    }
}
