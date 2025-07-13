<?php

namespace Modules\Royalty\Models;

use Illuminate\Database\Eloquent\Model;


class RoyaltyCategory extends Model
{

  protected $table = 'royalty_categories';
  protected $fillable = ['active', 'name', 'amount'];
  protected $dates = ['updated_at', 'created_at'];

  public function royalty()
  {
    return $this->hasMany(Royalty::class, 'royalty');
  }

  /**
   * @param $value
   * @return string
   */
  public function getUrlOnLogsAttribute($value)
  {
    return admin_route('royalty.category.index');
  }
}
