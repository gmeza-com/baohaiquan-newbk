<?php

namespace Modules\Gallery\Models;

use Illuminate\Database\Eloquent\Model;

class PodcastCategoryLanguage extends Model
{
    protected $fillable = [
        'locale',
        'name',
        'slug'
    ];

    public $timestamps = false;

    public function category()
    {
        return $this->belongsTo(PodcastCategory::class, 'podcast_category_id');
    }

    public function getLinkAttribute()
    {
        return route('gallery.podcast-category.show', $this->getAttribute('slug'));
    }
}