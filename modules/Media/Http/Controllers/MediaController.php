<?php

namespace Modules\Media\Http\Controllers;

use App\Http\Controllers\AdminController;
use Illuminate\Http\Request;

class MediaController extends AdminController
{
  public function index(Request $request)
  {
    $this->tpl->setData('title', trans('media::language.index'));
    $this->tpl->setTemplate('media::index');


    $this->tpl->breadcrumb()->add('/' . admin_path(), trans('language.dashboard'));
    $this->tpl->breadcrumb()->add('admin.media.index', trans('media::language.index'));

    return $this->tpl->render();
  }
}
