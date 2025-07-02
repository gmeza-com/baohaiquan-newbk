<?php

namespace Modules\Gallery\Http\Controllers\Web;

use App\Core\Template\TemplateInterface;
use App\Http\Controllers\WebController;
use Illuminate\Http\Request;
use Modules\Gallery\Repositories\GalleryCategoryRepository;
use Modules\Gallery\Repositories\GalleryRepository;

class CategoryController extends WebController
{
    protected $categoryRepository;

    public function __construct(TemplateInterface $template, GalleryCategoryRepository $galleryCategoryRepository)
    {
        parent::__construct($template);
        $this->categoryRepository = $galleryCategoryRepository;
    }

    public function show(GalleryRepository $galleryRepository, $slug)
    {
        $category = $this->categoryRepository->getViaSlug($slug, true);

        if (!$category) {
            abort(404);
        }
        $this->tpl->setTemplateFrontend('category', 'gallery');

        if (config('cnv.seo_plugin')) {
            $this->tpl->setData('title', $category->category->seo->language('title'));
            $this->tpl->setData('description', $category->category->seo->language('description'));
        } else {
            $this->tpl->setData('title', $category->title);
        }
        $gallery = $galleryRepository->getGalleryViaCategoires($category->category, 12, true);
        $this->tpl->setData('gallery', $gallery);
        $this->tpl->setData('category', $category);
        return $this->tpl->render();
    }

    public function shortlink(Request $request)
    {
        $category = $this->categoryRepository->getViaId($request->get('id'));
        return redirect()->route('gallery.category.show', $category->language('slug'));
    }
}