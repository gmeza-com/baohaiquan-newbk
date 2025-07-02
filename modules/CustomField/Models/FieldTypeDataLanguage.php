<?php

namespace Modules\CustomField\Models;

use Illuminate\Database\Eloquent\Model;

class FieldTypeDataLanguage extends Model
{
    protected $fillable = [
        'slug',
        'value',
        'locale',
        'field_id'
    ];

    public $timestamps = false;

    public function typeData()
    {
        return $this->belongsTo(FieldTypeDataLanguage::class, 'field_type_data_id');
    }
}