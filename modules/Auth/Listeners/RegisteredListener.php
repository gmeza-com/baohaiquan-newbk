<?php

namespace Modules\Auth\Listeners;

use Modules\Auth\Events\Registered;
use Modules\Auth\Notifications\VerifyAccount;

class RegisteredListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  Registered  $event
     * @return void
     */
    public function handle(Registered $event)
    {
        // send welcome & activated email
        $event->user->notify(new VerifyAccount($event->user));
    }
}
