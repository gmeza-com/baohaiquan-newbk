<?php

namespace App\Http\Controllers;

class HomeController extends WebController
{
  public function index()
  {
    $this->tpl->setTemplate('theme::home');

    $viewCount = (int) get_option('site_view', 3932436) ?: 3932436;

    update_option('site_view', $viewCount + 1);

    return $this->tpl->render();
  }
}
