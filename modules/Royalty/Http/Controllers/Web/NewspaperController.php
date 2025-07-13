<?php

namespace Modules\Gallery\Http\Controllers\Web;

use App\Core\Template\TemplateInterface;
use App\Http\Controllers\WebController;
use Illuminate\Http\Request;
use Modules\Gallery\Repositories\GalleryRepository;
use Modules\Gallery\Models\Gallery;
use Modules\Gallery\Mobile_Detect;

class NewspaperController extends WebController
{
    protected $repository;

    public function __construct(TemplateInterface $template, GalleryRepository $galleryRepository)
    {
        parent::__construct($template);
        $this->repository = $galleryRepository;
    }

    public function index(Request $request)
    {
        $endYear = date('Y');
        $starYear = $endYear - 10;
        $currentYear = $request->get('year') ?: $endYear;
        for ($y = $starYear; $y <= $endYear; $y++) {
            $listYears[] = $y;
        }

        $listNewspaper = Gallery::whereRaw('YEAR(published_at) = ' . $currentYear)->where('published', true)->whereHas('categories', function($query) {
            $query->where('id', 3);
        })->orderBy('published_at','desc')->get();
        $currentNewspaper = $request->get('id') ? Gallery::whereId($request->get('id')) : Gallery::orderBy('published_at','desc');
        $currentNewspaper = $currentNewspaper->where('published', true)->whereHas('categories', function($query) {
            $query->where('id', 3);
        })->first();

        $detect = new Mobile_Detect;
        // Exclude tablets.
        if( $detect->isMobile() || $detect->isTablet() ){
            $this->tpl->setTemplateFrontend('newspaper_mobile', 'gallery');
            $this->tpl->setData('title', 'Báo In Hải Quân');
            $this->tpl->setData('listYears', $listYears);
            $this->tpl->setData('currentYear', $currentYear);
            $this->tpl->setData('listNewspaper', $listNewspaper);
            $this->tpl->setData('currentNewspaper', $currentNewspaper);
            return $this->tpl->render();
        }
        $this->tpl->setTemplateFrontend('newspaper', 'gallery');

        $this->tpl->setData('title', 'Báo In Hải Quân');

        $this->tpl->setData('listYears', $listYears);
        $this->tpl->setData('currentYear', $currentYear);
        $this->tpl->setData('listNewspaper', $listNewspaper);
        $this->tpl->setData('currentNewspaper', $currentNewspaper);

        return $this->tpl->render();
    }


    public function loadNewsPaper(Request $request){
        if($request->ajax()){
            $id_cate = $request->get('id_cate');
            $year = $request->get('year');
            $currentNewspaper = Gallery::whereId($id_cate)->where('published', true)->whereHas('categories', function($query) {
                $query->where('id', 3);
            })->first();
            return $listNewspaperJson = $currentNewspaper->language('content')->sortBy('position')->map(function ($item) {
                        return [
                            'src' => $item['picture'],
                            'thumb' => $item['picture'],
                        ];
                    })->values()->toJson();

        }
    }


    public function index_3d(Request $request)
    {
        $endYear = date('Y');
        $starYear = $endYear - 10;
        $currentYear = $request->get('year') ?: $endYear;
        for ($y = $starYear; $y <= $endYear; $y++) {
            $listYears[] = $y;
        }

        /*$listNewspaper = Gallery::whereRaw('YEAR(published_at) = ' . $currentYear)->where('published', true)->whereHas('categories', function($query) {
            $query->where('id', 3);
        })->orderBy('published_at','desc')->get();*/

        $listNewspaper = Gallery::whereRaw('YEAR(published_at) = ' . $currentYear)->where('published', true)->whereHas('categories', function($query) {
            $query->where('id', 3);
        })->orderBy('published_at','desc')->paginate(8);

        $currentNewspaper = $request->get('id') ? Gallery::whereId($request->get('id')) : Gallery::orderBy('published_at','desc');
        $currentNewspaper = $currentNewspaper->where('published', true)->whereHas('categories', function($query) {
            $query->where('id', 3);
        })->first();

        $detect = new Mobile_Detect;
        // Exclude tablets.
        // if( $detect->isMobile() || $detect->isTablet() ){
        //     $this->tpl->setTemplateFrontend('newspaper_mobile', 'gallery');
        //     $this->tpl->setData('title', 'Báo In Hải Quân');
        //     $this->tpl->setData('listYears', $listYears);
        //     $this->tpl->setData('currentYear', $currentYear);
        //     $this->tpl->setData('listNewspaper', $listNewspaper);
        //     $this->tpl->setData('currentNewspaper', $currentNewspaper);
        //     return $this->tpl->render();
        // }


        $this->tpl->setTemplateFrontend('newspaper_3d', 'gallery');

        $this->tpl->setData('title', 'Báo In Hải Quân');

        $this->tpl->setData('listYears', $listYears);
        $this->tpl->setData('currentYear', $currentYear);
        $this->tpl->setData('listNewspaper', $listNewspaper);
        // Json thumbnail flipbook

        return $this->tpl->render();
    }
}
