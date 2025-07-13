<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DeactivatedModule
{
  use Dispatchable, InteractsWithSockets, SerializesModels;

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

    if (!$this->module->status) {
      throw new \Exception('Module was not activated');
    }
  }

  /**
   * Get the channels the event should broadcast on.
   *
   * @return Channel|array
   */
  public function broadcastOn()
  {
    return new PrivateChannel('channel-name');
  }
}
