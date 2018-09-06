<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HostComment extends Model
{
    /**
     * Get the user that owns the comment.
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
