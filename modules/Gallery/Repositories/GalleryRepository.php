<?php

namespace Modules\Gallery\Repositories;

use Carbon\Carbon;
use Modules\Gallery\Models\Gallery;
use Modules\Gallery\Models\GalleryCategory;
use Modules\Gallery\Models\GalleryLanguage;
use Illuminate\Support\Facades\Cache;

class GalleryRepository
{
  protected $gallery;
  protected $galleryLanguage;

  public function __construct(Gallery $gallery, GalleryLanguage $galleryLanguage)
  {
    $this->gallery = $gallery;
    $this->galleryLanguage = $galleryLanguage;
  }

  public function getViaSlug($slug, $onlyPublished = false, $type = false)
  {
    $gallery = $this->galleryLanguage->where([
      ['slug', '=', $slug]
    ])->with(['gallery.seo']);

    if ($onlyPublished || $type) {
      $gallery->whereHas('gallery', function ($query) use ($onlyPublished, $type) {
        if ($onlyPublished) {
          $query->where('published', true)->whereDate('published_at', '<=', Carbon::now());
        }
        if ($type) {
          $query->where('type', $type);
        }
      });
    }

    $gallery = $gallery->first();
    if (!$gallery) {
      abort(404);
    }

    session(['lang' => $gallery->locale]);

    return $gallery;
  }

  public function getViaId($id)
  {
    return $this->gallery->where('id', $id)->firstOrFail();
  }

  public function search($keyword, $onlyPublished = false, $type = false, $limit = 10)
  {
    $keyword = '%' . $keyword . '%';
    $gallery = $this->galleryLanguage
      ->orWhere([
        ['slug', 'like', $keyword],
        ['name', 'like', $keyword],
      ])->with('gallery.languages');

    if ($onlyPublished || $type) {
      $gallery->whereHas('gallery', function ($query) use ($onlyPublished, $type) {
        if ($onlyPublished) {
          $query->where('published', true)->whereDate('published_at', '<=', Carbon::now());
        }
        if ($type) {
          $query->where('type', $type);
        }
      });
    }

    return $gallery->limit($limit)->get();
  }

  public function getList($onlyPublished = false, $type = false, $paginate = 12)
  {
    $gallery = $this->gallery;

    if ($onlyPublished) {
      $gallery = $gallery->where('published', true)->whereDate('published_at', '<=', Carbon::now());
    }
    if ($type) {
      $gallery = $gallery->where('type', $type);
    }

    $language = session('lang');

    $gallery = $gallery->whereHas('languages', function ($q) use ($language) {
      $q->where('locale', $language);
    });

    $gallery = $gallery->orderBy('featured')->latest();
    return $paginate ? $gallery->paginate($paginate) : $gallery->get();
  }

  public function getGalleryViaCategoires(GalleryCategory $category, $perPage = 10, $onlyPublished = false)
  {
    $query = $category->gallery();
    $query->with('view');

    if ($onlyPublished) {
      $query = $query->where('published', true)->whereDate('published_at', '<=', Carbon::now());
    }
    $query = $query->orderBy('featured');
    $query = $query->latest();

    return $perPage ? $query->paginate($perPage) : $query->get();
  }

  public function getGallery(
    $limit = 10,
    $category_id = 0,
    $onlyShowPublished = true,
    $orderBy = 'latest',
    $locale = null
  ) {
    $key = 'gallery_' . md5($limit . (is_array($category_id) ? implode('_', $category_id) : $category_id) . $onlyShowPublished . $orderBy . $locale);

    if (! Cache::has($key)) {
      $locale = $locale ? $locale : session('lang');
      $query = $this->gallery->query();

      if ($onlyShowPublished) {
        $query = $query->where('published', true)
          ->whereDate('published_at', '<=', Carbon::now());
      }

      if ($category_id) {
        $query = $query->whereHas('categories', function ($q) use ($category_id) {
          if (is_array($category_id)) {
            $q->whereIn('id', $category_id);
          } else {
            $q->where('id', $category_id);
          }
        });
      } else {
        $query = $query->with('categories.languages');
      }

      $query = $query->whereHas('languages', function ($q) use ($locale) {
        return $q->where('locale', $locale);
      });

      switch ($orderBy) {
        case 'latest':
          $query = $query->orderBy('published_at', 'desc');
          break;
        case 'oldest':
          $query = $query->latest();
          break;
        case 'popular':
          $query = $query->join('views', function ($join) {
            $join->on('gallery.id', '=', 'views.subject_id')
              ->where('subject_type', get_class($this->gallery));
          })
            ->select('gallery.*', 'views.count AS views')
            ->orderBy('views', 'desc');
          break;
        case 'featured':
          $query = $query->orderBy('featured')->latest();
          break;
      }

      Cache::put($key, $query->limit($limit)->get(), 3600);
    }

    return Cache::get($key);
  }
}
