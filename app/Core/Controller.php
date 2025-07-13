<?php

namespace App\Core;

use App\Core\Template\TemplateInterface;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
  use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

  /**
   * @var \App\Core\Template\Template
   */
  public $tpl;
  /**
   * @var array
   */
  public $languages;

  public $currentLanguage;

  public function __construct(TemplateInterface $template)
  {
    $this->tpl = $template;
  }
}
