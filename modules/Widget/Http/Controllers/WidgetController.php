<?php

namespace Modules\Widget\Http\Controllers;

use App\Core\Template\TemplateInterface;
use App\Http\Controllers\AdminController;
use Illuminate\Http\Request;
use Modules\Widget\Models\Widget;
use Yajra\DataTables\Facades\DataTables;

class WidgetController extends AdminController
{
  protected $widgetObject;

  /**
   * WidgetController constructor.
   */
  public function __construct(TemplateInterface $template, \Modules\Widget\Libraries\Widget $widget)
  {
    parent::__construct($template);
    $this->widgetObject = $widget;
  }

  public function index(Request $request)
  {
    if ($request->ajax()) {
      return $this->data($request);
    }

    $this->tpl->setData('title', trans('widget::language.manager'));
    $this->tpl->setTemplate('widget::index');

    // breadcrumb
    $this->tpl->breadcrumb()->add('/' . admin_path(), trans('language.dashboard'));
    $this->tpl->breadcrumb()->add('admin.user.index', trans('widget::language.manager'));

    // Datatables
    $this->tpl->datatable()->setSource(admin_route('widget.index'));
    $this->tpl->datatable()->addColumn(
      '#',
      'id',
      ['class' => 'col-md-1']
    );
    $this->tpl->datatable()->addColumn(
      trans('widget::language.widget_name'),
      'name',
      ['class' => 'col-md-3']
    );
    $this->tpl->datatable()->addColumn(
      trans('widget::language.widget_code'),
      'code',
      ['class' => 'col-md-4'],
      false,
      false
    );
    $this->tpl->datatable()->addColumn(
      trans('language.published'),
      'published',
      ['class' => 'col-md-2']
    );

    return $this->tpl->render();
  }

  public function data(Request $request)
  {
    app('helper')->load('buttons');

    return Datatables::eloquent(Widget::query())
      ->editColumn('name', function ($model) {
        if (allow('widget.widget.edit')) {
          return link_to_route('admin.widget.edit', $model->name, $model->id);
        }
        return $model->name;
      })
      ->addColumn('code', function ($model) {
        return sprintf('<code>{!! widget(\'%s\') !!}</code>', $model->slug);
      })
      ->addColumn('action', function ($model) {
        $button = [];

        // lock
        if (auth()->user()->is_super_admin) {
          $button[] = [
            'route' => 'javascript:void(0);',
            'name' => 'Lock',
            'icon' => 'fa fa-' . ($model->lock ? 'unlock' : 'lock'),
            'attributes' => [
              'class' => 'btn btn-xs btn-success',
              'data-url' => admin_route('widget.update', $model->id),
              'data-action' => 'lock_widget'
            ]
          ];
        }

        // edit role
        if (allow('widget.widget.edit')) {
          $button[] = [
            'route' => admin_route('widget.edit', $model->id),
            'name' => trans('language.edit'),
            'icon' => 'fa fa-pencil-square-o',
            'attributes' => [
              'class' => 'btn btn-xs btn-primary'
            ]
          ];
        }

        // delete
        if (allow('widget.widget.destroy') && !$model->lock) {
          $button[] = [
            'route' => 'javascript:void(0);',
            'name' => trans('language.delete'),
            'icon' => 'fa fa-trash-o',
            'attributes' => [
              'class' => 'btn btn-xs btn-danger',
              'data-url' => admin_route('widget.destroy', $model->id),
              'data-action' => 'confirm_to_delete',
              'data-message' => trans('language.confirm_to_delete')
            ]
          ];
        }

        return cnv_action_block($button);
      })
      ->editColumn('published', function ($model) {
        return sprintf(
          '<span class="label label-%s">%s</span>',
          $model->published ? 'success' : 'warning',
          $model->published ? trans('language.published') : trans('language.trashed')
        );
      })
      ->rawColumns(['action', 'published', 'code'])
      ->make(true);
  }

  public function create(Request $request, Widget $widget)
  {
    if ($request->ajax() && $request->has('type')) {
      return $this->getFormForWidget($request->get('type'), $widget);
    }

    $this->tpl->setData('title', trans('widget::language.widget_create'));
    $this->tpl->setTemplate('widget::create');

    // Breadcrumb
    $this->tpl->breadcrumb()->add('/' . admin_path(), trans('language.dashboard'));
    $this->tpl->breadcrumb()->add('admin.widget.index', trans('widget::language.manager'));
    $this->tpl->breadcrumb()->add('admin.widget.create', trans('widget::language.widget_create'));

    $widget->published = true;
    $this->tpl->setData('widget', $widget);
    $this->tpl->setData('widgetTypes',  $this->widgetObject->getWidgetTypesForForm());

    return $this->tpl->render();
  }

  public function store(Request $request)
  {
    if (! $request->ajax()) {
      return;
    }
    $widget = new Widget([
      'name' => $request->name,
      'slug' => $request->slug,
      'published' => $request->published ? true : false,
      'type' => $request->type,
      'lock' => false,
    ]);
    $this->widgetObject->fromSave($request, $widget);

    if ($widget->save()) {
      return response()->json([
        'status' => 200,
        'message' => trans('language.create_success'),
        'redirect_url' => admin_route('widget.edit', $widget->id)
      ]);
    } else {
      return response()->json([
        'status' => 500,
        'message' => trans('language.create_fail')
      ]);
    }
  }

  public function edit(Request $request, Widget $widget)
  {
    if ($request->ajax() && $request->has('type')) {
      return $this->getFormForWidget($request->get('type'), $widget);
    }

    $this->tpl->setData('title', trans('widget::language.widget_edit'));
    $this->tpl->setTemplate('widget::edit');

    // Breadcrumb
    $this->tpl->breadcrumb()->add('/' . admin_path(), trans('language.dashboard'));
    $this->tpl->breadcrumb()->add('admin.widget.index', trans('widget::language.manager'));
    $this->tpl->breadcrumb()->add(admin_route('widget.edit', $widget->id), trans('widget::language.widget_edit'));

    $this->tpl->setData('widget', $widget);
    $this->tpl->setData('widgetTypes',  $this->widgetObject->getWidgetTypesForForm());

    return $this->tpl->render();
  }

  public function update(Request $request, Widget $widget)
  {
    if (! $request->ajax()) {
      return;
    }

    if (auth()->user()->is_super_admin && $request->has('lock')) {
      $widget->lock = !$widget->lock;
    } else {
      $widget->name = $request->name;
      $widget->published = $request->published ? true : false;
      $this->widgetObject->fromSave($request, $widget);
    }

    if ($widget->save()) {
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

  public function destroy(Request $request, Widget $widget)
  {
    if (! $request->ajax()) {
      return;
    }

    if (!$widget->lock && $widget->delete()) {
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

  /**
   * @param string $type
   * @param Widget $widget
   * @return mixed
   */
  protected function getFormForWidget($type, $widget)
  {
    $this->widgetObject->setWidgetObject($type);
    return $this->widgetObject->getForm($widget);
  }
}
