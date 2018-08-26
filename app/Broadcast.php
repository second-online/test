<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Broadcast extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'day', 'time', 'live', 'enabled'];

    /**
     * Get the comments for the broadcast.
     */
    public function comments()
    {
        return $this->hasMany('App\BroadcastComment');
    }
}
