<?php

namespace Modules\News\Models;

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
class PostCategory extends Model
{
    use ModelNested, ModelLanguages, RecordsActivity, Seoable;

    protected $fillable = [
        'parent_id',
        'published',
        'thumbnail',
        'level'
    ];

    protected $casts = [
        'published' => 'boolean',
    ];

    protected $with = [
        'languages', 'children'
    ];

    public function languages()
    {
        return $this->hasMany(PostCategoryLanguage::class, 'post_category_id');
    }

    public function posts()
    {
        return $this->belongsToMany(Post::class, 'post_category', 'post_category_id', 'post_id');
    }

    public function parent()
    {
        return $this->belongsTo(static::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(static::class, 'parent_id');
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
        return admin_route('post.category.index');
    }

    public function getParentForSelection($locale = null, $root = true, $get_all = true)
    {
        return $this->getNestedMenusForChoose($locale, $root, false, $get_all);
    }
}
