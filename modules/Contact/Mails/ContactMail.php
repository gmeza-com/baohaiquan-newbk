<?php

namespace Modules\Contact\Mails;

use Illuminate\Bus\Queueable;
use Illuminate\Http\Request;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ContactMail extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $email;
    public $title;
    public $content;

    /**
     * Create a new message instance.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->name = $request->input('name');
        $this->email = $request->input('email');
        $this->title = $request->input('subject') ?: trans('contact::web.no_title_email');
        $this->content = nl2br(strip_tags($request->input('message')));
        $this->subject = trans('contact::web.subject_mail', [
            'name' => $this->name,
            'app' => get_option('site_name')
        ]);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('contact::contact')
            ->to(get_option('site_email'))
            ->replyTo($this->email );
    }
}
