<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'postsQty', 'avatar', 'notifs'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function userPosts()
    {
        return $this->hasMany('App\Post');
    }

    public function userComments()
    {
        return $this->hasMany('App\Comment');
    }

    public function userGroups()
    {
        return $this->hasMany('App\Group');
    }

    public function comment()
    {
        return $this->hasOne('App\Comment');
    }
}
