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
    'type',
    'prefix',
    'has_royalty',
    'approve_level',
    'hide'
  ];

  protected $with = [
    'languages'
  ];

  protected $dates = [
    'created_at',
    'updated_at',
    'published_at',
    'date_featured_started_at',
    'date_featured_ended_at'
  ];

  protected $casts = [
    'featured' => 'boolean',
    'published' => 'boolean',
    'has_royalty' => 'boolean',
    'hide' => 'boolean'
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

  public function getShowPublishedStatusAttribute($value)
  {
    $class = 'warning';
    $text = trans('news::language.waiting_level_1');

    switch ($this->approve_level) {
      case -1:
        $class = 'danger';
        $text = 'Đã hủy';
        break;
      case 1:
        if (allow('news.post.approved_level_1')) {
          $text = trans('news::language.waiting_level_2');
        } else {
          $class = 'info';
          $text = trans('news::language.approved_by_level_1');
        }
        break;
      case 2:
        if (allow('news.post.approved_level_2')) {
          $text = trans('news::language.waiting_level_3');
        } else {
          $class = 'info';
          $text = trans('news::language.approved_by_level_2');
        }
        break;
      case 3:
        $class = 'success';
        $text = $this->getOriginal('published_at') ? trans('news::language.approved_by_level_3') : 'Đã duyệt chờ đăng';
        break;
    }
    return sprintf('<span class="label label-%s">%s</span>', $class, $text);
  }


  public function getCouldBeApprovedPostAttribute($value)
  {
    if ($this->approve_level <= 3 && allow('gallery.gallery.approved_level_3')) {
      return true;
    } elseif ($this->approve_level <= 2 && allow('gallery.gallery.approved_level_2')) {
      return true;
    } elseif ($this->approve_level <= 1 && allow('gallery.gallery.approved_level_1')) {
      return true;
    }

    return false;
  }

  public function getApprovedAttribute($value)
  {
    if ($this->approve_level == 3 && allow('gallery.gallery.approved_level_3')) {
      return true;
    } elseif ($this->approve_level == 2 && allow('gallery.gallery.approved_level_2')) {
      return true;
    } elseif ($this->approve_level == 1 && allow('gallery.gallery.approved_level_1')) {
      return true;
    }

    return false;
  }
}
