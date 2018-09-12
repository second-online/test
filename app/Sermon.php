<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sermon extends Model
{
    /**
     * Get the speaker that belongs to this sermon.
     */
    public function speaker()
    {
        return $this->belongsTo('App\Speaker');
    }

    /**
     * Get the series that belongs to this sermon.
     */
    public function series()
    {
        return $this->belongsTo('App\Series');
    }

    /**
     * Get the duration in seconds.
     *
     * @param  string  $value
     * @return string
     */
    public function getDurationAttribute($value)
    {   
        $time = explode(':', $value);

        return ($time[0] * 60 * 60) + ($time[1] * 60) + $time [2];
    }
}
