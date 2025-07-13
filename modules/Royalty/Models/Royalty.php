<?php

namespace Modules\Royalty\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\User\Models\User;

class Royalty extends Model
{
  protected $table = 'royalty';
  protected $fillable = ['user_id', 'category_id', 'status_id', 'amount', 'note', 'post_id', 'gallery_id', 'created_at', 'updated_at'];
  protected $dates = ['updated_at', 'created_at'];

  public function author()
  {
    return $this->belongsTo(User::class, 'user_id');
  }

  public function category()
  {
    return $this->belongsTo(RoyaltyCategory::class, 'category_id');
  }

  public function status()
  {
    return $this->belongsTo(RoyaltyStatus::class, 'status_id');
  }

  public function post()
  {
    return $this->belongsTo(\Modules\News\Models\Post::class, 'post_id');
  }

  public function gallery()
  {
    return $this->belongsTo(\Modules\Gallery\Models\Gallery::class, 'gallery_id');
  }


  /**
   * @param $value
   * @return string
   */
  public function getUrlOnLogsAttribute($value)
  {
    return admin_route('royalty.index');
  }



  public function getListCategoryAttribute($value)
  {
    return $this->categories()->map(function ($category) {
      return link_to_route('royalty.category.show', $category->name, $category->id);
    })->toArray();
  }
}
