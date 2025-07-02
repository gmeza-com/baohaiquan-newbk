<?php

namespace Modules\News\Http\Controllers\Web;

use App\Core\Template\TemplateInterface;
use App\Http\Controllers\WebController;
use Illuminate\Http\Request;
use Modules\News\Repositories\PostRepository;
use Modules\News\Repositories\PostCategoryRepository;

class PostController extends WebController
{
    protected $postRepository;
    protected $categoryRepository;

    public function __construct(TemplateInterface $template, PostRepository $postRepository, PostCategoryRepository $categoryRepository)
    {
        parent::__construct($template);
        $this->postRepository = $postRepository;
        $this->categoryRepository = $categoryRepository;
    }

    public function show($slug)
    {
        $post = $this->postRepository->getViaSlug($slug, true);

        if (!$post) {
            abort(404);
        }

        $post->post->counting();

        $this->tpl->setTemplateFrontend('post.index', 'news');

        if (config('cnv.seo_plugin')) {
            $this->tpl->setData('title', $post->post->seo->language('title'));
            $this->tpl->setData('description', $post->post->seo->language('description'));
        } else {
            $this->tpl->setData('title', $post->title);
        }

        $category = $post->post->categories->first();
        // breadcrumb
        if ($category) {
            foreach ($this->categoryRepository->getAllParentsCategories($category->parent_id) as $cat) {
                $this->tpl->breadcrumb()->add($cat->language('link'), $cat->language('name'));
            }
            $this->tpl->breadcrumb()->add($category->language('link'), $category->language('name'));
        }
        $this->tpl->breadcrumb()->add($post->link, $post->name);

        $this->tpl->setData('post', $post);
        return $this->tpl->render();
    }

    public function test($slug)
    {
        $post = $this->postRepository->getViaSlug($slug, false);

        if (!$post) {
            abort(404);
        }

        $post->post->counting();

        $this->tpl->setTemplateFrontend('post.index_test', 'news');

        if (config('cnv.seo_plugin')) {
            $this->tpl->setData('title', $post->post->seo->language('title'));
            $this->tpl->setData('description', $post->post->seo->language('description'));
        } else {
            $this->tpl->setData('title', $post->title);
        }

        $category = $post->post->categories->first();
        // breadcrumb
        if ($category) {
            foreach ($this->categoryRepository->getAllParentsCategories($category->parent_id) as $cat) {
                $this->tpl->breadcrumb()->add($cat->language('link'), $cat->language('name'));
            }
            $this->tpl->breadcrumb()->add($category->language('link'), $category->language('name'));
        }
        $this->tpl->breadcrumb()->add($post->link, $post->name);

        $this->tpl->setData('post', $post);
        return $this->tpl->render();
    }

    public function shortlink(Request $request)
    {
        $post = $this->postRepository->getViaId($request->get('id'));
        return redirect()->route('post.show', $post->language('slug'));
    }

    public function search(Request $request)
    {
        $this->tpl->setTemplateFrontend('post.search', 'news');

        $this->tpl->setData('title', trans('news::language.search_result'));
        $posts = $this->postRepository->search($request->get('q'), true, 0, 12);
        $this->tpl->setData('posts', $posts);

        $this->tpl->breadcrumb()->add('#', 'Kết quả tìm kiếm');

        return $this->tpl->render();
    }

    public function printer($slug)
    {
        $post = $this->postRepository->getViaSlug($slug, true);

        if (!$post) {
            abort(404);
        }

        $post->post->counting();

        $this->tpl->setTemplateFrontend('post.print', 'news');

        if (config('cnv.seo_plugin')) {
            $this->tpl->setData('title', $post->post->seo->language('title'));
            $this->tpl->setData('description', $post->post->seo->language('description'));
        } else {
            $this->tpl->setData('title', $post->title);
        }

        $category = $post->post->categories->first();
        // breadcrumb
        if ($category) {
            foreach ($this->categoryRepository->getAllParentsCategories($category->parent_id) as $cat) {
                $this->tpl->breadcrumb()->add($cat->language('link'), $cat->language('name'));
            }
            $this->tpl->breadcrumb()->add($category->language('link'), $category->language('name'));
        }
        $this->tpl->breadcrumb()->add($post->link, $post->name);

        $this->tpl->setData('post', $post);
        return $this->tpl->render();
    }
}
