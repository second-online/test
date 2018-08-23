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
}
