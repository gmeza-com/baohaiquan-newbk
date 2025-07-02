<?php

namespace Modules\Contact\Http\Controllers;

use App\Http\Controllers\WebController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Modules\Contact\Mails\ContactMail;

class ContactController extends WebController
{
    public function index()
    {
        $this->tpl->setTemplateFrontend('index', 'contact');
        $this->tpl->setData('title', trans('contact::web.contact'));

        return $this->tpl->render();
    }

    public function send(Request $request)
    {
        Mail::send(new ContactMail($request))   ;

        return [
            'status' => 200,
            'message' => trans('contact::web.send_contact_success')
        ];
    }
}