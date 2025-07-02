<?php

namespace Modules\News\Models;

use Illuminate\Database\Eloquent\Model;

class PostHistory extends Model
{
    protected $fillable = [
        'locale',
        'name',
        'origin_content',
        'post_id',
        'user_id',
    ];

    public $timestamps = true;

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public function author()
    {
        return $this->belongsTo(\Modules\User\Models\User::class, 'user_id');
    }

    public function languages()
    {
        return $this->hasMany(PostLanguage::class);
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

}
