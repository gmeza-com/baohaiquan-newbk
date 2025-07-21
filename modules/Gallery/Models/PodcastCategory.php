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
class PodcastCategory extends Model
{
    use ModelLanguages, RecordsActivity, Seoable;

    protected $fillable = [
        'published',
        'icon',
    ];

    protected $casts = [
        'published' => 'boolean'
    ];

    protected $with = [
        'languages'
    ];

    public function languages()
    {
        return $this->hasMany(PodcastCategoryLanguage::class, 'podcast_category_id');
    }

    public function gallery()
    {
        return $this->belongsToMany(Gallery::class, 'gallery_podcast_category', 'gallery_podcast_category_id', 'gallery_id');
    }

    /**
     * @param $value
     * @return string
     */
    public function getNameOnLogsAttribute($value)
    {
        return $this->language('name') ?: 'podcast category';
    }

    /**
     * @param $value
     * @return string
     */
    public function getUrlOnLogsAttribute($value)
    {
        return admin_route('gallery.podcast-category.index');
    }

    public function getForSelection()
    {
        return (new static)->with('languages')->get()->mapWithKeys(function ($model) {
            return [$model->id => $model->language('name')];
        })->toArray();
    }
}
