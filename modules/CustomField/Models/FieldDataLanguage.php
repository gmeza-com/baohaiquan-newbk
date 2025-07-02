<?php

namespace Modules\CustomField\Models;

use Illuminate\Database\Eloquent\Model;

class FieldDataLanguage extends Model
{
    protected $fillable = [
        'value',
        'locale',
        'field_data_id'
    ];

    public $timestamps = false;

    public function data()
    {
        return $this->belongsTo(FieldData::class, 'field_data_id');
    }
}