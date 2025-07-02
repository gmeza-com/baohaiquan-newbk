<?php

namespace Plugins\Province\Http\Controllers;

use App\Http\Controllers\AdminController;
use Illuminate\Http\Request;
use Modules\Plugin\Repositories\PluginRepository;
use Plugins\Province\Models\Province;
use Yajra\DataTables\Facades\DataTables;

class ProvinceController extends AdminController
{
  protected $plugin;

  public function index(Request $request, PluginRepository $pluginRepository)
  {
    $this->plugin = $pluginRepository;

    if ($request->ajax()) {
      if ($request->exists('activate')) {
        return $this->update($request);
      } else {
        return $this->getListProvince();
      }
    }

    $this->tpl->setData('title', $this->plugin->name);
    $this->tpl->setTemplate('province_plugin::index');

    // breadcrumb
    $this->tpl->breadcrumb()->add('/' . admin_path(), trans('language.dashboard'));
    $this->tpl->breadcrumb()->add('admin.user.index', trans('user::language.user_manager'));

    // Datatables
    $this->tpl->datatable()->setSource(admin_route('plugin.index') . '?plugin=' . $this->plugin->slug);
    $this->tpl->datatable()->addColumn(
      trans('language.type'),
      'type',
      ['class' => 'col-md-3']
    );
    $this->tpl->datatable()->addColumn(
      trans('language.name'),
      'name',
      ['class' => 'col-md-3']
    );
    $this->tpl->datatable()->addColumn(
      trans('language.status'),
      'activated',
      ['class' => 'col-md-2'],
      false,
      false
    );

    return $this->tpl->render();
  }

  protected function getListProvince()
  {
    app('helper')->load('buttons');

    return Datatables::eloquent(Province::query())
      ->editColumn('activated', function ($model) {
        return sprintf('<span class="label label-%s">%s</span>', $model->activated ? 'success' : 'warning', $model->activated ? trans('language.activated') :  trans('language.deactivated'));
      })
      ->addColumn('action', function ($model) {
        $button = [];

        $button[] = [
          'route' => 'javascript:void(0);',
          'name' => $model->activated ? trans('language.deactivate') : trans('language.activate'),
          'icon' => $model->activated ? 'fa fa-ban' : 'fa fa-check-circle-o',
          'attributes' => [
            'class' => $model->activated ? 'btn btn-xs btn-warning' : 'btn btn-xs btn-success',
            'data-url' => admin_route('plugin.index') . '?plugin=' . $this->plugin->slug . '&activate=true&province_id=' . $model->id,
            'data-action' => 'activation'
          ]
        ];

        return cnv_action_block($button);
      })
      ->rawColumns(['action', 'activated'])
      ->make(true);
  }

  protected function update(Request $request)
  {
    $province = Province::findOrFail($request->get('province_id'));
    $province->activated = !$province->activated;
    $province->save();

    return response()->json([
      'status' => 200,
      'message' => trans('language.update_success')
    ]);
  }
}
