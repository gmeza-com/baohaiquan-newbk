<?php

namespace Modules\CustomField\Libraries;

class ModelCustomField
{
    /**
     * @var \Illuminate\Database\Eloquent\Collection
     */
    protected $customFields;

    protected $locale;

    /**
     * ModelCustomField constructor.
     *
     * @param $locale
     * @param $fieldData
     */
    public function __construct($fieldData, $locale = null)
    {
        $this->customFields = $fieldData ?: collect([]);
        $this->locale = $locale;
    }

    public function __get($name)
    {
        if($this->customFields->isNotEmpty()) {
            /**
             * @var \Modules\CustomField\Models\FieldData $data
             */
            $data = $this->customFields->filter(function ($model) use ($name) {
                return $name === $model->field->slug;
            })->first();

            if ($data) {
                $customField = app('custom.field')
                    ->getField($data->field)
                    ->get($data->language('value', $this->locale));

                return $customField ?: null;
            }
        }
    }
}