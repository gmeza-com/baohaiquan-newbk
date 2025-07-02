<?php

namespace Modules\News\Repositories;

use Carbon\Carbon;
use Modules\News\Models\PostCategory;
use Modules\News\Models\PostCategoryLanguage;

class PostCategoryRepository
{
    protected $category;
    protected $categoryLanguage;

    public function __construct(PostCategory $category, PostCategoryLanguage $postCategoryLanguage)
    {
        $this->category = $category;
        $this->categoryLanguage = $postCategoryLanguage;
    }

    public function query()
    {
        return $this->category->query();
    }

    public function all()
    {
        return $this->category->all();
    }

    public function getViaSlug($slug, $onlyPublished = false)
    {
        $key = 'post.get_category_by_slug_' . md5($slug);

        if(! \Cache::has($key)) {
            $category = $this->categoryLanguage->where([
                ['slug', '=', $slug]
            ])->with('category.seo');

            if($onlyPublished) {
                $category = $category->whereHas('category', function($query) {
                    $query->where('published', true);
                });
            }
            $category = $category->firstOrFail();

            session(['lang' => $category->locale]);

            \Cache::put($key, $category, 3600);
        }
        return \Cache::get($key);
    }

    public function getAllParentsCategories($parentId)
    {
        $category = [];
        if ($parentId) {
            $cat = $this->getViaId($parentId);
            if($cat->parent_id !== 0) {
                $category = array_merge($category, $this->getAllParentsCategories($cat->parent_id));
            }
            $category[] = $cat;
        }
        return $category;
    }

    public function getViaId($id)
    {
        $key = 'get_category_by_id_' . $id;

        if(! \Cache::has($key)){
            $category = $this->category->where('id', $id)->firstOrFail();
            \Cache::put($key, $category, 3600);
        }

        return \Cache::get($key);
    }

    public function search($keyword, $limit = 10)
    {
        $keyword = '%' . $keyword . '%';
        $categories = $this->categoryLanguage
            ->orWhere([
                ['slug', 'like', $keyword],
                ['name', 'like', $keyword],
            ])->with('category.languages');

        return $categories->limit($limit)->get();
    }

    public function getCategories($parent_id = 0, $onlyShowPublished = true, $locale = null)
    {
        $query = $this->category->withCount('children')->where('parent_id', $parent_id);
        $locale = $locale ? $locale : session('lang');

        if($onlyShowPublished) {
            $query = $query->where('published',  true);
        }

        $query = $query->whereHas('languages', function ($q) use ($locale) {
            $q->where('locale', $locale);
        });

        return $query->get();
    }

    public function getViaParentId($id)
    {
        return $this->category->where('parent_id', $id)->firstOrFail();
    }

}
