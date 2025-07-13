<?php

namespace Modules\Royalty\Repositories;

use Carbon\Carbon;
use Modules\Royalty\Models\Royalty;
use Modules\Royalty\Models\RoyaltyCategory;
use Illuminate\Support\Facades\Cache;

class RoyaltyRepository
{
  protected $royalty;


  public function __construct(Royalty $royalty)
  {
    $this->royalty = $royalty;
  }


  public function getViaId($id)
  {
    return $this->royalty->where('id', $id)->firstOrFail();
  }

  public function search($keyword, $type = false, $limit = 10)
  {
    $keyword = '%' . $keyword . '%';
    $royalty = $this->royalty
      ->where('name', 'like', $keyword);

    if ($type) {
      $royalty->whereHas('gallery', function ($query) use ($type) {
        if ($type)
          $query->where('type', $type);
      });
    }

    return $royalty->limit($limit)->get();
  }

  public function getList($type = false, $paginate = 12)
  {
    $royalty = $this->royalty;

    if ($type) {
      $royalty = $royalty->where('type', $type);
    }

    $royalty = $royalty->orderBy('featured')->latest();
    return $paginate ? $royalty->paginate($paginate) : $royalty->get();
  }

  public function getRoyaltyViaCategoires(RoyaltyCategory $category, $perPage = 10)
  {
    $query = $category->royalty();
    $query = $query->orderBy('created_at', 'desc');
    $query = $query->latest();

    return $perPage ? $query->paginate($perPage) : $query->get();
  }

  public function getRoyalty(
    $limit = 10,
    $category_id = 0,
    $status_id = 0,
    $orderBy = 'latest'
  ) {
    $key = 'royalty_' . md5($limit . (is_array($category_id) ? implode('_', $category_id) : $category_id) . (is_array($status_id) ? implode('_', $status_id) : $status_id) . $orderBy);

    if (! Cache::has($key)) {

      $query = $this->royalty->query();


      if ($category_id) {
        $query = $query->whereHas('category', function ($q) use ($category_id) {
          if (is_array($category_id)) {
            $q->whereIn('id', $category_id);
          } else {
            $q->where('id', $category_id);
          }
        });
      }

      switch ($orderBy) {
        case 'latest':
          $query = $query->orderBy('published_at', 'desc');
          break;
        case 'oldest':
          $query = $query->latest();
          break;
      }

      Cache::put($key, $query->limit($limit)->get(), 3600);
    }

    return Cache::get($key);
  }
}
