<?php

namespace Modules\News\Models;

use Illuminate\Database\Eloquent\Model;

class PostLanguage extends Model
{
    protected $fillable = [
        'locale',
        'name',
        'slug',
        'description',
        'content',
        'tags',
        'post_id',
        'note',
        'quote',
        'second_name',
        'third_name',
    ];

    public $timestamps = false;

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function getLinkAttribute()
    {
        return route('post.show', [
            'slug' => $this->getAttribute('slug')
        ]);
    }
}
