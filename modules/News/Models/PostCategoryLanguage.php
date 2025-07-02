<?php

namespace Modules\News\Models;

use Illuminate\Database\Eloquent\Model;

class PostCategoryLanguage extends Model
{
    protected $fillable = [
        'locale',
        'name',
        'slug'
    ];

    public $timestamps = false;

    public function category()
    {
        return $this->belongsTo(PostCategory::class, 'post_category_id');
    }

    public function getNameWithPrefixAttribute($value)
    {
        return $this->name;
    }

    public function getLinkAttribute()
    {
        return route('post.category.show', $this->getAttribute('slug'));
    }
}
