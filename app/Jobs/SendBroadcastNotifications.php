<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Events\BroadcastOpen;
use App\Events\BroadcastStarting;
use App\Events\BroadcastClosed;
use App\Broadcast;
use App\Sermon;
use Carbon\Carbon;
use Log;

class SendBroadcastNotifications
{
    use Dispatchable, Queueable;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $now = Carbon::now();
        $now->second(0);
        $broadcasts = Broadcast::where('enabled', 1)->get();

        $sermon = Sermon::where('publish_on', '<=', $now)
            ->latest('publish_on')
            ->first();

        foreach ($broadcasts as $broadcast) {
            $opensAt = $broadcast->opensAt();
            $startsAt = $broadcast->starts_at;
            // What about if a LIVE broadcast...
            $closesAt = $broadcast->closesAt($sermon->duration);

            if ($now == $opensAt) {
                $broadcast->loadTrailer();

                broadcast(new BroadcastOpen($broadcast->toArray()));

            } else if ($now == $startsAt) {
                if (! $broadcast->live) { 
                    $broadcast->sermon = $sermon;
                }

                broadcast(new BroadcastStarting($broadcast->toArray()));

            } else if ($closesAt->isPast()) {
                // Save broadcast so our listener can update
                // the new starts_at timestamp
                $broadcast->save();

                broadcast(new BroadcastClosed($broadcast->toArray()));

                Log::debug("Broadcast {$broadcast->id} has closed. {$closesAt}");
            }
        }
    }
}
