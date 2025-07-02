<?php

namespace Plugins\ViewCounter\Models;

use Illuminate\Database\Eloquent\Model;

class View extends Model
{
    protected $fillable = [
        'subject_id',
        'subject_type',
        'count'
    ];

    public $timestamps = false;

    public function subject()
    {
        return $this->morphTo();
    }
}