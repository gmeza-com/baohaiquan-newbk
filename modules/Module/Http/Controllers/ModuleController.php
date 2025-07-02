<?php
namespace Modules\Module\Http\Controllers;

use App\Core\Template\TemplateInterface;
use App\Events\ActivatedModule;
use App\Events\DeactivatedModule;
use App\Http\Controllers\AdminController;
use App\Libraries\APIStoreService;
use Illuminate\Http\Request;
use Modules\Module\Repositories\ModulesRepository;

class ModuleController extends AdminController
{
    protected $modules;

    public function __construct(TemplateInterface $template, ModulesRepository $modulesRepository)
    {
        parent::__construct($template);
        $this->modules = $modulesRepository;
    }

    public function index()
    {
        $this->tpl->setData('title', trans('module::language.manager'));
        $this->tpl->setTemplate('module::index');

        $this->tpl->breadcrumb()->add('/' . admin_path(), trans('language.dashboard'));
        $this->tpl->breadcrumb()->add('admin.module.index', trans('module::language.manager'));

        $this->tpl->setData('modules', $this->modules->getModules());

        return $this->tpl->render();
    }

    public function activate(Request $request, $slug)
    {
        if (! $request->ajax()) {
            return;
        }
        $module = $this->modules->getModule($slug);

        if(! $module) {
            return response()->json([
                'status' => 500,
                'message' => trans('module::language.module_does_not_exist')
            ]);
        }

        event(new ActivatedModule($module));

        return response()->json([
            'status' => 200,
            'message' => trans('module::language.activated_success')
        ]);
    }

    public function deactivate(Request $request, $slug)
    {
        if (! $request->ajax()) {
            return;
        }

        if($request->input('license') !== env('APP_LICENSE')) {
            return response()->json([
                'status' => 500,
                'message' => 'Security key does not matches !'
            ]);
        }

        $module = $this->modules->getModule($slug);

        if(! $module) {
            return response()->json([
                'status' => 500,
                'message' => trans('module::language.module_does_not_exist')
            ]);
        }

        event(new DeactivatedModule($module));

        return response()->json([
            'status' => 200,
            'message' => trans('module::language.deactivated_success')
        ]);

    }

    public function destroy(Request $request, $slug)
    {
        if (! $request->ajax()) {
            return;
        }

        if($request->input('license') !== env('APP_LICENSE')) {
            return response()->json([
                'status' => 500,
                'message' => 'Security key does not matches !'
            ]);
        }

        $module = $this->modules->getModule($slug);

        if(! $module) {
            return response()->json([
                'status' => 500,
                'message' => trans('module::language.module_does_not_exist')
            ]);
        }

        if($module->status) {
            return response()->json([
                'status' => 500,
                'message' => trans('module::language.could_not_remove_activated_module')
            ]);
        }

        app('helper')->load('files');
        unlinkr($module->path);
        @rmdir($module->path);

        return response()->json([
            'status' => 200,
            'message' => trans('module::language.destroy_success')
        ]);
    }

    /**
     * Chợ ứng dụng
     *
     */
    public function market(Request $request)
    {
        $this->tpl->setData('title', trans('module::language.market'));
        $this->tpl->setTemplate('module::market');

        $this->tpl->breadcrumb()->add('/' . admin_path(), trans('language.dashboard'));
        $this->tpl->breadcrumb()->add('admin.module.index', trans('module::language.manager'));
        $this->tpl->breadcrumb()->add('admin.module.market', trans('module::language.market'));

        $page = $request->has('page') ? $request->get('page') : 1;
        $api = app()->make(APIStoreService::class);
        $store = $api->getAllModules($page);

        $this->tpl->setData('modules', $store);
        $this->tpl->setData('page', $page);

        return $this->tpl->render();
    }

    public function install(Request $request)
    {
        $api = app()->make(APIStoreService::class);

        if($request->ajax()) {
            return $this->installPremiumModule($request, $api);
        }

        $slug = $request->get('slug');
        $price = intval($request->get('price'));

        if($price === 0 && $slug) {
            if($api->doInstallFreeModule($request->get('slug'))) {
                return redirect(admin_route('module.index'));
            }

            return back();
        }


        $this->tpl->setData('title', trans('module::language.install_title'));
        $this->tpl->breadcrumb()->add('admin.module.index', trans('module::language.manager'));
        $this->tpl->setTemplate('module::install');

        return $this->tpl->render();
    }

    protected function installPremiumModule(Request $request, APIStoreService $api)
    {
        if($api->doInstallPremiumModule($request->get('secret_key'))) {
            return response()->json([
                'status' => 200,
                'message' => trans('language.update_success'),
                'redirect_url' => admin_route('module.index')
            ]);
        } else {
            return response()->json([
                'status' => 500,
                'message' => trans('language.update_fail')
            ]);
        }
    }
}