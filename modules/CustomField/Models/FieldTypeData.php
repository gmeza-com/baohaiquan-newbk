<?php

namespace Modules\CustomField\Models;

use App\Traits\ModelLanguages;
use Illuminate\Database\Eloquent\Model;

class FieldTypeData extends Model
{
    use ModelLanguages;

    protected $fillable = [
        'position',
        'field_id'
    ];

    public $timestamps = false;

    public function languages()
    {
        return $this->hasMany(FieldTypeDataLanguage::class, 'field_type_data_id');
    }

    public function field()
    {
        return $this->belongsTo(Field::class);
    }
}