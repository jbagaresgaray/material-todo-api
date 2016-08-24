<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ServerUpdated extends Event implements ShouldBroadcast
{
    use SerializesModels;

    public $data;
    public $users;
    public $resource;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($data, $users, $resource)
    {
        $this->data = $data;
        $this->users = $users;
        $this->resource = $resource;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return ['server-updates'];
    }
}
