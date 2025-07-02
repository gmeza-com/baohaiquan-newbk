<?php

namespace Modules\News\Repositories;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\News\Models\PostCategory;
use Modules\News\Models\PostLanguage;
use Modules\News\Models\Post;
use Plugins\ViewCounter\Models\View;

class PostRepository
{
    protected $posts;
    protected $postLanguage;

    public function __construct(Post $page, PostLanguage $postLanguage)
    {
        $this->posts = $page;
        $this->postLanguage = $postLanguage;
    }

    public function query()
    {
        return $this->posts->query();
    }

    public function getViaSlug($slug, $onlyPublished = false)
    {
        $key = 'post.' . md5($slug);

        if (!\Cache::has($key)) {
            $post = $this->postLanguage->where([
                ['slug', '=', $slug]
            ])->with(['post.seo', 'post.categories', 'post.view']);

            if ($onlyPublished) {
                $post = $post->whereHas('post', function ($query) {
                    $query
                        ->where('published', 3)
                        ->where('status', false)
                        ->where('hide', false)
                        ->where('published_at', '<=', Carbon::now())
                        ->whereRaw('published_at is NOT NULL');
                });
            }

            $post = $post->firstOrFail();
            session(['lang' => $post->locale]);

            \Cache::put($key, $post, 3600);
        }

        return \Cache::get($key);
    }

    public function getViaId($id)
    {
        return $this->posts->where('id', $id)->firstOrFail();
    }

    public function search($keyword, $onlyPublished = false, $limit = 10, $paginate = 0)
    {
        $keyword = '%' . $keyword . '%';
        $posts = $this->postLanguage
            ->where(function ($query) use ($keyword) {
                $query->orWhere('slug', 'like', $keyword);
                $query->orWhere('name', 'like', $keyword);
                $query->orWhere('content', 'like', $keyword);
                $query->orWhere('tags', 'like', $keyword);
            })
            ->with('post');

        if ($onlyPublished) {
            $posts = $posts->whereHas('post', function ($query) {
                $query
                    ->where('published', 3)
                    ->where('hide', false)
                    ->where('status', false)
                    ->where('published_at', '<=', Carbon::now())
                    ->whereRaw('published_at is NOT NULL');
            });
        }
        return $limit > 0  ? $posts->limit($limit)->get() : ($paginate > 0 ? $posts->paginate($paginate) : $posts->get());
    }

    public function getPostViaCategoires(PostCategory $category, $perPage = 10, $onlyPublished = true, $orderBy = 'latest')
    {

        $key = 'post.get_post_via_category.' . md5($category->id . $perPage . $onlyPublished . $orderBy . request('page'));
        if (!\Cache::has($key)) {
            $query = $this->posts->query();
            $query->whereHas('categories', function ($q) use ($category) {
                $categories = $this->getAllPostCategoriesBy($category->id);
                $q->whereIn('id', $categories);
            });
            $query->with('view');

            if ($onlyPublished) {
                $query = $query
                    ->where('published', 3)
                    ->where('hide', false)
                    ->where('published_at', '<=', Carbon::now())
                    ->whereRaw('published_at is NOT NULL');
            }
            if ($orderBy == 'featured') {
                $query = $query
                    ->orderBy('featured', 'desc');
            }
            $query = $query->orderBy('published_at', 'desc');
            $query = $query->where('status',false);
            $posts = $perPage ? $query->paginate($perPage) : $query->get();
            \Cache::put($key, $posts, 3600);
        }
        return \Cache::get($key);
    }

