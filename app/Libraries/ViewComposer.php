<?php

namespace App\Libraries;

class ViewComposer
{
  protected $composers = [];

  public function __construct() {}

  public function apply()
  {
    foreach ($this->composers as $composer) {
      view()->composer($composer['view'], $composer['callback']);
    }
  }

  public function register($view, $callback)
  {
    $this->composers[] = [
      'view' => $view,
      'callback' => $callback
    ];
  }

  public function admin()
  {
    $this->register('partial.navbar', function ($view) {
      $view->with('user', auth()->user());
    });

    $this->register('partial.sidebar', function ($view) {
      $view->with('menu_items', cnv_menu('admin-menu'));
    });
  }

  public function web() {}
}
