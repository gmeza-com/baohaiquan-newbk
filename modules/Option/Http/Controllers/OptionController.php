<?php
namespace Modules\Option\Http\Controllers;

use App\Core\Template\TemplateInterface;
use App\Http\Controllers\AdminController;
use App\Libraries\APIStoreService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Modules\Option\Models\Option;

class OptionController extends AdminController
{
    public function index(Request $request)
    {
        if(! $request->user()->is_super_admin) {
            abort(404);
        }

        $this->tpl->setData('title', trans('option::language.all_option'));
        $this->tpl->setTemplate('option::index');

        $this->tpl->setData('options', Option::all());

        // breadcrumb
        $this->tpl->breadcrumb()->add('/' . admin_path(), trans('language.dashboard'));
        $this->tpl->breadcrumb()->add(admin_url('option/general'), trans('option::language.all_option'));

        return $this->tpl->render();
    }

    public function save(Request $request)
    {
        $data = $request->except(['_token']);

        foreach ($data as $name => $value)
        {
            update_option($name, $value);
        }

        return response()->json([
            'status' => 200,
            'message' => trans('language.update_success')
        ]);
    }

    public function general()
    {
        $this->tpl->setData('title', trans('option::language.general'));
        $this->tpl->setTemplate('option::general');

        // breadcrumb
        $this->tpl->breadcrumb()->add('/' . admin_path(), trans('language.dashboard'));
        $this->tpl->breadcrumb()->add(admin_url('option/general'), trans('option::language.general'));

        return $this->tpl->render();
    }

    public function system()
    {
        $this->tpl->setData('title', trans('option::language.system'));
        $this->tpl->setTemplate('option::system');

        // breadcrumb
        $this->tpl->breadcrumb()->add('/' . admin_path(), trans('language.dashboard'));
        $this->tpl->breadcrumb()->add(admin_url('option/system'), trans('option::language.system'));

        return $this->tpl->render();
    }

    public function update(Request $request)
    {
        if($request->method() == 'POST') {
            return $this->doUpdate($request);
        }

        $this->tpl->setData('title', trans('option::language.update'));
        $this->tpl->setTemplate('option::update');

        // breadcrumb
        $this->tpl->breadcrumb()->add('/' . admin_path(), trans('language.dashboard'));
        $this->tpl->breadcrumb()->add(admin_url('option/system'), trans('option::language.update'));

        if (! Storage::exists('check_for_update_for_core.db'))
        {
            $updateLogs = [];
        } else {
            $updateLogs = unserialize(Storage::get('check_for_update_for_core.db'));

            if ($request->has('version')) {
                $first = collect($updateLogs)->filter(function ($item) use ($request) {
                    return floatval($item['version']) == floatval($request->get('version'));
                })->first();
            } else {
                $first = collect($updateLogs)->first();
            }
            $this->tpl->setData('first', $first);
        }
        $this->tpl->setData('logs', $updateLogs);

        return $this->tpl->render();
    }

    public function doUpdate(Request $request)
    {
        $secretKey = $request->input('secret_key');
        $api = app()->make(APIStoreService::class);

        if($api->getLinkDownloads($secretKey)) {
            return response()->json([
                'status' => 200,
                'message' => trans('option::language.update_successfully')
            ]);
        } else {
            return response()->json([
                'status' => 500,
                'message' => trans('option::language.update_fail')
            ]);
        }
    }
}