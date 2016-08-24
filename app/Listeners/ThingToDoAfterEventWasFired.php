<?php

namespace App\Listeners;

use App\Events\ServerUpdated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ThingToDoAfterEventWasFired
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
     * @param  ServerUpdated  $event
     * @return void
     */
    public function handle(ServerUpdated $event)
    {
        //
    }
}
