<?php

namespace Modules\Dashboard\Http\Controllers;

use App\Http\Controllers\AdminController;

class DashboardController extends AdminController
{
    public function index()
    {
        $this->tpl->setData('title', trans('dashboard::language.dashboard'));
        $this->tpl->setTemplate('dashboard::index');

        // breadcrumb
        $this->tpl->breadcrumb()->add('/' . admin_path(), trans('language.dashboard'));

        $this->tpl->setData('dashboard_blocks', get_hook('dashboard_block'));

        return $this->tpl->render();
    }
}