<?php

namespace App\Core;

use Illuminate\Foundation\Application as BaseApplication;

class Application extends BaseApplication
{
  public function publicPath($path = '')
  {
    return $this->basePath . DIRECTORY_SEPARATOR . '..' . ($path ? DIRECTORY_SEPARATOR . $path : '');
  }
}
