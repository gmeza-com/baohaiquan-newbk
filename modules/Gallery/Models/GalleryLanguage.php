<?php

namespace Modules\Gallery\Models;

use App\Libraries\Str;
use Illuminate\Database\Eloquent\Model;

class GalleryLanguage extends Model
{
    protected $fillable = [
        'locale',
        'name',
        'slug',
        'description',
        'content',
        'gallery_id',
        'post_content',
        'tags',
        'quote',
        'note'
    ];

    public $timestamps = false;

    public function gallery()
    {
        return $this->belongsTo(Gallery::class);
    }

    public function getLinkAttribute()
    {
        return route('gallery.show', $this->getAttribute('slug'));
    }

    public function getContentAttribute($value)
    {
        return unserialize($value);
    }

    public function setContentAttribute($value)
    {
        $this->attributes['content'] = serialize(
            collect($value)
        );
    }

    public function getThumbnailAttribute()
    {
        return $this->gallery->thumbnail ?: Str::parseYoutubeLinkToThumbnail($this->content->get('link'));
    }
}
