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
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'enabled', 'created_at', 'updated_at',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'day', 'time', 'live', 'enabled'];

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
     * Get live attribute.
     *
     * @param  int  $value
     * @return boolean
     */
    public function getLiveAttribute($value)
    {
        return $value ? true : false;
    }

}
