<?php

namespace Modules\Royalty\Repositories;

use Illuminate\Support\Facades\Cache;
use Modules\Royalty\Models\RoyaltyCategory;

class RoyaltyCategoryRepository
{
  protected $category;

  public function __construct(RoyaltyCategory $category)
  {
    $this->category = $category;
  }

  public function query()
  {
    return $this->category->query();
  }

  public function all()
  {
    return $this->category->all();
  }


  public function getViaId($id)
  {
    $key = 'get_royalty_category_by_' . $id;

    if (! Cache::has($key)) {
      $category = $this->category->where('id', $id)->firstOrFail();
      Cache::put($key, $category, 3600);
    }
    return Cache::get($key);
  }


  public function getCategory($onlyShowPublished = true)
  {
    $query = $this->category;

    if ($onlyShowPublished) {
      $query = $query->where('active',  true);
    }

    return $query->get();
  }
}
