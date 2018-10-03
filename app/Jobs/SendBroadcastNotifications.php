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
        $format = 'Y-m-d H:i';
        $now = new Carbon();
        $sermon = Sermon::orderBy('id', 'desc')->first();
        $broadcasts = Broadcast::where('enabled', 1)->get();

        foreach ($broadcasts as $broadcast) {

            $opensAt = Carbon::createFromFormat('Y-m-d H:i:s', $broadcast->starts_at)
                ->subMinutes(Broadcast::MINUTES_BEFORE_START);

            $startsAt = Carbon::createFromFormat('Y-m-d H:i:s', $broadcast->starts_at);

            $closesAt = Carbon::createFromFormat('Y-m-d H:i:s', $broadcast->starts_at)
                ->addSeconds($sermon->duration)
                ->addMinutes(Broadcast::MINUTES_AFTER_END)
                ->second(0);

            Log::debug($closesAt->toDateTimeString());

            if ($opensAt->format($format) == $now->format($format)) {
                // Broadcast chat open
                if (! $broadcast->live) { 
                    $broadcast->sermon = $sermon;
                }

                broadcast(new BroadcastOpen($broadcast->toArray()));

            } else if ($startsAt->format($format) == $now->format($format)) {
                // Broadcast starts now
                if (! $broadcast->live) { 
                    $broadcast->sermon = $sermon;
                }

                broadcast(new BroadcastStarting($broadcast->toArray()));
                
            } else if ($closesAt->isPast()) {
                // Broadcast chat closed
                // Save broadcast so our listener can update
                // the new starts_at timestamp
                $broadcast->save();

                Log::debug("Broadcast {$broadcast->id} has closed. {$closesAt}");

                broadcast(new BroadcastClosed($broadcast->toArray()));
            }
        }
    }
}
