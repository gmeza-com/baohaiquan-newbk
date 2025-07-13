<?php

namespace Modules\News\Models;

use App\Traits\ModelLanguages;
use Illuminate\Database\Eloquent\Model;
use Modules\Activity\Traits\RecordsActivity;
use Modules\CustomField\Traits\HasCustomFields;
use Plugins\SEO\Traits\Seoable;
use Plugins\ViewCounter\Traits\ViewCounter;
use Carbon\Carbon;

class Post extends Model
{
  use ModelLanguages, RecordsActivity, Seoable, ViewCounter, HasCustomFields;

  protected $fillable = [
    'featured',
    'published',
    'status',
    'published_at',
    'thumbnail',
    'user_id',
    'featured_started_at',
    'featured_ended_at',
    'prefix',
    'hide',
    'cancel_message'
  ];

  protected $dates = [
    'created_at',
    'updated_at',
    'published_at',
    'featured_started_at',
    'featured_ended_at'
  ];

  protected $casts = [
    'featured' => 'boolean',
    'status' => 'boolean',
    'hide' => 'boolean'
  ];

  protected $with = [
    'languages'
  ];

  public function languages()
  {
    return $this->hasMany(PostLanguage::class);
  }

  public function author()
  {
    return $this->belongsTo(\Modules\User\Models\User::class, 'user_id');
  }

  public function categories()
  {
    return $this->belongsToMany(PostCategory::class, 'post_category', 'post_id', 'post_category_id');
  }

  /**
   * @param $value
   * @return string
   */
  public function getNameOnLogsAttribute($value)
  {
    return $this->language('name') ?: 'blog';
  }

  /**
   * @param $value
   * @return string
   */
  public function getUrlOnLogsAttribute($value)
  {
    return admin_route('post.index');
  }

  public function getListCategoriesAttribute($value)
  {
    return $this->categories->map(function ($category) {
      return link_to_route('post.category.show', $category->language('name'), $category->language('slug'));
    })->toArray();
  }

  public function getPublishedAtAttribute($value)
  {
    return $this->getAttribute('id') ? new Carbon($value) : Carbon::now();
  }

  public function getFeaturedEndedAtAttribute($value)
  {
    return new Carbon($value) ?: Carbon::now();
  }

  public function getFeaturedStartedAtAttribute($value)
  {
    return new Carbon($value) ?: Carbon::now();
  }

  public function getShowPublishedStatusAttribute($value)
  {
    $class = 'warning';
    $text = trans('news::language.waiting_level_1');

    switch ($this->published) {
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

  public function scopeFeatured($query)
  {
    return $query;
  }

  public function getCouldBeApprovedPostAttribute($value)
  {
    if ($this->published <= 3 && allow('news.post.approved_level_3')) {
      return true;
    } elseif ($this->published <= 2 && allow('news.post.approved_level_2')) {
      return true;
    } elseif ($this->published <= 1 && allow('news.post.approved_level_1')) {
      return true;
    }

    return false;
  }

  public function getApprovedAttribute($value)
  {
    if ($this->published == 3 && allow('news.post.approved_level_3')) {
      return true;
    } elseif ($this->published == 2 && allow('news.post.approved_level_2')) {
      return true;
    } elseif ($this->published == 1 && allow('news.post.approved_level_1')) {
      return true;
    }

    return false;
  }
}
