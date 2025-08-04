<?php

namespace Modules\Royalty\Models;

use Illuminate\Database\Eloquent\Model;

class RoyaltyStatus extends Model
{
  protected $table = 'royalty_statuses';
  protected $fillable = ['ordering', 'name'];

  const CANCELED = 4;

  public function royalty()
  {
    return $this->hasMany(Royalty::class, 'royalty');
  }

  /**
   * @param $value
   * @return string
   */
  public function getNameOnLogsAttribute($value)
  {
    return $this->language('name') ?: 'status';
  }

  /**
   * @param $value
   * @return string
   */
  public function getUrlOnLogsAttribute($value)
  {
    return admin_route('royalty.status.index');
  }
}
