<?php

namespace App\Libraries;

use Illuminate\Support\Facades\Route;

class Breadcrumb
{
    /**
     * @var array
     */
    protected $items = [];

    /**
     * @param $route
     * @param $name
     */
    public function add($route, $name)
    {
        $this->items[] = [
            'link' => Route::has($route) ? route($route) : url($route),
            'name' => trans($name)
        ];
    }

    /**
     * @return array
     */
    public function getItems()
    {
        return $this->items;
    }
}