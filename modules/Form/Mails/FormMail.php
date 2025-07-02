<?php

namespace Modules\Form\Mails;

use Illuminate\Bus\Queueable;
use Illuminate\Http\Request;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Form\Models\Form;
use Modules\Form\Models\FormData;

class FormMail extends Mailable
{
    use Queueable, SerializesModels;

    public $form;
    public $formData;

    /**
     * Create a new message instance.
     *
     * @param Form $form
     * @param FormData $formData
     */
    public function __construct(Form $form, FormData $formData)
    {
        $this->form = $form;
        $this->formData = $formData;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $mailer = $this->view('form::mail')
            ->subject(trans('form::language.subject_mail', ['url' => get_option('site_url')]))
            ->to(get_option('site_email'));
        if(isset($this->formData->data['email']) {
            return $mailer->cc($this->formData->data['email']);
        }

        return $mailer;
    }
}
