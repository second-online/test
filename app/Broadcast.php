<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Events\BroadcastSaving;
use Carbon\Carbon;

class Broadcast extends Model
{
    /**
     * The number of minutes before start time to open the broadcast.
     *
     * @var int
     */
    const MINUTES_BEFORE_START = 10;

    /**
     * The number of minutes after broadcast has ended to keep chat open.
     *
     * @var int
     */
    const MINUTES_AFTER_END = 10;

    /**
     * The duration in minutes for live broadcast.
     *
     * @var int
     */
    const LIVE_BROADCAST_DURATION = 90;

    /**
     * The broadcast chat is open.
     *
     * @var string
     */
    const BROADCAST_OPEN = 'broadcast_open';

    /**
     * The broadcast is in progress.
     *
     * @var string
     */
    const BROADCAST_IN_PROGRESS = 'broadcast_in_progress';

    /**
     * The broadcast has ended.
     *
     * @var string
     */
    const BROADCAST_ENDED = 'broadcast_ended';

    /**
     * The broadcast chat has closed.
     *
     * @var string
     */
    const BROADCAST_CLOSED = 'broadcast_closed';

    /**
     * The event map for the model.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'saving' => BroadcastSaving::class
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'starts_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'day', 'time', 'enabled', 'created_at', 'updated_at',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'day', 'time', 'live', 'enabled'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'live' => 'boolean',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [];

    /**
     * Get the comments for the broadcast.
     * 
     * @return array  \App\BroadcastComment
     */
    public function comments()
    {
        return $this->hasMany('App\BroadcastComment');
    }

    /**
     * Get the time the broadcast chat opens.
     * 
     * @return Carbon  \Carbon\Carbon
     */
    public function opensAt()
    {
        return $this->starts_at
            ->subMinutes(self::MINUTES_BEFORE_START);
    }

    /**
     * Get the time the broadcast ends.
     * 
     * @param  int     $duration
     * @return Carbon  \Carbon\Carbon
     */
    public function endsAt($duration)
    {
        return $this->starts_at
            ->addSeconds($duration);
    }

    /**
     * Get the time the broadcast chat closes.
     * 
     * @param  int     $duration
     * @return Carbon  \Carbon\Carbon
     */
    public function closesAt($duration)
    {
        return $this->endsAt($duration)
            ->addMinutes(self::MINUTES_AFTER_END)
            ->second(0);
    }

    /**
     * Load the latest trailer.
     * 
     * @return array
     */
    public function loadTrailer()
    {
        return ['link' => 'https://vimeo.com/218845426/1d582e7485'];
    }

    /**
     * Get the sermon for the broadcast.
     * 
     * @return Sermon  \App\Sermon
     */
    public function loadSermon() 
    {
        return Sermon::where('publish_on', '<=', $this->starts_at)
            ->latest('publish_on')
            ->first();
    }

    /**
     * Configure the broadcast by adding trailer, sermon & status.
     * 
     * @return void
     */
    public function configure()
    {
        $opensAt = $this->opensAt();
        $startsAt = $this->starts_at;

        if ($opensAt->isFuture()) {
            $this->status = self::BROADCAST_CLOSED;
            return;
        }

        if ($startsAt->isFuture()) {
            $this->trailer = $this->loadTrailer();
            $this->status = self::BROADCAST_OPEN;
            return;
        }

        $durationInSeconds = self::LIVE_BROADCAST_DURATION * 60;

        if (! $this->live) {
            // Load the sermon so we can calculate end time.
            $sermon = $this->loadSermon();
            $durationInSeconds = $sermon->duration;
        }

        $endsAt = $this->endsAt($durationInSeconds);

        if ($endsAt->isFuture()) {
            if (isset($sermon)) {
                $this->sermon = $sermon;
            }
            $this->time_elapsed = $startsAt->diffInSeconds();
            $this->status = self::BROADCAST_IN_PROGRESS;
            return;
        }

        $this->status = self::BROADCAST_ENDED;
    }
}
