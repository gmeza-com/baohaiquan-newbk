<?php

namespace Modules\News\Http\Controllers\Web;

use App\Core\Template\TemplateInterface;
use App\Http\Controllers\WebController;
use Illuminate\Http\Request;
use Modules\News\Repositories\PostCategoryRepository;
use Modules\News\Repositories\PostRepository;

class CategoryController extends WebController
{
    protected $categoryRepository;

    public function __construct(TemplateInterface $template, PostCategoryRepository $postCategoryRepository)
    {
        parent::__construct($template);
        $this->categoryRepository = $postCategoryRepository;
    }

    public function show(PostRepository $postRepository, $slug)
    {
        $category = $this->categoryRepository->getViaSlug($slug, true);

        if (!$category) {
            abort(404);
        }
        $this->tpl->setTemplateFrontend('category.index', 'news');

        if (config('cnv.seo_plugin')) {
            $this->tpl->setData('title', $category->category->seo->language('title'));
            $this->tpl->setData('description', $category->category->seo->language('description'));
        } else {
            $this->tpl->setData('title', $category->title);
        }
        $posts = $postRepository->getPostViaCategoires($category->category, 10, true, 'published_at');

        if($category) {
            foreach ($this->categoryRepository->getAllParentsCategories($category->category->parent_id) as $cat) {
                $this->tpl->breadcrumb()->add($cat->language('link'), $cat->language('name'));
            }
            $this->tpl->breadcrumb()->add($category->link, $category->name);
        }

        $this->tpl->breadcrumb()->add($category->link, $category->name);
        $this->tpl->setData('posts', $posts);
        $this->tpl->setData('category', $category);

        return $this->tpl->render();
    }

    public function shortlink(Request $request)
    {
        $category = $this->categoryRepository->getViaId($request->get('id'));
        return redirect()->route('post.category.show', $category->language('slug'));
    }
}
