<?php

namespace Modules\Royalty\Repositories;

use Illuminate\Support\Facades\Cache;
use Modules\Royalty\Models\RoyaltyStatus;

class RoyaltyStatusRepository
{
  protected $status;

  public function __construct(RoyaltyStatus $status)
  {
    $this->status = $status;
  }

  public function query()
  {
    return $this->status->query();
  }

  public function all()
  {
    return $this->status->all();
  }


  public function getViaId($id)
  {
    $key = 'get_royalty_status_by_' . $id;

    if (! Cache::has($key)) {
      $status = $this->status->where('id', $id)->firstOrFail();
      Cache::put($key, $status, 3600);
    }
    return Cache::get($key);
  }


  public function getStatus()
  {
    return $this->status->get();
  }
}
