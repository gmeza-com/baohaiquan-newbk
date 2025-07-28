<?php

namespace Modules\CustomField\Models;

use App\Traits\ModelLanguages;
use Illuminate\Database\Eloquent\Model;

class FieldData extends Model
{
  use ModelLanguages;

  protected $fillable = [
    'field_type_data_id',
    'field_id',
    'module_id',
    'module_type'
  ];

  protected $with = ['field', 'typeData'];

  protected $table = 'field_datas';

  public $timestamps = false;

  public function languages()
  {
    return $this->hasMany(FieldDataLanguage::class, 'field_data_id');
  }

  public function field()
  {
    return $this->belongsTo(Field::class, 'field_id');
  }

  public function typeData()
  {
    return $this->belongsTo(FieldTypeData::class, 'field_type_data_id');
  }

  public function module()
  {
    return $this->morphTo();
  }
}
