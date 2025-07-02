<?php

namespace Modules\CustomField\Models;

use Illuminate\Database\Eloquent\Model;

class FieldLanguage extends Model
{
    protected $fillable = [
        'name',
        'locale',
        'field_id'
    ];

    public $timestamps = false;

    public function field()
    {
        return $this->belongsTo(Field::class);
    }
}