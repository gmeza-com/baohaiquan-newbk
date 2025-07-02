<?php
namespace Modules\Option\Models;

use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    protected $fillable = [
        'name',
        'value',
        'autoload'
    ];

    protected $casts = [
        'autoload' => 'boolean'
    ];
}