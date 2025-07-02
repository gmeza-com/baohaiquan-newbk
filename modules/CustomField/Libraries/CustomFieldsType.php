<?php

namespace Modules\CustomField\Libraries;

use Modules\CustomField\FieldAdapters\DateField;
use Modules\CustomField\FieldAdapters\DateTimeField;
use Modules\CustomField\FieldAdapters\EditorField;
use Modules\CustomField\FieldAdapters\NumberField;
use Modules\CustomField\FieldAdapters\SelectField;
use Modules\CustomField\FieldAdapters\TextareaField;
use Modules\CustomField\FieldAdapters\TextField;
use Modules\CustomField\FieldAdapters\TimeField;
use Modules\CustomField\Models\Field;

class CustomFieldsType
{
    /**
     * All type forms
     * @var array
     */
    protected $typesAdapter = [
        'text' => TextField::class,
        'textarea' => TextareaField::class,
        'editor' => EditorField::class,
        'number' => NumberField::class,
        'select' => SelectField::class,
        'date' => DateField::class,
        'time' => TimeField::class,
        'datetime' => DateTimeField::class,
    ];
    /**
     * Supported modules
     * @var array
     */
    protected $modulesSupperted = [
        'product' => \Modules\Product\Models\Product::class,
        'blog' => \Modules\Blog\Models\Post::class,
        'customer' => \Modules\Customer\Models\Customer::class,
    ];

    /**
     * Get all types
     * @return array
     */
    public function getTypes()
    {
        return $this->typesAdapter;
    }

    /**
     * Get modules name for choose
     * @return array
     */
    public function getModulesWithKey()
    {
        return collect($this->modulesSupperted)
            ->filter(function($value, $key) {
                return class_exists($value);
            })
            ->mapWithKeys(function ($value, $key) {
                return [$key => ucfirst($key)];
            })->toArray();
    }

    /**
     * Get all types for choose
     * @return array
     */
    public function getTypesWithKey()
    {
        return collect($this->typesAdapter)->mapWithKeys(function ($value, $key) {
            return [$key => ucfirst($key)];
        })->toArray();
    }

    protected function getClassViaType($type)
    {
        return isset($this->typesAdapter[$type]) ? $this->typesAdapter[$type] : false;
    }

    /**
     * Set field for forms
     *
     * @param Field $field
     * @return mixed
     *
     * @throws \Exception
     */
    public function getField(Field $field)
    {
        if ($getClass = $this->getClassViaType($field->type)) {
            if (class_exists($getClass)) {
                $apdater = new $getClass();
                return $apdater;
            }
            throw new \Exception($getClass. '::class not found !');
        }
    }
}