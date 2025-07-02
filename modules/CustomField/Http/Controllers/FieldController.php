<?php

namespace Modules\CustomField\Http\Controllers;

use App\Http\Controllers\AdminController;
use Illuminate\Http\Request;
use Modules\CustomField\Models\Field;
use Modules\CustomField\Models\FieldLanguage;
use Yajra\DataTables\Facades\DataTables;

class FieldController extends AdminController
{
  /**
   * Index
   * @param Request $request
   * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|void
   */
  public function index(Request $request)
  {
    if ($request->ajax()) {
      return $this->data($request);
    }

    $this->tpl->setData('title', trans('custom_field::language.custom_field'));
    $this->tpl->setTemplate('custom_field::field.index');

    // breadcrumb
    $this->tpl->breadcrumb()->add('/' . admin_path(), trans('language.dashboard'));
    $this->tpl->breadcrumb()->add('admin.custom-field.index', trans('custom_field::language.custom_field'));

    // Datatables
    $this->tpl->datatable()->setSource(admin_route('custom-field.index'));
    $this->tpl->datatable()->addColumn(
      '#',
      'id',
      ['class' => 'col-md-1']
    );
    $this->tpl->datatable()->addColumn(
      trans('custom_field::language.name'),
      'name',
      ['class' => 'col-md-3'],
      false,
      false
    );
    $this->tpl->datatable()->addColumn(
      trans('custom_field::language.module'),
      'module',
      ['class' => 'col-md-2'],
      false,
      false
    );
    $this->tpl->datatable()->addColumn(
      trans('custom_field::language.type'),
      'field.type',
      ['class' => 'col-md-2']
    );
    $this->tpl->datatable()->addColumn(
      trans('language.status'),
      'field.hidden',
      ['class' => 'col-md-2']
    );

    return $this->tpl->render();
  }

  /**
   * Get list fields
   * @param Request $request
   * @return mixed
   */
  public function data(Request $request)
  {
    $language = $request->has('language') ? $request->get('language') : config('cnv.language_default');
    $model = FieldLanguage::with('field')->where('locale', $language);

    return Datatables::of($model)
      ->addColumn('module', function ($model) {
        return ucfirst($model->field->module);
      })
      ->editColumn('name', function ($model) {
        return link_to_route('admin.custom-field.edit', $model->name, ['custom_field' => $model->field->id]);
      })
      ->addColumn('action', function ($model) {
        app('helper')->load('buttons');
        $button = [];

        // if mutilselect or select show option
        if (allow('customfield.type.index') && in_array($model->field->type, ['mutilselect', 'select'])) {
          $button[] = [
            'route' => admin_route('custom-field.type.index', $model->field->id),
            'name' => trans('language.edit'),
            'icon' => 'fa fa-gears',
            'attributes' => [
              'class' => 'btn btn-xs btn-success'
            ]
          ];
        }

        // edit role
        if (allow('customfield.field.edit')) {
          $button[] = [
            'route' => admin_route('custom-field.edit', $model->field->id),
            'name' => trans('language.edit'),
            'icon' => 'fa fa-pencil-square-o',
            'attributes' => [
              'class' => 'btn btn-xs btn-primary'
            ]
          ];
        }

        // delete
        if (allow('customfield.field.destroy')) {
          $button[] = [
            'route' => 'javascript:void(0);',
            'name' => trans('language.delete'),
            'icon' => 'fa fa-trash-o',
            'attributes' => [
              'class' => 'btn btn-xs btn-danger',
              'data-url' => admin_route('custom-field.destroy', $model->field->id),
              'data-action' => 'confirm_to_delete',
              'data-message' => trans('language.confirm_to_delete')
            ]
          ];
        }

        return cnv_action_block($button);
      })
      ->editColumn('field.hidden', function ($model) {
        return sprintf(
          '<span class="label label-%s">%s</span>',
          $model->field->hidden ? 'warning' : 'success',
          $model->field->hidden ? trans('custom_field::language.hidden') : trans('custom_field::language.show')
        );
      })
      ->rawColumns(['name', 'action', 'field.hidden'])
      ->make(true);
  }

  /**
   * Create field
   * @param Field $field
   * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
   */
  public function create(Field $field)
  {
    $this->tpl->setData('title', trans('custom_field::language.custom_field_create'));
    $this->tpl->setData('field', $field);
    $this->tpl->setData('modules', app('custom.field')->getModulesWithKey());
    $this->tpl->setData('type', app('custom.field')->getTypesWithKey());
    $this->tpl->setTemplate('custom_field::field.create');

    // breadcrumb
    $this->tpl->breadcrumb()->add('/' . admin_path(), trans('language.dashboard'));
    $this->tpl->breadcrumb()->add('admin.custom-field.create', trans('custom_field::language.custom_field_create'));

    return $this->tpl->render();
  }

  /**
   * Store field
   *
   * @param Request $request
   * @return \Illuminate\Http\JsonResponse|void
   */
  public function store(Request $request)
  {
    if (! $request->ajax()) {
      return;
    }

    $data = $request->except(['_token', 'language']);
    $data['hidden'] = $request->has('hidden') ? true : false;
    $data['require'] = $request->has('require') ? true : false;
    $data['module'] = $request->input('module_name');

    if ($field = Field::create($data)) {
      $field->saveLanguages($request->only('language'));

      return response()->json([
        'status' => 200,
        'message' => trans('language.create_success'),
        'redirect_url' => admin_route('custom-field.edit', $field->id)
      ]);
    } else {
      return response()->json([
        'status' => 500,
        'message' => trans('language.create_fail'),
      ]);
    }
  }

  /**
   * Edit field
   * @param int $id
   * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
   */
  public function edit($id)
  {
    $field = Field::whereId($id)->with('languages')->firstOrFail();

    $this->tpl->setData('title', trans('custom_field::language.custom_field_edit'));
    $this->tpl->setData('field', $field);
    $this->tpl->setData('modules', app('custom.field')->getModulesWithKey());
    $this->tpl->setData('type', app('custom.field')->getTypesWithKey());
    $this->tpl->setTemplate('custom_field::field.edit');

    // breadcrumb
    $this->tpl->breadcrumb()->add('/' . admin_path(), trans('language.dashboard'));
    $this->tpl->breadcrumb()->add(admin_route('custom-field.edit', $id), trans('custom_field::language.custom_field_create'));

    return $this->tpl->render();
  }

  /**
   * Update field
   *
   * @param Request $request
   * @return \Illuminate\Http\JsonResponse|void
   */
  public function update(Request $request, $id)
  {
    if (! $request->ajax()) {
      return;
    }
    $field = Field::whereId($id)->with('languages')->firstOrFail();

    $data = $request->except(['_token', 'language']);
    $data['hidden'] = $request->has('hidden') ? true : false;
    $data['require'] = $request->has('require') ? true : false;
    unset($data['type']);
    unset($data['module']);

    if ($field->update($data)) {
      $field->saveLanguages($request->only('language'));

      return response()->json([
        'status' => 200,
        'message' => trans('language.update_success')
      ]);
    } else {
      return response()->json([
        'status' => 500,
        'message' => trans('language.update_fail'),
      ]);
    }
  }

  public function destroy(Request $request, $id)
  {
    if (! $request->ajax()) {
      return;
    }
    $field = Field::whereId($id)->with('languages')->firstOrFail();

    if ($field->delete()) {
      return response()->json([
        'status' => 200,
        'message' => trans('language.delete_success')
      ]);
    } else {
      return response()->json([
        'status' => 500,
        'message' => trans('language.delete_fail'),
      ]);
    }
  }
}
