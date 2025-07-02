<?php

namespace Modules\CustomField\Models;

use App\Traits\ModelLanguages;
use Illuminate\Database\Eloquent\Model;

class Field extends Model
{
    use ModelLanguages;

    protected $fillable = [
        'slug',
        'type',
        'hidden',
        'require',
        'module'
    ];

    protected $casts = [
        'hidden' => 'boolean',
        'require' => 'boolean'
    ];

    public static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            $model->setAttribute('slug', str_replace('-', '_', $model->slug));
        });
    }

    /*
     * RELATIONSHIP
     */
    public function languages()
    {
        return $this->hasMany(FieldLanguage::class);
    }

    public function typeDatas()
    {
        return $this->hasMany(FieldTypeData::class, 'field_id');
    }

    public function datas()
    {
        return $this->hasMany(FieldData::class);
    }

    public function scopeModule($query, $module)
    {
        return $query->where('module', $module);
    }

    public function scopeActivated($query)
    {
        return $query->where('hidden', false);
    }
}