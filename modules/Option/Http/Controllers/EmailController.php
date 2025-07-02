<?php
namespace Modules\Option\Http\Controllers;

use App\Http\Controllers\AdminController;
use Illuminate\Http\Request;

class EmailController extends AdminController
{
    public function email(Request $request)
    {
        $this->tpl->setData('title', trans('option::language.email_templates'));
        $this->tpl->setTemplate('option::email.list');

        // breadcrumb
        $this->tpl->breadcrumb()->add('/' . admin_path(), trans('language.dashboard'));
        $this->tpl->breadcrumb()->add(admin_url('option/system/email'), trans('option::language.email_templates'));

        // emails
        $this->tpl->setData('moduleTemplates', get_array_option('email_templates'));

        return $this->tpl->render();
    }

    public function editEmailTemplate(Request $request, $module, $email)
    {
        if($request->method() == 'POST') {
            return $this->saveEmailTemplate($request, $module, $email);
        }

        $emailTemplates = get_array_option('email_templates');

        if((! array_key_exists($module, $emailTemplates) && !array_key_exists($email, $emailTemplates[$module])) || file_exists($emailTemplates[$module][$email]['path'])) {
            abort(404);
        }

        $this->tpl->setData('title', trans('option::language.email_edit_template'));
        $this->tpl->setData('email_slug', $module . '/' . $email);
        $this->tpl->setData('email', $emailTemplates[$module][$email]);
        $this->tpl->setData('email_content', file_get_contents(base_path($emailTemplates[$module][$email]['path'])));
        $this->tpl->setTemplate('option::email.edit');


        // breadcrumb
        $this->tpl->breadcrumb()->add('/' . admin_path(), trans('language.dashboard'));
        $this->tpl->breadcrumb()->add(admin_url('option/email'), trans('option::language.email_templates'));
        $this->tpl->breadcrumb()->add('#', trans('option::language.email_edit_template'));

        return $this->tpl->render();
    }

    private function saveEmailTemplate($request, $module, $email)
    {
        $emailTemplates = get_array_option('email_templates');

        if ((!array_key_exists($module, $emailTemplates) && !array_key_exists($email, $emailTemplates[$module])) || file_exists($emailTemplates[$module][$email]['path'])) {
            abort(404);
        }

        $filePath = base_path($emailTemplates[$module][$email]['path']);
        if (!file_exists($filePath)) {
            return response()->json([
                'status' => 500,
                'messenge' => trans('language.save_fail')
            ]);
        }

        $content = $request->input('email_content');
        file_put_contents($filePath, $content);

        return response()->json([
            'status' => 200,
            'messenge' => trans('language.save_success')
        ]);
    }
}