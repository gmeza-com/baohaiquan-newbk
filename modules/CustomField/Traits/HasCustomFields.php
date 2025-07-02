<?php

namespace Modules\CustomField\Traits;

use Modules\CustomField\Libraries\ModelCustomField;
use Modules\CustomField\Models\Field;
use Modules\CustomField\Models\FieldData;

trait HasCustomFields
{
    protected $customFields;

    public static function bootHasCustomFields()
    {
        static::saved(function($model) {
            $request = request()->all();

            if(isset($request['customfield'])) {
                foreach ($request['customfield'] as $field => $data) {
                    $field = Field::where('slug', $field)->firstOrFail();
                    /**
                     * @var FieldData $fieldData
                     */
                    if (! ($fieldData = FieldData::where('module_id', $model->id)
                        ->where('module_type', get_class($model))
                        ->where('field_id', $field->id)
                        ->first())) {
                        $fieldData = FieldData::create([
                            'module_id' => $model->id,
                            'module_type' => get_class($model),
                            'field_id' => $field->id
                        ]);
                    }

                    foreach (config('cnv.languages') as $language) {
                        $data['language'][$language['locale']] = [
                            'value' => app('custom.field')->getField($field)->set($data['language'][$language['locale']])
                        ];
                    }
                    $fieldData->saveLanguages($data);
                }
            }
        });

        static::deleting(function($model) {
            $model->fieldDatas()->delete();
        });
    }
    
    public function fields()
    {
        return $this->morphMany(Field::class, 'module');
    }

    public function fieldDatas()
    {
        return $this->morphMany(FieldData::class, 'module');
    }

    public function customField($locale = null)
    {
        $locale = ($locale ?: session('lang')) ?: config('cnv.default_language');

        if (!isset($this->customFields[$locale])) {
            $this->customFields[$locale] = new ModelCustomField($this->fieldDatas, $locale);
        }

        return $this->customFields[$locale];
    }
}