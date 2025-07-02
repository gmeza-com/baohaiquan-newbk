<?php
namespace Modules\Plugin\Http\Controllers;

use App\Core\Template\TemplateInterface;
use App\Http\Controllers\AdminController;
use Illuminate\Http\Request;
use Modules\Plugin\Repositories\PluginsRepository;

class PluginController extends AdminController
{
    protected $plugins;

    public function __construct(TemplateInterface $template, PluginsRepository $pluginsRepository)
    {
        parent::__construct($template);
        $this->plugins = $pluginsRepository;
    }

    public function index(Request $request)
    {
        if($request->has('plugin')) {
            return $this->settingViewPage($request, $request->get('plugin'));
        }

        $this->tpl->setData('title', trans('plugin::language.manager'));
        $this->tpl->setTemplate('plugin::index');

        $this->tpl->breadcrumb()->add('/' . admin_path(), trans('language.dashboard'));
        $this->tpl->breadcrumb()->add('admin.module.index', trans('plugin::language.manager'));

        $this->tpl->setData('plugins', $this->plugins->getPlugins());

        return $this->tpl->render();
    }

    protected function settingViewPage(Request $request, $plugin)
    {
        $pluginOrigin = $this->plugins->getPlugin($plugin);
        if ($pluginOrigin && isset($pluginOrigin->setting['controller']) && class_exists($pluginOrigin->setting['controller'])) {
            $instance = app()->make($pluginOrigin->setting['controller']);
            if (isset($pluginOrigin->setting['action']) && method_exists($instance, $pluginOrigin->setting['action'])) {
                return $instance->{$pluginOrigin->setting['action']}($request, $pluginOrigin);
            }
        }
        abort(404);
    }
}