<?php

namespace Modules\Gallery\Http\Controllers\Web;

use App\Core\Template\TemplateInterface;
use App\Http\Controllers\WebController;
use Illuminate\Http\Request;
use Modules\Gallery\Repositories\GalleryRepository;

class GalleryController extends WebController
{
    protected $repository;

    public function __construct(TemplateInterface $template, GalleryRepository $galleryRepository)
    {
        parent::__construct($template);
        $this->repository = $galleryRepository;
    }

    public function show($slug)
    {
        $gallery = $this->repository->getViaSlug($slug, true);

        if (!$gallery) {
            abort(404);
        }
        $gallery->gallery->counting();

        $this->tpl->setTemplateFrontend($gallery->gallery->type, 'gallery');

        if (config('cnv.seo_plugin')) {
            $this->tpl->setData('title', $gallery->gallery->seo->language('title'));
            $this->tpl->setData('description', $gallery->gallery->seo->language('description'));
        } else {
            $this->tpl->setData('title', $gallery->title);
        }

        $this->tpl->setData('gallery', $gallery);

        return $this->tpl->render();
    }

    public function shortlink(Request $request)
    {
        $gallery = $this->repository->getViaId($request->get('id'));
        return redirect()->route('gallery.show', $gallery->language('slug'));
    }

    public function album()
    {
        $gallery = $this->repository->getList(true, 'album');

        if (!$gallery->count()) {
            abort(404);
        }

        $this->tpl->setTemplateFrontend('list_album', 'gallery');

        $this->tpl->setData('title', 'Album #'. (request('page') ?: 1));

        $this->tpl->setData('gallery', $gallery);

        return $this->tpl->render();
    }

    public function video()
    {
        $gallery = $this->repository->getList(true, 'video');

        if (!$gallery->count()) {
            abort(404);
        }

        $this->tpl->setTemplateFrontend('list_video', 'gallery');

        $this->tpl->setData('title', 'Video #'. (request('page') ?: 1));

        $this->tpl->setData('gallery', $gallery);

        return $this->tpl->render();
    }
}