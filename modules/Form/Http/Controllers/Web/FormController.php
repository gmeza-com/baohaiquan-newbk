<?php

namespace Modules\Form\Http\Controllers\Web;

use App\Http\Controllers\WebController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Modules\Form\Mails\FormMail;
use Modules\Form\Models\Form;
use Modules\Form\Models\FormData;

class FormController extends WebController
{
    public function index($slug)
    {
        $form = Form::whereSlug($slug)->firstOrFail();
        $this->tpl->setData('form', $form);
        $this->tpl->setTemplateFrontend('index', 'form');

        return $this->tpl->render();
    }

    public function store(Request $request, $slug)
    {
        if(!$request->ajax()) {
            return;
        }
        $form = Form::whereSlug($slug)->firstOrFail();
        $data = $request->except(['_token']);
        $formData = new FormData([
            'data' => $data
        ]);
        if ($form->formDatas()->save($formData)) {
            Mail::send(new FormMail($form, $formData));

            return response()->json([
                'status' => 200,
                'message' => trans('language.update_success')
            ]);
        }
        return response()->json([
            'status' => 500,
            'message' => trans('language.update_fail')
        ]);
    }
}