<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Events\BroadcastSaving;

class Broadcast extends Model
{
    /**
     * The event map for the model.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'saving' => BroadcastSaving::class
    ];
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
