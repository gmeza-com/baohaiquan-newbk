<?php

namespace Modules\Gallery\Models;

use App\Traits\ModelLanguages;
use App\Traits\ModelNested;
use Illuminate\Database\Eloquent\Model;
use Modules\Activity\Traits\RecordsActivity;
use Plugins\SEO\Traits\Seoable;
use Plugins\ViewCounter\Traits\ViewCounter;

/**
 * @property bool published
 * @property integer parent_id
 * @property string thumbnail
 */
class GalleryCategory extends Model
{
    use ModelLanguages, RecordsActivity, Seoable;

    protected $fillable = [
        'published',
        'thumbnail',
        'level'
    ];

    protected $casts = [
        'published' => 'boolean'
    ];

    protected $with = [
        'languages'
    ];

    public function languages()
    {
        return $this->hasMany(GalleryCategoryLanguage::class, 'gallery_category_id');
    }

    public function gallery()
    {
        return $this->belongsToMany(Gallery::class, 'gallery_category', 'gallery_category_id', 'gallery_id');
    }

    /**
     * @param $value
     * @return string
     */
    public function getNameOnLogsAttribute($value)
    {
        return $this->language('name') ?: 'category';
    }

    /**
     * @param $value
     * @return string
     */
    public function getUrlOnLogsAttribute($value)
    {
        return admin_route('gallery.category.index');
    }

    public function getForSelection()
    {
        return (new static)->with('languages')->get()->mapWithKeys(function ($model) {
            return [$model->id => $model->language('name')];
        })->toArray();
    }
}
