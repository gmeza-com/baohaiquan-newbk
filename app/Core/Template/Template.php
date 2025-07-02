<?php

namespace App\Core\Template;


use App\Libraries\Breadcrumb;
use App\Libraries\Datatable;

class Template implements TemplateInterface
{
  /**
   * @var string
   */
  protected $themeDefault = 'default';

  /**
   * @var
   */
  protected $themePath;

  /**
   * @var
   */
  protected $theme;

  /**
   * @var
   */
  protected $template;

  /**
   * @var array
   */
  protected $viewData = [];

  /**
   * @var Breadcrumb
   */
  protected $breadcrumb;

  /**
   * @var Datatable
   */
  protected $datatable;

  /**
   * Template constructor.
   *
   * @param Breadcrumb $breadcrumb
   * @param Datatable $datatable
   */
  public function __construct(Breadcrumb $breadcrumb, Datatable $datatable)
  {
    $this->themeDefault = config('cnv.theme_default');
    $this->setTheme($this->themeDefault);

    $this->breadcrumb = $breadcrumb;
    $this->datatable = $datatable;
  }

  /**
   * @param $key
   * @param $value
   */
  public function setData($key, $value)
  {
    $this->viewData[$key] = $value;
  }

  /**
   * @param $name
   * @throws \Exception
   */
  public function setTheme($name)
  {
    $this->theme = $name;
    $this->themePath = config('view.theme') . '/' . $this->theme;
    if (!file_exists($this->themePath)) {
      throw new \Exception('Theme is not exists, please contact cnv.vn');
    }

    view()->addNamespace('theme', $this->themePath . '/templates');
  }

  /**
   * @param $templateName
   */
  public function setTemplate($templateName)
  {
    $this->template = $templateName;
  }

  public function setTemplateFrontend($templateName, $module)
  {
    if (view()->exists('theme::' . $module . '.' . $templateName)) {
      $this->template = 'theme::' . $module . '.' . $templateName;
    } else {
      $this->template = $module . '::web.' . $templateName;
    }
  }


  /**
   * @return mixed
   */
  public function getThemeInfo()
  {
    return require_once($this->themePath . '/' . 'index.php');
  }

  /**
   * @return Breadcrumb
   */
  public function breadcrumb()
  {
    return $this->breadcrumb;
  }

  /**
   * @return Datatable
   */
  public function datatable()
  {
    return $this->datatable;
  }

  /**
   * Ready to render
   */
  public function ready()
  {
    $this->setData('theme_path', $this->themePath);
    $this->setData('theme_url', '/themes/' . $this->theme);
    $this->setData('theme_info', $this->getThemeInfo());

    $this->setData('breadcrumb', $this->breadcrumb->getItems());
    $this->setData('datatable', $this->datatable->getItems());
  }

  /**
   * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
   */
  public function render()
  {
    $this->ready();
    return view($this->template, $this->viewData);
  }
}
