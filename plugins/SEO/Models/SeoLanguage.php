<?php

namespace Plugins\SEO\Models;

use Illuminate\Database\Eloquent\Model;

class SeoLanguage extends Model
{
    protected $table = 'seo_languages';

    protected $fillable = [
        'seo_id',
        'locale',
        'title',
        'description'
    ];

    public $timestamps = false;

    public function seo()
    {
        return $this->belongsTo(Seo::class);
    }
}