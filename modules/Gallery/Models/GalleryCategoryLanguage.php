<?php

namespace Modules\Gallery\Models;

use Illuminate\Database\Eloquent\Model;

class GalleryCategoryLanguage extends Model
{
    protected $fillable = [
        'locale',
        'name',
        'slug'
    ];

    public $timestamps = false;

    public function category()
    {
        return $this->belongsTo(GalleryCategory::class, 'gallery_category_id');
    }

    public function getLinkAttribute()
    {
        return route('gallery.category.show', $this->getAttribute('slug'));
    }
}