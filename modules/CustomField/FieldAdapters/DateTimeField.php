<?php

namespace Modules\CustomField\FieldAdapters;

use Illuminate\Http\Request;
use Modules\CustomField\Models\Field;

class DateTimeField implements FieldApdaper
{
    public function get($value)
    {
        return new \Carbon\Carbon($value);
    }

    public function set($value)
    {
        return $value;
    }

    public function form(Field $field, $model, $locale)
    {
        return view('custom_field::types.datetime', compact('field', 'model', 'locale'));
    }
}