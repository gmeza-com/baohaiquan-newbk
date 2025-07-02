<?php

namespace App\Http\Controllers;

use App\Core\Template\TemplateInterface;

class WebController extends Controller
{

    /**
     * Setup theme
     *
     * @param TemplateInterface $template
     */
    public function __construct(TemplateInterface $template)
    {
        parent::__construct($template);

        $viewComposer = app()->make(\App\Libraries\ViewComposer::class);
        $viewComposer->web();
        $viewComposer->apply();
    }
}
