<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Module\Repositories\ModuleRepository;

class ActivatedModule
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var ModuleRepository
     */
    public $module;

    /**
     * Create a new event instance.
     *
     * @param $module
     * @throws \Exception
     */
    public function __construct($module)
    {
        $this->module = $module;

        if ($this->module->status) {
            throw new \Exception('Module was activated');
        }
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        //return new PrivateChannel('channel-name');
    }
}
