<?php

namespace Plugins\SEO\Models;

use App\Traits\ModelLanguages;
use Illuminate\Database\Eloquent\Model;

class Seo extends Model
{
    use ModelLanguages;

    protected $table = 'seo';

    protected $fillable = [
        'seoable_id',
        'seoable_type'
    ];

    public $timestamps = false;

    public function languages()
    {
        return $this->hasMany(SeoLanguage::class, 'seo_id');
    }

    public function seoable()
    {
        return $this->morphTo();
    }
}