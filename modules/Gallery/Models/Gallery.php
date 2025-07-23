<?php

namespace Modules\Gallery\Models;

use App\Traits\ModelLanguages;
use Illuminate\Database\Eloquent\Model;
use Modules\Activity\Traits\RecordsActivity;
use Modules\User\Models\User;
use Plugins\SEO\Traits\Seoable;
use Plugins\ViewCounter\Traits\ViewCounter;

class Gallery extends Model
{
  use ModelLanguages, RecordsActivity, Seoable, ViewCounter;

  protected $table = 'gallery';

  protected $fillable = [
    'featured',
    'published',
    'published_at',
    'thumbnail',
    'user_id',
    'type'
  ];

  protected $with = [
    'languages'
  ];

  protected $dates = [
    'created_at',
    'updated_at',
    'published_at'
  ];

  protected $casts = [
    'featured' => 'boolean',
    'published' => 'boolean'
  ];

  public static function boot()
  {
    parent::boot();

    static::creating(function ($model) {
      $user_id = auth()->user() ? auth()->user()->id : User::first()->id;
      $model->user_id = $user_id;
    });
  }

  public function languages()
  {
    return $this->hasMany(GalleryLanguage::class);
  }

  public function author()
  {
    return $this->belongsTo(User::class, 'user_id');
  }

  public function categories()
  {
    return $this->belongsToMany(GalleryCategory::class, 'gallery_category');
  }

  public function podcast_categories()
  {
    return $this->belongsToMany(PodcastCategory::class, 'gallery_podcast_category');
  }

  public function royalties()
  {
    return $this->hasMany(\Modules\Royalty\Models\Royalty::class, 'gallery_id');
  }

  /**
   * @param $value
   * @return string
   */
  public function getNameOnLogsAttribute($value)
  {
    return $this->language('name') ?: 'gallery';
  }

  /**
   * @param $value
   * @return string
   */
  public function getUrlOnLogsAttribute($value)
  {
    return admin_route('gallery.index');
  }



  public function getListCategoriesAttribute($value)
  {
    return $this->categories->map(function ($category) {
      return link_to_route('gallery.category.show', $category->language('name'), $category->language('slug'));
    })->toArray();
  }

  public function getListPodcastCategoriesAttribute($value)
  {
    return $this->podcast_categories->map(function ($category) {
      return link_to_route('gallery.podcast-category.show', $category->language('name'), $category->language('slug'));
    })->toArray();
  }
}