    public function getPosts(
        $limit = 10,
        $category_id = 0,
        $onlyShowPublished = true,
        $orderBy = 'latest',
        $locale = null
    ) {
        $locale = $locale ? $locale : session('lang');
        $key = 'get_lists_posts_' . md5($limit . (is_array($category_id) ? implode('.', $category_id) : $category_id) . $onlyShowPublished . $orderBy . $locale);
        if (!\Cache::has($key)) {
            $query = $this->posts->query();

            if ($onlyShowPublished) {
                $query = $query->where('published', 3)
                    ->where('hide', false)
                    ->where('published_at', '<=', Carbon::now())
                    ->whereRaw('published_at is NOT NULL');
            }
            if ($category_id) {
                $query = $query->whereHas('categories', function ($q) use ($category_id) {
                    $categories = $this->getAllPostCategoriesBy($category_id);
                    $q->whereIn('id', $categories);
                });
            } else {
                $query = $query->with('categories.languages');
            }

            $query = $query->whereHas('languages', function ($q) use ($locale) {
                return $q->where('locale', $locale);
            });

            switch ($orderBy) {
                case 'latest':
                    $query = $query->latest();
                    break;
                case 'oldest':
                    $query = $query->latest();
                    break;
                case 'popular':
                    $query = $query->join('views', function ($join) {
                        $join->on('posts.id', '=', 'views.subject_id')
                            ->where('subject_type', get_class($this->posts));
                    })
                        ->select('posts.*', 'views.count AS views')
                        ->orderBy('views', 'desc');
                    break;
                case 'featured':
                    $query = $query
                        ->orderBy('featured', 'desc')->latest();
                    break;
                case 'published':
                    $query = $query
                        ->orderBy('published_at', 'desc');
                    break;
            }
            $query = $query->where('status',false);
            $posts = $query->limit($limit)->get();

            \Cache::put($key, $posts, 3660);
        }

        return \Cache::get($key);
    }

    public function getPostsFeatured(
        $limit = 10,
        $category_id = 0,
        $onlyShowPublished = true,
        $orderBy = 'latest',
        $locale = null
    ) {
        $locale = $locale ? $locale : session('lang');
        $key = 'get_lists_posts_' . md5($limit . (is_array($category_id) ? implode('.', $category_id) : $category_id) . $onlyShowPublished . $orderBy . $locale);
        if (!\Cache::has($key)) {
            $query = $this->posts->query();

            if ($onlyShowPublished) {
                $query = $query->where('published', 3)
                    ->where('hide', false)
                    ->where('status', false)
                    ->where('published_at', '<=', Carbon::now())
                    ->whereRaw('published_at is NOT NULL');
            }
            if ($category_id) {
                $query = $query->whereHas('categories', function ($q) use ($category_id) {
                    $categories = $this->getAllPostCategoriesBy($category_id);
                    $q->whereIn('id', $categories);
                });
            } else {
                $query = $query->with('categories.languages');
            }

            $query = $query->whereHas('languages', function ($q) use ($locale) {
                return $q->where('locale', $locale);
            });

            switch ($orderBy) {
                case 'latest':
                    $query = $query->latest();
                    break;
                case 'oldest':
                    $query = $query->latest();
                    break;
                case 'popular':
                    $query = $query->join('views', function ($join) {
                        $join->on('posts.id', '=', 'views.subject_id')
                            ->where('subject_type', get_class($this->posts));
                    })
                        ->select('posts.*', 'views.count AS views')
                        ->orderBy('views', 'desc');
                    break;
                case 'featured':
                    $query = $query
                        ->where('featured', 1)
                        ->where('featured_started_at', '<=', Carbon::now())
                        ->where('featured_ended_at', '>=', Carbon::now())
                        ->latest();
                    break;
                case 'published':
                    $query = $query
                        ->orderBy('published_at', 'desc');
                    break;
            }
            $posts = $query->limit($limit)->get();

            \Cache::put($key, $posts, 3660);
        }

        return \Cache::get($key);
    }

    protected function getAllPostCategoriesBy($category)
    {
        $categoryId = [];
        if (is_array($category)) {
            $categoryId = $category;
        } else {
            $categoryId[] = $category;
        }
        $categories = PostCategory::select('id', 'parent_id')->whereIn('parent_id', $categoryId)->get();
        $categories = $this->getIdPostCategories($categories);
        $data = array_merge($categoryId, $categories);

        return $data;
    }

    protected function getIdPostCategories($categories)
    {
        $data = [];
        foreach ($categories as $category) {
            $data[] = $category->id;
            if (!$category->children->isEmpty()) {
                $data = array_merge($this->getIdPostCategories($category->children), $data);
            }
        }

        return $data;
    }
}
