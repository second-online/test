<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'email', 'password', 'remember_token', 'updated_at', 'created_at', 'email_verified_at',
    ];

    /**
     * The roles that belong to the user.
     */
    public function roles()
    {
        return $this->belongsToMany('App\Role')->withTimestamps();
    }

    /**
     * Check if a user has a specific role.
     *
     * @return boolean
     */
    public function hasRole($role)
    {
        return $this->roles()->where('name', $role)->exists();
    } 

    /**
     * Check if a user is a host.
     *
     * @return boolean
     */
    public function isHost()
    {
        return $this->hasRole('host');
    }
}
