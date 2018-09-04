<?php

namespace App\Events;

use App\Broadcast;
use Illuminate\Queue\SerializesModels;

class BroadcastSaving
{
    use SerializesModels;

    /**
     * The broadcast that is saving.
     *
     * @var \App\Broadcast  $broadcast
     */
    public $broadcast;

    /**
     * Create a new event instance.
     *
     * @param  \App\Broadcast  $broadcast
     * @return void
     */
    public function __construct(Broadcast $broadcast)
    {
        $this->broadcast = $broadcast;
    }
}