<?php

namespace App\Listeners;

use App\Events\ActivatedModule;

class ActivatedModuleListener
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
     * @param  ActivatedModule $event
     * @return void
     */
    public function handle(ActivatedModule $event)
    {
        // Change status
        $module = $event->module->path. '/module.php';
        $content = file_get_contents($module);
        $content = str_replace("'status' => 0", "'status' => 1", $content);
        file_put_contents($module, $content);
        session(['queues_update_datatabse' =>  true]);
    }
}
