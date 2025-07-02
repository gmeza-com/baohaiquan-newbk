<?php

namespace Modules\CustomField\FieldAdapters;

use Illuminate\Http\Request;
use Modules\CustomField\Models\Field;

class EditorField implements FieldApdaper
{
    public function get($value)
    {
        return $value;
    }

    public function set($value)
    {
        return $value;
    }

    public function form(Field $field, $model, $locale)
    {
        return view('custom_field::types.editor', compact('field', 'model', 'locale'));
    }
}