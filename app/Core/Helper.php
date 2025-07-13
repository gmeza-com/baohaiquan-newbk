<?php

namespace App\Core;

class Helper
{
  public function load($helper, $module = null)
  {
    $helpersFile = '/' . $helper . '.php';
    $pathHelper = ($module ? base_path('modules/' . $module . '/Helpers') : app_path('Helpers')) . $helpersFile;
    if (file_exists($pathHelper)) {
      require_once $pathHelper;
    } else {
      throw new \Exception("Helper '{$helper}' is not exists");
    }
  }
}
