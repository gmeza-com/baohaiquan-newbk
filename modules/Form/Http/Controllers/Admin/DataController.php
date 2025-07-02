<?php

namespace Modules\Form\Http\Controllers\Admin;

use App\Http\Controllers\AdminController;
use Illuminate\Http\Request;
use Modules\Form\Models\FormData;
use Yajra\DataTables\Facades\DataTables;

class DataController extends AdminController
{
  public function index(Request $request)
  {
    if ($request->ajax()) {
      return $this->data($request);
    }

    $this->tpl->setData('title', trans('form::language.form_data'));
    $this->tpl->setTemplate('form::admin.data.index');

    // breadcrumb
    $this->tpl->breadcrumb()->add('/' . admin_path(), trans('language.dashboard'));
    $this->tpl->breadcrumb()->add('/iadmin/form/data', trans('form::language.form_data'));

    // Datatables
    $this->tpl->datatable()->setSource('/iadmin/form/data');
    $this->tpl->datatable()->addColumn(
      '#',
      'id',
      ['class' => 'col-md-5']
    );
    $this->tpl->datatable()->addColumn(
      trans('form::language.form_type'),
      'form.slug',
      ['class' => 'col-md-2']
    );

    $this->tpl->datatable()->addColumn(
      trans('language.created_at'),
      'created_at',
      ['class' => 'col-md-2']
    );

    return $this->tpl->render();
  }

  protected function data()
  {
    app('helper')->load('buttons');

    return Datatables::eloquent(FormData::query()->with('form'))
      ->editColumn('id', function ($model) {
        if (allow('form.form.edit')) {
          return link_to(admin_url('form/data/' . $model->id), '#' . $model->form->slug . ' ID' . $model->id);
        }
        return $model->name;
      })
      ->editColumn('form.slug', function ($model) {
        return sprintf('<span class="label label-success">%s</span>', $model->form->slug);
      })
      ->addColumn('action', function ($model) {
        $button = [];

        // edit role
        if (allow('form.data.show')) {
          $button[] = [
            'route' => admin_url('form/data/' . $model->id),
            'name' => 'view',
            'icon' => 'fa fa-eye',
            'attributes' => [
              'class' => 'btn btn-xs btn-warning'
            ]
          ];
        }

        // delete
        if (allow('form.data.destroy') && !$model->lock) {
          $button[] = [
            'route' => 'javascript:void(0);',
            'name' => trans('language.delete'),
            'icon' => 'fa fa-trash-o',
            'attributes' => [
              'class' => 'btn btn-xs btn-danger',
              'data-url' => admin_url('form/data/' . $model->id),
              'data-action' => 'confirm_to_delete',
              'data-message' => trans('language.confirm_to_delete')
            ]
          ];
        }

        return cnv_action_block($button);
      })
      ->rawColumns(['action', 'slug'])
      ->make(true);
  }

  public function show(FormData $formData)
  {
    $this->tpl->setData('title', trans('form::language.form_edit'));
    $this->tpl->setTemplate('form::admin.data.show');

    // Breadcrumb
    $this->tpl->breadcrumb()->add('/' . admin_path(), trans('language.dashboard'));
    $this->tpl->breadcrumb()->add(admin_url('form/data'), trans('form::language.form_data'));
    $this->tpl->breadcrumb()->add(admin_url('form/data/' . $formData->id), trans('form::language.show_data'));

    $this->tpl->setData('formData', $formData);

    return $this->tpl->render();
  }

  public function destroy(Request $request, FormData $formData)
  {
    if (! $request->ajax()) {
      return;
    }

    if ($formData->delete()) {
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
}
