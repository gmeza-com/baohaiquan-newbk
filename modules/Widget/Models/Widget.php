<?php

namespace Modules\Widget\Models;

use Illuminate\Database\Eloquent\Model;

class Widget extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'content',
        'setting',
        'lock',
        'published',
        'type',
    ];

    protected $casts = [
        'setting' => 'array',
        'lock' => 'boolean',
        'published' => 'boolean',
    ];
}