<?php

namespace Modules\Form\Http\Controllers\Admin;

use App\Http\Controllers\AdminController;
use Illuminate\Http\Request;
use Modules\Form\Models\Form;
use Yajra\DataTables\Facades\DataTables;

class FormController extends AdminController
{
  public function index(Request $request)
  {
    if ($request->ajax()) {
      return $this->data($request);
    }

    $this->tpl->setData('title', trans('form::language.manager'));
    $this->tpl->setTemplate('form::admin.form.index');

    // breadcrumb
    $this->tpl->breadcrumb()->add('/' . admin_path(), trans('language.dashboard'));
    $this->tpl->breadcrumb()->add('admin.form.index', trans('form::language.manager'));

    // Datatables
    $this->tpl->datatable()->setSource(admin_route('form.index'));
    $this->tpl->datatable()->addColumn(
      '#',
      'id',
      ['class' => 'col-md-1']
    );
    $this->tpl->datatable()->addColumn(
      trans('form::language.form_slug'),
      'slug',
      ['class' => 'col-md-6']
    );
    $this->tpl->datatable()->addColumn(
      trans('language.created_at'),
      'created_at',
      ['class' => 'col-md-2']
    );

    return $this->tpl->render();
  }

  public function data(Request $request)
  {
    app('helper')->load('buttons');
    return Datatables::eloquent(Form::query())
      ->editColumn('slug', function ($model) {
        if (allow('form.form.edit')) {
          return link_to_route('admin.form.edit', $model->slug, $model->id);
        }
        return $model->name;
      })
      ->addColumn('action', function ($model) {
        $button = [];

        $button[] = [
          'route' => url('/form/' . $model->slug),
          'name' => 'view',
          'icon' => 'fa fa-eye',
          'attributes' => [
            'class' => 'btn btn-xs btn-warning',
            'target' => '_blank'
          ]
        ];

        // edit role
        if (allow('form.form.edit')) {
          $button[] = [
            'route' => admin_route('form.edit', $model->id),
            'name' => trans('language.edit'),
            'icon' => 'fa fa-pencil-square-o',
            'attributes' => [
              'class' => 'btn btn-xs btn-primary'
            ]
          ];
        }

        // delete
        if (allow('form.form.destroy') && !$model->lock) {
          $button[] = [
            'route' => 'javascript:void(0);',
            'name' => trans('language.delete'),
            'icon' => 'fa fa-trash-o',
            'attributes' => [
              'class' => 'btn btn-xs btn-danger',
              'data-url' => admin_route('form.destroy', $model->id),
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

  public function create(Form $form)
  {
    $this->tpl->setData('title', trans('form::language.form_create'));
    $this->tpl->setTemplate('form::admin.form.create');

    // Breadcrumb
    $this->tpl->breadcrumb()->add('/' . admin_path(), trans('language.dashboard'));
    $this->tpl->breadcrumb()->add('admin.form.index', trans('form::language.manager'));
    $this->tpl->breadcrumb()->add('admin.form.create', trans('form::language.form_create'));

    $this->tpl->setData('form', $form);

    return $this->tpl->render();
  }

  public function store(Request $request)
  {
    if (! $request->ajax()) {
      return;
    }

    if ($form = Form::create($request->all())) {
      return response()->json([
        'status' => 200,
        'message' => trans('language.create_success'),
        'redirect_url' => admin_route('form.edit', $form->id)
      ]);
    } else {
      return response()->json([
        'status' => 500,
        'message' => trans('language.create_fail')
      ]);
    }
  }

  public function edit(Request $request, Form $form)
  {
    $this->tpl->setData('title', trans('form::language.form_edit'));
    $this->tpl->setTemplate('form::admin.form.edit');

    // Breadcrumb
    $this->tpl->breadcrumb()->add('/' . admin_path(), trans('language.dashboard'));
    $this->tpl->breadcrumb()->add('admin.form.index', trans('form::language.manager'));
    $this->tpl->breadcrumb()->add(admin_route('form.edit', $form->id), trans('form::language.form_edit'));

    $this->tpl->setData('form', $form);

    return $this->tpl->render();
  }

  public function update(Request $request, Form $form)
  {
    if (! $request->ajax()) {
      return;
    }

    if ($form->update($request->all())) {
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

  public function destroy(Request $request, Form $form)
  {
    if (! $request->ajax()) {
      return;
    }

    if ($form->delete()) {
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
