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
        $now = new Carbon();
        $now->second(0);
        
        // Copy $now and add minutes so we can check if
        // now + MINUTES_BEFORE_START == publish_on/start_at time
        $startTime = $now->copy()->addMinutes(Broadcast::MINUTES_BEFORE_START);

        $sermon = Sermon::where('publish_on', '<=', $startTime)
            ->latest('publish_on')
            ->first();

        $broadcasts = Broadcast::where('starts_at', '<=', $startTime)
            ->where('enabled', 1)
            ->oldest('starts_at')
            ->get();

        foreach ($broadcasts as $broadcast) {
            $durationInSeconds = $broadcast->live ? Broadcast::LIVE_BROADCAST_DURATION : $sermon->duration;
            $opensAt = $broadcast->opensAt();
            $startsAt = $broadcast->starts_at;
            $closesAt = $broadcast->closesAt($durationInSeconds);

            if ($now == $opensAt) {
                $broadcast->trailer = $broadcast->loadTrailer();

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
            }
        }
    }
}
