<?php

namespace Modules\CustomField\FieldAdapters;

use Illuminate\Http\Request;
use Modules\CustomField\Models\Field;

interface FieldApdaper
{
    public function get($value);

    public function set($value);

    public function form(Field $field, $model, $locale);
}