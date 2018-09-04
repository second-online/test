<?php

namespace App\Observers;

use App\Broadcast;

class BroadcastObserver
{
    /**
     * Handle the broadcast "created" event.
     *
     * @param  \App\Broadcast  $broadcast
     * @return void
     */
    public function created(Broadcast $broadcast)
    {
        //
    }

    /**
     * Handle the broadcast "updated" event.
     *
     * @param  \App\Broadcast  $broadcast
     * @return void
     */
    public function updated(Broadcast $broadcast)
    {
        //
    }

    /**
     * Handle the broadcast "deleted" event.
     *
     * @param  \App\Broadcast  $broadcast
     * @return void
     */
    public function deleted(Broadcast $broadcast)
    {
        //
    }

    /**
     * Handle the broadcast "restored" event.
     *
     * @param  \App\Broadcast  $broadcast
     * @return void
     */
    public function restored(Broadcast $broadcast)
    {
        //
    }

    /**
     * Handle the broadcast "force deleted" event.
     *
     * @param  \App\Broadcast  $broadcast
     * @return void
     */
    public function forceDeleted(Broadcast $broadcast)
    {
        //
    }
}
