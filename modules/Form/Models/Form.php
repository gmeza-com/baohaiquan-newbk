<?php

namespace Modules\Form\Models;

use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    protected $fillable = [
        'slug',
        'field'
    ];

    protected $casts = [
        'field' => 'array'
    ];

    public function formDatas()
    {
        return $this->hasMany(FormData::class);
    }
}