<?php

namespace Modules\CustomField\Http\Controllers;

use App\Http\Controllers\AdminController;
use App\Libraries\Str;
use Illuminate\Http\Request;
use Modules\CustomField\Models\Field;
use Modules\CustomField\Models\FieldData;
use Modules\CustomField\Models\FieldLanguage;
use Modules\CustomField\Models\FieldTypeData;
use Yajra\DataTables\Facades\DataTables;

class TypeController extends AdminController
{
  /**
   * Index
   * @param Request $request
   * @param Field $fieldId
   * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|void
   */
  public function index(Request $request, $fieldId)
  {
    $field = Field::findOrFail($fieldId);
    if ($request->ajax()) {
      return $this->data($request, $field);
    }

    $this->tpl->setData('title', trans('custom_field::language.custom_field'));
    $this->tpl->setData('field', $field);
    $this->tpl->setTemplate('custom_field::field.type.index');

    // breadcrumb
    $this->tpl->breadcrumb()->add('/' . admin_path(), trans('language.dashboard'));
    $this->tpl->breadcrumb()->add('admin.custom-field.index', trans('custom_field::language.custom_field'));
    $this->tpl->breadcrumb()->add(admin_route('custom-field.type.index', $field->id), trans('custom_field::language.custom_field_type'));

    // Datatables
    $this->tpl->datatable()->setSource(admin_route('custom-field.type.index', $field->id));
    $this->tpl->datatable()->addColumn(
      '#',
      'position',
      ['class' => 'col-md-1']
    );
    $this->tpl->datatable()->addColumn(
      trans('language.slug'),
      'slug',
      ['class' => 'col-md-3'],
      false,
      false
    );
    $this->tpl->datatable()->addColumn(
      trans('language.name'),
      'value',
      ['class' => 'col-md-7'],
      false,
      false
    );

    return $this->tpl->render();
  }

  /**
   * Get list fields
   * @param Request $request
   * @return mixed
   */
  public function data(Request $request, $field)
  {
    $model = $field->typeDatas()->with('languages');



    return Datatables::of($model)
      ->addColumn('slug', function ($model) {
        return $model->language('slug');
      })
      ->addColumn('value', function ($model) {
        return $model->language('value');
      })
      ->addColumn('action', function ($model) use ($field) {
        app('helper')->load('buttons');
        $button = [];

        // edit role
        // if (allow('customfield.field.edit')) {
        //   $button[] = [
        //     'route' => admin_route('custom-field.type.edit', [
        //       'custom-field' => $field->id,
        //       'type' => $model->id
        //     ]),
        //     'name' => trans('language.edit'),
        //     'icon' => 'fa fa-pencil-square-o',
        //     'attributes' => [
        //       'class' => 'btn btn-xs btn-primary'
        //     ]
        //   ];
        // }

        // delete
        // if (allow('customfield.field.destroy')) {
        //   $button[] = [
        //     'route' => 'javascript:void(0);',
        //     'name' => trans('language.delete'),
        //     'icon' => 'fa fa-trash-o',
        //     'attributes' => [
        //       'class' => 'btn btn-xs btn-danger',
        //       'data-url' => admin_route('custom-field.type.destroy', [
        //         'custom-field' => $field->id,
        //         'type' => $model->id
        //       ]),
        //       'data-action' => 'confirm_to_delete',
        //       'data-message' => trans('language.confirm_to_delete')
        //     ]
        //   ];
        // }

        return cnv_action_block($button);
      })
      ->rawColumns(['name', 'action', 'hidden'])
      ->make(true);
  }

  /**
   * Create field
   *
   * @param $fieldId
   * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
   */
  public function create($fieldId)
  {
    $field = Field::findOrFail($fieldId);
    $data = new FieldData();
    $data->position = $field->typeDatas()->count() + 1;

    $this->tpl->setData('title', trans('custom_field::language.custom_field_create'));
    $this->tpl->setData('field', $field);
    $this->tpl->setData('fieldData', $data);
    $this->tpl->setTemplate('custom_field::field.type.create');

    // breadcrumb
    $this->tpl->breadcrumb()->add('/' . admin_path(), trans('language.dashboard'));
    $this->tpl->breadcrumb()->add('admin.custom-field.index', trans('custom_field::language.custom_field'));
    $this->tpl->breadcrumb()->add(admin_route('custom-field.type.index', $field->id), trans('custom_field::language.custom_field_type'));

    return $this->tpl->render();
  }

  /**
   * Store field
   *
   * @param Request $request
   * @param $fieldId
   * @return \Illuminate\Http\JsonResponse|void
   */
  public function store(Request $request, $fieldId)
  {
    $field = Field::findOrFail($fieldId);

    if (! $request->ajax()) {
      return;
    }

    $data = $request->except(['_token', 'language']);
    $data['field_id'] = $field->id;

    $lang = $request->only('language');
    foreach (config('cnv.languages') as $language) {
      if (@$lang['language'][$language['locale']]['value']) {
        $lang['language'][$language['locale']]['slug'] = Str::friendlySlug($lang['language'][$language['locale']]['value']);
      }
    }

    if ($fieldType = FieldTypeData::create($data)) {
      $fieldType->saveLanguages($lang);

      return response()->json([
        'status' => 200,
        'message' => trans('language.create_success'),
        'redirect_url' => admin_route('custom-field.type.edit', [
          'custom-field' => $field->id,
          'type' => $fieldType->id
        ])
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
   *
   * @param $fieldId
   * @param $typeId
   * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
   */
  public function edit($fieldId, $typeId)
  {
    $field = Field::findOrFail($fieldId);
    $data = FieldTypeData::findOrFail($typeId);
    $this->tpl->setData('title', trans('custom_field::language.custom_field_edit'));
    $this->tpl->setData('field', $field);
    $this->tpl->setData('fieldData', $data);
    $this->tpl->setTemplate('custom_field::field.type.edit');

    // breadcrumb
    $this->tpl->breadcrumb()->add('/' . admin_path(), trans('language.dashboard'));
    $this->tpl->breadcrumb()->add('admin.custom-field.index', trans('custom_field::language.custom_field'));
    $this->tpl->breadcrumb()->add(admin_route('custom-field.type.index', $field->id), trans('custom_field::language.custom_field_type'));

    return $this->tpl->render();
  }

  /**
   * Update field
   *
   * @param Request $request
   * @return \Illuminate\Http\JsonResponse|void
   */
  public function update(Request $request, $fieldId, $dataId)
  {
    if (! $request->ajax()) {
      return;
    }
    $field = Field::findOrFail($fieldId);
    $type = FieldTypeData::findOrFail($dataId);

    $data = $request->except(['_token', 'language']);
    $data['field_id'] = $field->id;

    $lang = $request->only('language');
    foreach (config('cnv.languages') as $language) {
      if (@$lang['language'][$language['locale']]['value']) {
        $lang['language'][$language['locale']]['slug'] = Str::friendlySlug($lang['language'][$language['locale']]['value']);
      }
    }

    if ($type->update($data)) {
      $type->saveLanguages($lang);

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

  public function destroy(Request $request, $fieldId, $fielTypeDataId)
  {
    if (! $request->ajax()) {
      return;
    }
    $type = FieldTypeData::findOrFail($fielTypeDataId);

    if ($type->delete()) {
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
