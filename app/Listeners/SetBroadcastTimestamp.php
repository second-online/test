<?php

namespace App\Listeners;

use App\Events\BroadcastSaving;
use Carbon\Carbon;

class SetBroadcastTimestamp
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
     * @param  \App\Events\BroadcastSaving  $event
     * @return \App\Events\BroadcastSaving 
     */
    public function handle(BroadcastSaving $event)
    {
        $broadcast = $event->broadcast;
        $timestamp = $this->getBroadcastTimestamp($broadcast->day, $broadcast->time);
        $event->broadcast->timestamp = $timestamp;

        return $event;
    }

    /**
     * Generate timestamp for the next gathering.
     *
     * @param  string  $day
     * @param  string  $time
     * @return string
     */
    private function getBroadcastTimestamp($day, $time)
    {
        $format = 'l H:i:s';
        $time = $day . ' ' . $time;
        $timezone = 'America/Chicago';
        $date = Carbon::createFromFormat($format, $time, $timezone);

        $clonedDate = clone $date;

        if ($clonedDate->addHour()->isPast()) {
            $date->addWeek();
        }
        
        return $date->timezone('utc')->toDateTimeString();
    }
}
